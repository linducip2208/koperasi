<?php

namespace App\Reports\Financial;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\Kas;
use App\Reports\ReportDefinition;
use App\Reports\ReportFilter;
use App\Reports\ReportResult;

/** Shared helpers untuk report finansial. */
trait FinancialHelpers
{
    protected function viewerUrl(string $report, array $params = []): string
    {
        return route('filament.admin.pages.report-viewer', ['report' => $report] + $params);
    }
}

class BalanceSheetReport extends ReportDefinition
{
    use FinancialHelpers;
    public function key(): string { return 'neraca'; }
    public function name(): string { return 'Neraca (Posisi Keuangan)'; }
    public function description(): string { return 'Aset, kewajiban, dan ekuitas per tanggal — SAK EP.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::asOf(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::neraca($params['sampai'], $this->cabangId($params));
        $rows = [];
        foreach (['aset' => 'ASET', 'kewajiban' => 'KEWAJIBAN', 'ekuitas' => 'EKUITAS'] as $k => $label) {
            foreach ($d[$k] as $r) {
                $rows[] = ['kelompok' => $label, 'kode' => $r['kode'], 'akun' => $r['nama'], 'saldo' => $r['saldo']];
            }
        }
        $tA = collect($d['aset'])->sum('saldo');
        $tK = collect($d['kewajiban'])->sum('saldo');
        $tE = collect($d['ekuitas'])->sum('saldo');
        return new ReportResult(
            [['key' => 'kelompok', 'label' => 'Kelompok'], ['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'], $this->moneyCol('saldo', 'Saldo (Rp)')],
            $rows,
            ['saldo' => $tA],
            [
                ['label' => 'Total Aset', 'value' => $tA, 'format' => 'money'],
                ['label' => 'Total Kewajiban', 'value' => $tK, 'format' => 'money'],
                ['label' => 'Total Ekuitas', 'value' => $tE, 'format' => 'money'],
                ['label' => 'Balance Check', 'value' => $tA - ($tK + $tE) === 0 ? 'Balance' : 'SELISIH', 'format' => 'badge'],
            ],
            [],
            ['total_aset' => $tA, 'total_kewajiban' => $tK, 'total_ekuitas' => $tE]
        );
    }
}

class ProfitLossReport extends ReportDefinition
{
    public function key(): string { return 'laba-rugi'; }
    public function name(): string { return 'Laba Rugi (SHU)'; }
    public function description(): string { return 'Pendapatan, beban, dan SHU periode berjalan.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::dateRange(), ReportFilter::cabang()); }
    public function supportsChart(): bool { return true; }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::labaRugi($params['dari'], $params['sampai'], $this->cabangId($params));
        $rows = [];
        foreach ($d['pendapatan'] as $r) $rows[] = ['kelompok' => 'PENDAPATAN', 'kode' => $r['kode'], 'akun' => $r['nama'], 'jumlah' => $r['saldo']];
        foreach ($d['beban'] as $r) $rows[] = ['kelompok' => 'BEBAN', 'kode' => $r['kode'], 'akun' => $r['nama'], 'jumlah' => -$r['saldo']];
        return new ReportResult(
            [['key' => 'kelompok', 'label' => 'Kelompok'], ['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'], $this->moneyCol('jumlah', 'Jumlah (Rp)')],
            $rows,
            ['jumlah' => $d['shu']],
            [
                ['label' => 'Total Pendapatan', 'value' => $d['total_pendapatan'], 'format' => 'money'],
                ['label' => 'Total Beban', 'value' => $d['total_beban'], 'format' => 'money'],
                ['label' => 'SHU', 'value' => $d['shu'], 'format' => 'money'],
            ],
            [[
                'type' => 'bar', 'title' => 'Pendapatan vs Beban',
                'labels' => ['Pendapatan', 'Beban', 'SHU'],
                'datasets' => [['label' => 'Rp', 'data' => [$d['total_pendapatan'], $d['total_beban'], $d['shu']], 'color' => '#059669']],
            ]]
        );
    }
}

class CashFlowReport extends ReportDefinition
{
    public function key(): string { return 'arus-kas'; }
    public function name(): string { return 'Arus Kas'; }
    public function description(): string { return 'Penerimaan dan pengeluaran kas/bank periode.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::dateRange(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::arusKas($params['dari'], $params['sampai'], $this->cabangId($params));
        return new ReportResult(
            [['key' => 'uraian', 'label' => 'Uraian'], $this->moneyCol('jumlah', 'Jumlah (Rp)')],
            [
                ['uraian' => 'Penerimaan kas', 'jumlah' => $d['masuk']],
                ['uraian' => 'Pengeluaran kas', 'jumlah' => -$d['keluar']],
            ],
            ['jumlah' => $d['net']],
            [
                ['label' => 'Kas Masuk', 'value' => $d['masuk'], 'format' => 'money'],
                ['label' => 'Kas Keluar', 'value' => $d['keluar'], 'format' => 'money'],
                ['label' => 'Kas Bersih', 'value' => $d['net'], 'format' => 'money'],
            ]
        );
    }
}

