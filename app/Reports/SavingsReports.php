<?php

namespace App\Reports\Savings;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use App\Reports\ReportDefinition;
use App\Reports\ReportFilter;
use App\Reports\ReportResult;

class SavingsSummaryReport extends ReportDefinition
{
    public function key(): string { return 'simpanan-ringkasan'; }
    public function name(): string { return 'Ringkasan Simpanan'; }
    public function description(): string { return 'Total per jenis produk + rekening aktif.'; }
    public function category(): string { return 'savings'; }
    public function filters(): array { return array_merge(ReportFilter::asOf(), ReportFilter::cabang()); }
    public function supportsChart(): bool { return true; }

    public function run(array $params): ReportResult
    {
        $rows = Simpanan::with('produk')->where('status', 'aktif')
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->get()->groupBy(fn ($s) => $s->produk->jenis ?? '-')
            ->map(fn ($g, $jenis) => [
                'jenis' => ucfirst($jenis),
                'rekening' => $g->count(),
                'saldo' => $g->sum('saldo'),
            ])->values()->all();
        return new ReportResult(
            [['key' => 'jenis', 'label' => 'Jenis'], ['key' => 'rekening', 'label' => 'Rekening', 'align' => 'right'], $this->moneyCol('saldo', 'Saldo (Rp)')],
            $rows,
            ['rekening' => collect($rows)->sum('rekening'), 'saldo' => collect($rows)->sum('saldo')],
            [['label' => 'Total Saldo', 'value' => collect($rows)->sum('saldo'), 'format' => 'money']],
            [[
                'type' => 'doughnut', 'title' => 'Komposisi Simpanan',
                'labels' => collect($rows)->pluck('jenis')->all(),
                'datasets' => [['label' => 'Rp', 'data' => collect($rows)->pluck('saldo')->all(), 'color' => '#059669']],
            ]]
        );
    }
}

class SavingsGrowthReport extends ReportDefinition
{
    public function key(): string { return 'simpanan-pertumbuhan'; }
    public function name(): string { return 'Pertumbuhan Simpanan'; }
    public function description(): string { return 'Arus setor vs tarik 12 bulan terakhir.'; }
    public function category(): string { return 'savings'; }
    public function supportsChart(): bool { return true; }

    public function run(array $params): ReportResult
    {
        $labels = $setor = $tarik = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->startOfMonth()->subMonths($i);
            $labels[] = $m->format('M y');
            $setor[] = (int) SimpananTransaksi::where('jenis', 'setor')->whereDate('tanggal', '>=', $m->toDateString())->whereDate('tanggal', '<=', (clone $m)->endOfMonth()->toDateString())->sum('jumlah');
            $tarik[] = (int) SimpananTransaksi::where('jenis', 'tarik')->whereDate('tanggal', '>=', $m->toDateString())->whereDate('tanggal', '<=', (clone $m)->endOfMonth()->toDateString())->sum('jumlah');
        }
        $rows = [];
        foreach ($labels as $i => $l) $rows[] = ['bulan' => $l, 'setor' => $setor[$i], 'tarik' => $tarik[$i], 'neto' => $setor[$i] - $tarik[$i]];
        return new ReportResult(
            [['key' => 'bulan', 'label' => 'Bulan'], $this->moneyCol('setor', 'Setor'), $this->moneyCol('tarik', 'Tarik'), $this->moneyCol('neto', 'Neto')],
            $rows,
            ['setor' => array_sum($setor), 'tarik' => array_sum($tarik), 'neto' => array_sum($setor) - array_sum($tarik)],
            [['label' => 'Neto 12 Bulan', 'value' => array_sum($setor) - array_sum($tarik), 'format' => 'money']],
            [[
                'type' => 'line', 'title' => 'Setor vs Tarik',
                'labels' => $labels,
                'datasets' => [
                    ['label' => 'Setor', 'data' => $setor, 'color' => '#059669'],
                    ['label' => 'Tarik', 'data' => $tarik, 'color' => '#e11d48'],
                ],
            ]]
        );
    }
}

