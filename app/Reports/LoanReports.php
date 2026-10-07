<?php

namespace App\Reports\Loans;

use App\Models\Pinjaman;
use App\Models\PinjamanJadwal;
use App\Models\PinjamanPembayaran;
use App\Models\PinjamanRestrukturisasi;
use App\Reports\ReportDefinition;
use App\Reports\ReportFilter;
use App\Reports\ReportResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanPortfolioReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-portofolio';
    }

    public function name(): string
    {
        return 'Portofolio Pinjaman';
    }

    public function description(): string
    {
        return 'Outstanding per produk + kolektabilitas.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::asOf(), ReportFilter::cabang());
    }

    public function supportsChart(): bool
    {
        return true;
    }

    public function run(array $params): ReportResult
    {
        $rows = Pinjaman::with('produk')->whereIn('status', ['aktif', 'macet'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->get()->groupBy(fn ($p) => $p->produk->nama ?? '-')
            ->map(fn ($g, $prod) => [
                'produk' => $prod, 'akad' => $g->count(),
                'outstanding' => $g->sum('saldo_pokok'),
                'tunggakan' => $g->where('tunggakan_hari', '>', 0)->sum('saldo_pokok'),
            ])->values()->all();
        $tot = collect($rows)->sum('outstanding');

        return new ReportResult(
            [['key' => 'produk', 'label' => 'Produk'], ['key' => 'akad', 'label' => 'Akad', 'align' => 'right'],
                $this->moneyCol('outstanding', 'Outstanding'), $this->moneyCol('tunggakan', 'Tunggakan')],
            $rows,
            ['akad' => collect($rows)->sum('akad'), 'outstanding' => $tot, 'tunggakan' => collect($rows)->sum('tunggakan')],
            [['label' => 'PAR', 'value' => $tot > 0 ? round(collect($rows)->sum('tunggakan') / $tot * 100, 2).'%' : '0%', 'format' => 'text']],
            [[
                'type' => 'bar', 'title' => 'Outstanding per Produk',
                'labels' => collect($rows)->pluck('produk')->all(),
                'datasets' => [['label' => 'Rp', 'data' => collect($rows)->pluck('outstanding')->all(), 'color' => '#d97706']],
            ]]
        );
    }
}

class DisbursementReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-pencairan';
    }

    public function name(): string
    {
        return 'Pencairan (Disbursement)';
    }

    public function description(): string
    {
        return 'Akad cair pada periode.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::dateRange(), ReportFilter::cabang());
    }

    public function run(array $params): ReportResult
    {
        $rows = Pinjaman::with(['anggota', 'produk'])
            ->whereNotNull('tanggal_pencairan')
            ->whereDate('tanggal_pencairan', '>=', $params['dari'])->whereDate('tanggal_pencairan', '<=', $params['sampai'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->orderBy('tanggal_pencairan')->limit(2000)->get()
            ->map(fn ($p) => [
                'tanggal' => $p->tanggal_pencairan, 'nomor' => $p->nomor_akad,
                'anggota' => $p->anggota->nama ?? '—', 'produk' => $p->produk->nama ?? '—',
                'plafon' => (int) $p->plafon,
            ])->all();

        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'nomor', 'label' => 'No. Akad'],
                ['key' => 'anggota', 'label' => 'Anggota'], ['key' => 'produk', 'label' => 'Produk'], $this->moneyCol('plafon', 'Plafon')],
            $rows, ['plafon' => collect($rows)->sum('plafon')], []
        );
    }
}

class CollectionReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-koleksi';
    }

    public function name(): string
    {
        return 'Koleksi Angsuran';
    }

    public function description(): string
    {
        return 'Pembayaran terverifikasi + collection rate.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::dateRange(), ReportFilter::cabang());
    }

    public function supportsChart(): bool
    {
        return true;
    }

    public function run(array $params): ReportResult
    {
        $bayar = PinjamanPembayaran::where('status', 'disetujui')
            ->whereDate('tanggal', '>=', $params['dari'])->whereDate('tanggal', '<=', $params['sampai'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->whereHas('pinjaman', fn ($p) => $p->where('cabang_id', $c)));
        $total = (int) (clone $bayar)->sum('total_bayar');
        $n = (int) (clone $bayar)->count();

        $jatuhTempo = (int) PinjamanJadwal::whereDate('tanggal_jatuh_tempo', '>=', $params['dari'])->whereDate('tanggal_jatuh_tempo', '<=', $params['sampai'])
            ->sum('total_angsuran');
        $rate = $jatuhTempo > 0 ? round($total / $jatuhTempo * 100, 2) : 0;

        return new ReportResult(
            [['key' => 'uraian', 'label' => 'Uraian'], $this->moneyCol('jumlah', 'Jumlah')],
            [
                ['uraian' => "Pembayaran disetujui ({$n}x)", 'jumlah' => $total],
                ['uraian' => 'Tagihan jatuh tempo periode', 'jumlah' => $jatuhTempo],
            ],
            [],
            [
                ['label' => 'Terkumpul', 'value' => $total, 'format' => 'money'],
                ['label' => 'Collection Rate', 'value' => $rate.'%', 'format' => 'text'],
            ],
            [[
                'type' => 'doughnut', 'title' => 'Koleksi vs Tagihan',
                'labels' => ['Terkumpul', 'Belum'],
                'datasets' => [['label' => 'Rp', 'data' => [$total, max(0, $jatuhTempo - $total)], 'color' => '#059669']],
            ]]
        );
    }
}

class OverdueReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-tunggakan';
    }

    public function name(): string
    {
        return 'Tunggakan (Overdue)';
    }

    public function description(): string
    {
        return 'Jadwal telat + sisa tagihan.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::asOf(), ReportFilter::cabang());
    }

    public function run(array $params): ReportResult
    {
        $rows = PinjamanJadwal::with(['pinjaman.anggota'])
            ->whereIn('status', ['jatuh_tempo', 'telat'])
            ->whereDate('tanggal_jatuh_tempo', '<=', $params['sampai'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->whereHas('pinjaman', fn ($p) => $p->where('cabang_id', $c)))
            ->orderBy('tanggal_jatuh_tempo')->limit(2000)->get()
            ->map(function ($j) use ($params) {
                $sisa = max(0, ($j->total_angsuran + $j->denda) - ($j->terbayar_pokok + $j->terbayar_margin + $j->terbayar_denda));
                $hari = (int) Carbon::parse($j->tanggal_jatuh_tempo)->diffInDays(Carbon::parse($params['sampai']));

                return [
                    'akad' => $j->pinjaman->nomor_akad ?? '—', 'anggota' => $j->pinjaman->anggota->nama ?? '—',
                    'jatuh_tempo' => $j->tanggal_jatuh_tempo, 'sisa' => $sisa, 'hari' => $hari,
                ];
            })->filter(fn ($r) => $r['sisa'] > 0)->values()->all();

        return new ReportResult(
            [['key' => 'akad', 'label' => 'No. Akad'], ['key' => 'anggota', 'label' => 'Anggota'],
                ['key' => 'jatuh_tempo', 'label' => 'Jatuh Tempo', 'format' => 'date'], $this->moneyCol('sisa', 'Sisa Tagihan'), ['key' => 'hari', 'label' => 'Telat (hr)', 'align' => 'right']],
            $rows, ['sisa' => collect($rows)->sum('sisa')], []
        );
    }
}

class AgingReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-aging';
    }

    public function name(): string
    {
        return 'Aging Piutang (7 Bucket)';
    }

    public function description(): string
    {
        return 'Current, 1–7, 8–30, 31–60, 61–90, 91–180, 180+ hari.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::asOf(), ReportFilter::cabang());
    }

    public function supportsChart(): bool
    {
        return true;
    }

    public function run(array $params): ReportResult
    {
        $diffExpr = DB::getDriverName() === 'sqlite'
            ? 'CAST(julianday(?) - julianday(tanggal_jatuh_tempo) AS INTEGER)'
            : 'DATEDIFF(?, tanggal_jatuh_tempo)';
        $buckets = ['current' => 0, 'd1_7' => 0, 'd8_30' => 0, 'd31_60' => 0, 'd61_90' => 0, 'd91_180' => 0, 'd180' => 0];
        $rows = Pinjaman::with(['anggota', 'produk'])->whereIn('status', ['aktif', 'macet'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->get()->map(function ($p) use ($params, &$buckets, $diffExpr) {
                $sisa = max(0, (int) $p->saldo_pokok + (int) $p->saldo_margin);
                $maxTelat = (int) ($p->jadwal()->whereIn('status', ['jatuh_tempo', 'telat'])
                    ->whereDate('tanggal_jatuh_tempo', '<=', $params['sampai'])
                    ->selectRaw("MAX({$diffExpr}) as d", [$params['sampai']])->value('d') ?? 0);
                $b = match (true) {
                    $maxTelat <= 0 => 'current', $maxTelat <= 7 => 'd1_7', $maxTelat <= 30 => 'd8_30',
                    $maxTelat <= 60 => 'd31_60', $maxTelat <= 90 => 'd61_90', $maxTelat <= 180 => 'd91_180', default => 'd180',
                };
                $buckets[$b] += $sisa;

                return ['akad' => $p->nomor_akad, 'anggota' => $p->anggota->nama ?? '—', 'outstanding' => $sisa, 'hari' => $maxTelat, 'bucket' => $b];
            })->filter(fn ($r) => $r['outstanding'] > 0)->values()->all();
        $labels = ['Lancar', '1–7', '8–30', '31–60', '61–90', '91–180', '180+'];

        return new ReportResult(
            [['key' => 'akad', 'label' => 'No. Akad'], ['key' => 'anggota', 'label' => 'Anggota'],
                $this->moneyCol('outstanding', 'Outstanding'), ['key' => 'hari', 'label' => 'Telat (hr)', 'align' => 'right'], ['key' => 'bucket', 'label' => 'Bucket', 'format' => 'badge']],
            $rows,
            ['outstanding' => collect($rows)->sum('outstanding')],
            [['label' => 'PAR >30hr', 'value' => $this->par($buckets).'%', 'format' => 'text']],
            [[
                'type' => 'bar', 'title' => 'Aging Outstanding',
                'labels' => $labels,
                'datasets' => [['label' => 'Rp', 'data' => array_values($buckets), 'color' => '#d97706']],
            ]]
        );
    }

    private function par(array $b): float
    {
        $tot = array_sum($b);
        if ($tot <= 0) {
            return 0;
        }

        return round(($b['d31_60'] + $b['d61_90'] + $b['d91_180'] + $b['d180']) / $tot * 100, 2);
    }
}

class MaturityReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-jatuh-tempo';
    }

    public function name(): string
    {
        return 'Jadwal Jatuh Tempo';
    }

    public function description(): string
    {
        return 'Angsuran jatuh tempo 30 hari ke depan.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function run(array $params): ReportResult
    {
        $sampai = now()->addDays(30)->toDateString();
        $rows = PinjamanJadwal::with(['pinjaman.anggota'])
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo'])
            ->whereDate('tanggal_jatuh_tempo', '>=', now()->toDateString())->whereDate('tanggal_jatuh_tempo', '<=', $sampai)
            ->orderBy('tanggal_jatuh_tempo')->limit(2000)->get()
            ->map(fn ($j) => [
                'tanggal' => $j->tanggal_jatuh_tempo, 'akad' => $j->pinjaman->nomor_akad ?? '—',
                'anggota' => $j->pinjaman->anggota->nama ?? '—', 'angsuran' => (int) $j->total_angsuran,
            ])->all();

        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'akad', 'label' => 'No. Akad'],
                ['key' => 'anggota', 'label' => 'Anggota'], $this->moneyCol('angsuran', 'Angsuran')],
            $rows, ['angsuran' => collect($rows)->sum('angsuran')], []
        );
    }
}

class RestructuringReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-restrukturisasi';
    }

    public function name(): string
    {
        return 'Restrukturisasi';
    }

    public function description(): string
    {
        return 'Riwayat restrukturisasi akad bermasalah.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return ReportFilter::dateRange();
    }

    public function run(array $params): ReportResult
    {
        $rows = PinjamanRestrukturisasi::with(['pinjaman.anggota'])
            ->whereBetween('tanggal', [$params['dari'], $params['sampai']])
            ->orderByDesc('tanggal')->limit(1000)->get()
            ->map(fn ($r) => [
                'tanggal' => $r->tanggal->toDateString(), 'akad' => $r->pinjaman->nomor_akad ?? '—',
                'anggota' => $r->pinjaman->anggota->nama ?? '—', 'jenis' => $r->jenis,
                'alasan' => Str::limit($r->alasan ?? '-', 60),
            ])->all();

        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'akad', 'label' => 'No. Akad'],
                ['key' => 'anggota', 'label' => 'Anggota'], ['key' => 'jenis', 'label' => 'Jenis', 'format' => 'badge'], ['key' => 'alasan', 'label' => 'Alasan']],
            $rows, [], []
        );
    }
}

class RiskSummaryReport extends ReportDefinition
{
    public function key(): string
    {
        return 'pinjaman-risiko';
    }

    public function name(): string
    {
        return 'Ringkasan Risiko';
    }

    public function description(): string
    {
        return 'Kolektabilitas, PAR, collection rate, top tunggakan.';
    }

    public function category(): string
    {
        return 'loans';
    }

    public function filters(): array
    {
        return array_merge(ReportFilter::asOf(), ReportFilter::cabang());
    }

    public function run(array $params): ReportResult
    {
        $q = Pinjaman::whereIn('status', ['aktif', 'macet'])
            ->when($this->cabangId($params), fn ($qq, $c) => $qq->where('cabang_id', $c));
        $tot = (int) (clone $q)->sum('saldo_pokok');
        $byKol = (clone $q)->selectRaw('kolektabilitas, COUNT(*) c, COALESCE(SUM(saldo_pokok),0) s')->groupBy('kolektabilitas')->get()
            ->map(fn ($r) => ['kolektabilitas' => $r->kolektabilitas ?? '-', 'akad' => (int) $r->c, 'outstanding' => (int) $r->s])->all();
        $tunggakan = (int) (clone $q)->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');

        return new ReportResult(
            [['key' => 'kolektabilitas', 'label' => 'Kolektabilitas', 'format' => 'badge'], ['key' => 'akad', 'label' => 'Akad', 'align' => 'right'], $this->moneyCol('outstanding', 'Outstanding')],
            $byKol, ['akad' => collect($byKol)->sum('akad'), 'outstanding' => $tot],
            [
                ['label' => 'PAR', 'value' => $tot > 0 ? round($tunggakan / $tot * 100, 2).'%' : '0%', 'format' => 'text'],
                ['label' => 'Outstanding', 'value' => $tot, 'format' => 'money'],
            ]
        );
    }
}