class EquityChangesReport extends ReportDefinition
{
    public function key(): string { return 'perubahan-ekuitas'; }
    public function name(): string { return 'Perubahan Ekuitas'; }
    public function description(): string { return 'Saldo awal, mutasi, saldo akhir ekuitas — SAK EP.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::dateRange(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::perubahanEkuitas($params['dari'], $params['sampai'], $this->cabangId($params));
        $rows = array_map(fn ($r) => [
            'kode' => $r['kode'], 'akun' => $r['nama'],
            'saldo_awal' => $r['saldo_awal'], 'mutasi' => $r['mutasi'], 'saldo_akhir' => $r['saldo_akhir'],
        ], $d['rincian']);
        return new ReportResult(
            [['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'],
                $this->moneyCol('saldo_awal', 'Saldo Awal'), $this->moneyCol('mutasi', 'Mutasi'), $this->moneyCol('saldo_akhir', 'Saldo Akhir')],
            $rows,
            ['saldo_awal' => $d['total_awal'], 'mutasi' => $d['total_mutasi'], 'saldo_akhir' => $d['total_akhir']],
            [['label' => 'Ekuitas Akhir', 'value' => $d['total_akhir'], 'format' => 'money']]
        );
    }
}

class TrialBalanceReport extends ReportDefinition
{
    public function key(): string { return 'trial-balance'; }
    public function name(): string { return 'Neraca Saldo'; }
    public function description(): string { return 'Debit vs kredit semua akun postable — harus balance.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::asOf(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::trialBalance($params['sampai'], $this->cabangId($params));
        $rows = array_map(fn ($r) => ['kode' => $r['kode'], 'akun' => $r['nama'], 'debit' => $r['debit'], 'kredit' => $r['kredit']], $d['rows']);
        return new ReportResult(
            [['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'],
                $this->moneyCol('debit', 'Debit'), $this->moneyCol('kredit', 'Kredit')],
            $rows,
            ['debit' => $d['total_debit'], 'kredit' => $d['total_kredit']],
            [['label' => $d['total_debit'] === $d['total_kredit'] ? 'Balance' : 'SELISIH', 'value' => $d['total_debit'] - $d['total_kredit'], 'format' => 'money']]
        );
    }
}

class GeneralLedgerReport extends ReportDefinition
{
    use FinancialHelpers;
    public function key(): string { return 'buku-besar'; }
    public function name(): string { return 'Buku Besar'; }
    public function description(): string { return 'Mutasi kronologis + saldo berjalan per akun.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array
    {
        return array_merge(ReportFilter::dateRange(), ReportFilter::cabang(), ReportFilter::account());
    }

    public function run(array $params): ReportResult
    {
        if (empty($params['coa_id'])) {
            return new ReportResult([], [], [], [['label' => 'Pilih akun terlebih dahulu', 'value' => '-', 'format' => 'text']]);
        }
        $d = LaporanKeuanganService::bukuBesar((int) $params['coa_id'], $params['dari'], $params['sampai'], $this->cabangId($params));
        $rows = array_map(fn ($l) => [
            'tanggal' => $l['tanggal'], 'nomor' => $l['nomor'], 'keterangan' => $l['keterangan'],
            'debit' => $l['debit'], 'kredit' => $l['kredit'], 'saldo' => $l['saldo'],
        ], $d['lines']);
        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'nomor', 'label' => 'No. Jurnal'], ['key' => 'keterangan', 'label' => 'Keterangan'],
                $this->moneyCol('debit', 'Debit'), $this->moneyCol('kredit', 'Kredit'), $this->moneyCol('saldo', 'Saldo')],
            $rows,
            ['saldo' => $d['saldo_akhir']],
            [
                ['label' => 'Akun', 'value' => $d['coa']['kode'].' — '.$d['coa']['nama'], 'format' => 'text'],
                ['label' => 'Saldo Awal', 'value' => $d['saldo_awal'], 'format' => 'money'],
                ['label' => 'Saldo Akhir', 'value' => $d['saldo_akhir'], 'format' => 'money'],
            ]
        );
    }
}