class SavingsMutationReport extends ReportDefinition
{
    public function key(): string { return 'simpanan-mutasi'; }
    public function name(): string { return 'Mutasi Simpanan'; }
    public function description(): string { return 'Semua transaksi setor/tarik/mutasi pada periode.'; }
    public function category(): string { return 'savings'; }
    public function filters(): array
    {
        return array_merge(ReportFilter::dateRange(), ReportFilter::cabang(), [
            ['name' => 'jenis', 'type' => 'select', 'label' => 'Jenis',
                'options' => ['setor' => 'Setor', 'tarik' => 'Tarik', 'mutasi_masuk' => 'Mutasi Masuk', 'mutasi_keluar' => 'Mutasi Keluar'],
                'placeholder' => 'Semua'],
        ]);
    }

    public function run(array $params): ReportResult
    {
        $rows = SimpananTransaksi::with(['simpanan.anggota'])
            ->whereDate('tanggal', '>=', $params['dari'])->whereDate('tanggal', '<=', $params['sampai'])
            ->when(! empty($params['jenis']), fn ($q) => $q->where('jenis', $params['jenis']))
            ->when($this->cabangId($params), fn ($q, $c) => $q->whereHas('simpanan', fn ($s) => $s->where('cabang_id', $c)))
            ->orderBy('tanggal')->orderBy('id')->limit(2000)->get()
            ->map(fn ($t) => [
                'tanggal' => $t->tanggal->toDateString(), 'nomor' => $t->nomor,
                'anggota' => $t->simpanan->anggota->nama ?? '—',
                'jenis' => $t->jenis, 'jumlah' => (int) $t->jumlah,
            ])->all();
        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'nomor', 'label' => 'Nomor'],
                ['key' => 'anggota', 'label' => 'Anggota'], ['key' => 'jenis', 'label' => 'Jenis', 'format' => 'badge'], $this->moneyCol('jumlah', 'Jumlah')],
            $rows, ['jumlah' => collect($rows)->sum('jumlah')], []
        );
    }
}

class DormantMembersReport extends ReportDefinition
{
    public function key(): string { return 'simpanan-dorman'; }
    public function name(): string { return 'Anggota Dorman'; }
    public function description(): string { return 'Anggota aktif tanpa transaksi > 6 bulan.'; }
    public function category(): string { return 'savings'; }

    public function run(array $params): ReportResult
    {
        $batas = now()->subMonths(6)->toDateString();
        $rows = Anggota::where('status', 'aktif')->with(['simpanan' => fn ($q) => $q->where('status', 'aktif')])
            ->get()->filter(function ($a) use ($batas) {
                $ids = $a->simpanan->pluck('id');
                if ($ids->isEmpty()) return true;
                return ! SimpananTransaksi::whereIn('simpanan_id', $ids)->whereDate('tanggal', '>=', $batas)->exists();
            })->map(fn ($a) => [
                'nomor' => $a->nomor_anggota, 'nama' => $a->nama,
                'saldo' => $a->simpanan->sum('saldo'),
            ])->values()->all();
        return new ReportResult(
            [['key' => 'nomor', 'label' => 'No. Anggota'], ['key' => 'nama', 'label' => 'Nama'], $this->moneyCol('saldo', 'Saldo')],
            $rows, ['saldo' => collect($rows)->sum('saldo')],
            [['label' => 'Anggota Dorman', 'value' => count($rows).' orang', 'format' => 'text']]
        );
    }
}

class TopSaversReport extends ReportDefinition
{
    public function key(): string { return 'simpanan-top'; }
    public function name(): string { return 'Top Penabung'; }
    public function description(): string { return '20 saldo terbesar — tanpa nominal sensitif berlebih? nominal tampil untuk staf berizin.'; }
    public function category(): string { return 'savings'; }
    public function permission(): string { return 'reports.view'; }

    public function run(array $params): ReportResult
    {
        $rows = Anggota::where('status', 'aktif')->withSum(['simpanan as total_saldo' => fn ($q) => $q->where('status', 'aktif')], 'saldo')
            ->orderByDesc('total_saldo')->limit(20)->get()
            ->map(fn ($a, $i) => ['rank' => $i + 1, 'nomor' => $a->nomor_anggota, 'nama' => $a->nama, 'saldo' => (int) $a->total_saldo])->all();
        return new ReportResult(
            [['key' => 'rank', 'label' => '#', 'align' => 'right'], ['key' => 'nomor', 'label' => 'No. Anggota'], ['key' => 'nama', 'label' => 'Nama'], $this->moneyCol('saldo', 'Saldo')],
            $rows, [], []
        );
    }
}