class JournalReport extends ReportDefinition
{
    use FinancialHelpers;
    public function key(): string { return 'jurnal-umum'; }
    public function name(): string { return 'Jurnal Umum'; }
    public function description(): string { return 'Daftar jurnal posted + drill-down ke dokumen admin.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::dateRange(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $rows = Jurnal::with('details')
            ->where('is_posted', true)
            ->whereDate('tanggal', '>=', $params['dari'])->whereDate('tanggal', '<=', $params['sampai'])
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->orderBy('tanggal')->orderBy('id')
            ->limit(2000)->get()
            ->map(fn ($j) => [
                'tanggal' => $j->tanggal->toDateString(), 'nomor' => $j->nomor,
                'keterangan' => $j->keterangan, 'debit' => (int) $j->total_debit, 'kredit' => (int) $j->total_kredit,
                '__link' => url('/admin/jurnals/'.$j->id.'/edit'),
            ])->all();
        return new ReportResult(
            [['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'], ['key' => 'nomor', 'label' => 'Nomor'], ['key' => 'keterangan', 'label' => 'Keterangan'],
                $this->moneyCol('debit', 'Debit'), $this->moneyCol('kredit', 'Kredit')],
            $rows,
            ['debit' => collect($rows)->sum('debit'), 'kredit' => collect($rows)->sum('kredit')],
            [['label' => 'Jurnal', 'value' => count($rows).' baris (maks 2000 — persempit periode bila lebih)', 'format' => 'text']]
        );
    }
}

class CashPositionReport extends ReportDefinition
{
    public function key(): string { return 'posisi-kas'; }
    public function name(): string { return 'Posisi Kas & Bank'; }
    public function description(): string { return 'Saldo per kas/bank + total likuiditas.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::asOf(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $rows = Kas::with('coa')->where('aktif', true)
            ->when($this->cabangId($params), fn ($q, $c) => $q->where('cabang_id', $c))
            ->get()->map(function ($k) use ($params) {
                $saldo = $k->coa ? \App\Domain\Akuntansi\LaporanKeuanganService::saldoAkun($k->coa, null, $params['sampai'], $this->cabangId($params)) : (int) $k->saldo;
                return ['kas' => $k->nama, 'tipe' => $k->tipe, 'saldo' => $saldo];
            })->all();
        return new ReportResult(
            [['key' => 'kas', 'label' => 'Kas / Bank'], ['key' => 'tipe', 'label' => 'Tipe', 'format' => 'badge'], $this->moneyCol('saldo', 'Saldo (Rp)')],
            $rows,
            ['saldo' => collect($rows)->sum('saldo')],
            [['label' => 'Total Likuid', 'value' => collect($rows)->sum('saldo'), 'format' => 'money']]
        );
    }
}

class IncomeExpenseReport extends ReportDefinition
{
    public function key(): string { return 'rekap-pendapatan-beban'; }
    public function name(): string { return 'Rekap Pendapatan & Beban'; }
    public function description(): string { return 'Rincian pendapatan dan beban per akun.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::dateRange(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $d = LaporanKeuanganService::labaRugi($params['dari'], $params['sampai'], $this->cabangId($params));
        $rows = [];
        foreach ($d['pendapatan'] as $r) $rows[] = ['kelompok' => 'PENDAPATAN', 'kode' => $r['kode'], 'akun' => $r['nama'], 'jumlah' => $r['saldo']];
        foreach ($d['beban'] as $r) $rows[] = ['kelompok' => 'BEBAN', 'kode' => $r['kode'], 'akun' => $r['nama'], 'jumlah' => $r['saldo']];
        return new ReportResult(
            [['key' => 'kelompok', 'label' => 'Kelompok'], ['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'], $this->moneyCol('jumlah', 'Jumlah (Rp)')],
            $rows, [], []
        );
    }
}

class ReceivablePayableReport extends ReportDefinition
{
    public function key(): string { return 'piutang-hutang'; }
    public function name(): string { return 'Piutang & Hutang'; }
    public function description(): string { return 'Posisi akun piutang dan hutang dari COA.'; }
    public function category(): string { return 'financial'; }
    public function filters(): array { return array_merge(ReportFilter::asOf(), ReportFilter::cabang()); }

    public function run(array $params): ReportResult
    {
        $match = fn ($q) => $q->where('is_postable', true)->where('is_aktif', true)
            ->where(function ($w) {
                $w->where('nama', 'like', '%piutang%')->orWhere('nama', 'like', '%hutang%')
                  ->orWhere('nama', 'like', '%utang%')->orWhere('nama', 'like', '%tagihan%');
            });
        $rows = $match(Coa::query())->orderBy('kode')->get()->map(function ($c) use ($params) {
            return [
                'kode' => $c->kode, 'akun' => $c->nama,
                'posisi' => $c->tipe === 'aset' ? 'PIUTANG' : 'HUTANG',
                'saldo' => LaporanKeuanganService::saldoAkun($c, null, $params['sampai'], $this->cabangId($params)),
            ];
        })->filter(fn ($r) => $r['saldo'] != 0)->values()->all();
        return new ReportResult(
            [['key' => 'kode', 'label' => 'Kode'], ['key' => 'akun', 'label' => 'Akun'],
                ['key' => 'posisi', 'label' => 'Posisi', 'format' => 'badge'], $this->moneyCol('saldo', 'Saldo (Rp)')],
            $rows, ['saldo' => collect($rows)->sum('saldo')], []
        );
    }
}
