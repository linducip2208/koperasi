<?php

namespace App\Reports\Domain;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Rat;
use App\Models\ShuDistribusi;
use App\Models\ShuPerhitungan;
use App\Models\Simpanan;
use App\Reports\ReportDefinition;
use App\Reports\ReportFilter;
use App\Reports\ReportResult;

class MemberGrowthReport extends ReportDefinition
{
    public function key(): string { return 'anggota-pertumbuhan'; }
    public function name(): string { return 'Pertumbuhan Anggota'; }
    public function description(): string { return 'Anggota baru per bulan 12 bulan terakhir.'; }
    public function category(): string { return 'members'; }
    public function supportsChart(): bool { return true; }

    public function run(array $params): ReportResult
    {
        $labels = $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->startOfMonth()->subMonths($i);
            $labels[] = $m->format('M y');
            $data[] = Anggota::whereDate('tanggal_masuk', '>=', $m->toDateString())->whereDate('tanggal_masuk', '<=', (clone $m)->endOfMonth()->toDateString())->count();
        }
        $rows = [];
        foreach ($labels as $i => $l) $rows[] = ['bulan' => $l, 'baru' => $data[$i]];
        return new ReportResult(
            [['key' => 'bulan', 'label' => 'Bulan'], ['key' => 'baru', 'label' => 'Anggota Baru', 'align' => 'right']],
            $rows, ['baru' => array_sum($data)],
            [
                ['label' => 'Total Aktif', 'value' => Anggota::where('status', 'aktif')->count().' orang', 'format' => 'text'],
                ['label' => 'Baru 12 Bln', 'value' => array_sum($data).' orang', 'format' => 'text'],
            ],
            [[
                'type' => 'line', 'title' => 'Anggota Baru',
                'labels' => $labels,
                'datasets' => [['label' => 'Orang', 'data' => $data, 'color' => '#4f46e5']],
            ]]
        );
    }
}

class MemberStatementReport extends ReportDefinition
{
    public function key(): string { return 'anggota-statement'; }
    public function name(): string { return 'Statement Anggota (360 Ringkas)'; }
    public function description(): string { return 'Ringkasan simpanan, pinjaman, SHU per anggota. Detail penuh di Member 360.'; }
    public function category(): string { return 'members'; }
    public function filters(): array
    {
        return [[
            'name' => 'anggota_id', 'type' => 'select', 'label' => 'Anggota', 'required' => true,
            'model' => Anggota::class, 'option_label' => 'nama', 'placeholder' => 'Pilih anggota…',
        ]];
    }

    public function run(array $params): ReportResult
    {
        if (empty($params['anggota_id'])) {
            return new ReportResult([], [], [], [['label' => 'Pilih anggota terlebih dahulu', 'value' => '-', 'format' => 'text']]);
        }
        $a = Anggota::with(['simpanan.produk', 'pinjaman.produk'])->findOrFail($params['anggota_id']);
        $simpanan = $a->simpanan->where('status', 'aktif');
        $pinjaman = $a->pinjaman->whereIn('status', ['aktif', 'macet']);
        $shu = ShuDistribusi::where('anggota_id', $a->id)->sum('total_shu');
        return new ReportResult(
            [['key' => 'pos', 'label' => 'Pos'], $this->moneyCol('nilai', 'Nilai (Rp)')],
            [
                ['pos' => 'Simpanan ('.$simpanan->count().' rek)', 'nilai' => $simpanan->sum('saldo')],
                ['pos' => 'Pinjaman outstanding ('.$pinjaman->count().' akad)', 'nilai' => $pinjaman->sum('saldo_pokok')],
                ['pos' => 'SHU diterima (kumulatif)', 'nilai' => (int) $shu],
                ['pos' => 'Kekayaan bersih (simpanan − pinjaman)', 'nilai' => $simpanan->sum('saldo') - $pinjaman->sum('saldo_pokok')],
            ],
            [],
            [
                ['label' => 'Anggota', 'value' => $a->nomor_anggota.' — '.$a->nama, 'format' => 'text'],
                ['label' => 'Status', 'value' => $a->status, 'format' => 'badge'],
            ]
        );
    }
}

class ShuReport extends ReportDefinition
{
    public function key(): string { return 'shu'; }
    public function name(): string { return 'SHU & Distribusi'; }
    public function description(): string { return 'Perhitungan SHU per tahun + distribusi per anggota.'; }
    public function category(): string { return 'shu'; }
    public function filters(): array
    {
        return [[
            'name' => 'tahun', 'type' => 'select', 'label' => 'Tahun Buku', 'required' => true,
            'options' => array_combine($y = range(now()->year, now()->year - 10), $y),
        ]];
    }

    public function run(array $params): ReportResult
    {
        $tahun = (int) ($params['tahun'] ?? now()->year);
        $hit = ShuPerhitungan::where('tahun', $tahun)->first();
        if (! $hit) {
            $lr = LaporanKeuanganService::labaRugi("{$tahun}-01-01", "{$tahun}-12-31");
            return new ReportResult(
                [], [],
                [],
                [
                    ['label' => 'Tahun', 'value' => (string) $tahun, 'format' => 'text'],
                    ['label' => 'SHU berjalan (L/R)', 'value' => $lr['shu'], 'format' => 'money'],
                    ['label' => 'Status', 'value' => 'Belum ada perhitungan resmi — buat di SHU & RAT', 'format' => 'text'],
                ]
            );
        }
        $rows = ShuDistribusi::with('anggota')->where('shu_perhitungan_id', $hit->id)
            ->orderByDesc('total_shu')->limit(500)->get()
            ->map(fn ($d) => [
                'anggota' => $d->anggota->nama ?? '—', 'jasa_modal' => (int) $d->jasa_modal,
                'jasa_anggota' => (int) $d->jasa_anggota, 'jumlah' => (int) $d->total_shu,
            ])->all();
        return new ReportResult(
            [['key' => 'anggota', 'label' => 'Anggota'], $this->moneyCol('jasa_modal', 'Jasa Modal'),
                $this->moneyCol('jasa_anggota', 'Jasa Anggota'), $this->moneyCol('jumlah', 'Total')],
            $rows,
            ['jasa_modal' => collect($rows)->sum('jasa_modal'), 'jasa_anggota' => collect($rows)->sum('jasa_anggota'), 'jumlah' => collect($rows)->sum('jumlah')],
            [
                ['label' => 'SHU Total', 'value' => (int) $hit->shu_total, 'format' => 'money'],
                ['label' => 'Status', 'value' => $hit->status, 'format' => 'badge'],
            ]
        );
    }

    /** Alokasi resmi tersimpan (snapshot — tidak berubah bila formula berubah). */
    public static function alokasi(ShuPerhitungan $hit): array
    {
        return [
            ['pos' => 'Jasa Modal ('.$hit->persen_jasa_modal.'%)', 'nilai' => (int) $hit->jumlah_jasa_modal],
            ['pos' => 'Jasa Anggota ('.$hit->persen_jasa_anggota.'%)', 'nilai' => (int) $hit->jumlah_jasa_anggota],
            ['pos' => 'Dana Cadangan ('.$hit->persen_dana_cadangan.'%)', 'nilai' => (int) $hit->jumlah_dana_cadangan],
            ['pos' => 'Dana Pendidikan ('.$hit->persen_dana_pendidikan.'%)', 'nilai' => (int) $hit->jumlah_dana_pendidikan],
            ['pos' => 'Dana Sosial ('.$hit->persen_dana_sosial.'%)', 'nilai' => (int) $hit->jumlah_dana_sosial],
            ['pos' => 'Dana Pengurus ('.$hit->persen_dana_pengurus.'%)', 'nilai' => (int) $hit->jumlah_dana_pengurus],
            ['pos' => 'Dana Karyawan ('.$hit->persen_dana_karyawan.'%)', 'nilai' => (int) $hit->jumlah_dana_karyawan],
        ];
    }
}

class SyariahPortfolioReport extends ReportDefinition
{
    public function key(): string { return 'syariah-portofolio'; }
    public function name(): string { return 'Portofolio per Akad Syariah'; }
    public function description(): string { return 'Outstanding, margin, dan nisbah per akad. Hanya bila mode syariah/dual.'; }
    public function category(): string { return 'syariah'; }
    public function supportsChart(): bool { return true; }

    public const SYARIAH_AKAD = ['murabahah', 'mudharabah', 'musyarakah', 'ijarah', 'ijarah_mb', 'qardh', 'rahn', 'salam', 'istishna'];

    public function run(array $params): ReportResult
    {
        $rows = Pinjaman::with('produk')->whereIn('status', ['aktif', 'macet'])
            ->whereHas('produk', fn ($q) => $q->whereIn('akad_type', self::SYARIAH_AKAD))
            ->get()->groupBy(fn ($p) => $p->produk->akad_type ?? '-')
            ->map(fn ($g, $akad) => [
                'akad' => ucfirst($akad), 'akad_count' => $g->count(),
                'outstanding' => $g->sum('saldo_pokok'), 'margin' => $g->sum('saldo_margin'),
            ])->values()->all();
        if (empty($rows)) {
            return new ReportResult([], [], [], [['label' => 'Belum ada transaksi syariah', 'value' => 'Aktifkan produk syariah untuk melihat report ini', 'format' => 'text']]);
        }
        return new ReportResult(
            [['key' => 'akad', 'label' => 'Akad', 'format' => 'badge'], ['key' => 'akad_count', 'label' => 'Akad', 'align' => 'right'],
                $this->moneyCol('outstanding', 'Outstanding'), $this->moneyCol('margin', 'Margin')],
            $rows,
            ['akad_count' => collect($rows)->sum('akad_count'), 'outstanding' => collect($rows)->sum('outstanding'), 'margin' => collect($rows)->sum('margin')],
            [],
            [[
                'type' => 'doughnut', 'title' => 'Outstanding per Akad',
                'labels' => collect($rows)->pluck('akad')->all(),
                'datasets' => [['label' => 'Rp', 'data' => collect($rows)->pluck('outstanding')->all(), 'color' => '#0d9488']],
            ]]
        );
    }
}

class SyariahRevenueReport extends ReportDefinition
{
    public function key(): string { return 'syariah-pendapatan'; }
    public function name(): string { return 'Pendapatan & Bagi Hasil Syariah'; }
    public function description(): string { return 'Margin/ujrah/bagi hasil diterima pada periode.'; }
    public function category(): string { return 'syariah'; }
    public function filters(): array { return \App\Reports\ReportFilter::dateRange(); }

    public function run(array $params): ReportResult
    {
        $bayar = \App\Models\PinjamanPembayaran::where('status', 'disetujui')
            ->whereDate('tanggal', '>=', $params['dari'])->whereDate('tanggal', '<=', $params['sampai'])
            ->whereHas('pinjaman.produk', fn ($q) => $q->whereIn('akad_type', SyariahPortfolioReport::SYARIAH_AKAD));
        $margin = (int) (clone $bayar)->sum('alokasi_margin');
        $denda = (int) (clone $bayar)->sum('alokasi_denda');
        return new ReportResult(
            [['key' => 'pos', 'label' => 'Pos'], $this->moneyCol('nilai', 'Nilai (Rp)')],
            [
                ['pos' => 'Margin/ujrah diterima', 'nilai' => $margin],
                ['pos' => 'Denda (ta’zir)', 'nilai' => $denda],
                ['pos' => 'Total pendapatan syariah', 'nilai' => $margin + $denda],
            ],
            [], []
        );
    }
}

class RatAnnualReport extends ReportDefinition
{
    public function key(): string { return 'rat-tahunan'; }
    public function name(): string { return 'RAT Tahunan (Data)'; }
    public function description(): string { return 'Statistik RAT: hadir, quorum, voting, keputusan. Buku PDF di menu RAT.'; }
    public function category(): string { return 'rat'; }
    public function permission(): string { return 'rat.view'; }

    public function run(array $params): ReportResult
    {
        $rows = Rat::withCount(['kehadiran', 'votings'])->orderByDesc('tahun_buku')->limit(50)->get()
            ->map(fn ($r) => [
                'tahun' => $r->tahun_buku,
                'tanggal' => $r->tanggal?->toDateString(),
                'hadir' => $r->kehadiran_count.' / '.$r->jumlah_anggota_terdaftar,
                'quorum' => $r->quorum_tercapai ? 'Tercapai' : 'Belum',
                'voting' => $r->votings_count.' agenda',
                'status' => $r->status,
            ])->all();
        return new ReportResult(
            [['key' => 'tahun', 'label' => 'Tahun Buku'], ['key' => 'tanggal', 'label' => 'Tanggal', 'format' => 'date'],
                ['key' => 'hadir', 'label' => 'Hadir'], ['key' => 'quorum', 'label' => 'Quorum', 'format' => 'badge'],
                ['key' => 'voting', 'label' => 'Voting'], ['key' => 'status', 'label' => 'Status', 'format' => 'badge']],
            $rows, [], []
        );
    }
}

class FinancialRatioReport extends ReportDefinition
{
    public function key(): string { return 'rasio-keuangan'; }
    public function name(): string { return 'Rasio Keuangan'; }
    public function description(): string { return 'Likuiditas, solvabilitas, profitabilitas, pertumbuhan + ambang peringatan.'; }
    public function category(): string { return 'ratios'; }
    public function filters(): array { return array_merge(\App\Reports\ReportFilter::asOf(), \App\Reports\ReportFilter::cabang()); }
    public function permission(): string { return 'reports.view'; }

    public function run(array $params): ReportResult
    {
        $neraca = LaporanKeuanganService::neraca($params['sampai'], $this->cabangId($params));
        $lr = LaporanKeuanganService::labaRugi(now()->startOfYear()->toDateString(), $params['sampai'], $this->cabangId($params));
        $kas = LaporanKeuanganService::arusKas(now()->startOfYear()->toDateString(), $params['sampai'], $this->cabangId($params));
        $ek = LaporanKeuanganService::perubahanEkuitas(now()->startOfYear()->toDateString(), $params['sampai'], $this->cabangId($params));

        $aset = collect($neraca['aset'])->sum('saldo');
        $kew = collect($neraca['kewajiban'])->sum('saldo');
        $ekuitas = collect($neraca['ekuitas'])->sum('saldo');
        $lancar = collect($neraca['aset'])->filter(fn ($r) => str_contains(strtolower($r['nama']), 'kas') || str_contains(strtolower($r['nama']), 'bank'))->sum('saldo');

        $def = [
            ['key' => 'rasio_lancar', 'nama' => 'Rasio Lancar', 'formula' => 'Aset lancar / Kewajiban', 'num' => $lancar, 'den' => $kew, 'warn_below' => 1.0, 'satuan' => 'x'],
            ['key' => 'rasio_kas', 'nama' => 'Rasio Kas', 'formula' => 'Kas+Bank / Kewajiban', 'num' => $kas['masuk'] - $kas['keluar'] + $lancar, 'den' => $kew, 'warn_below' => 0.2, 'satuan' => 'x'],
            ['key' => 'debt_ratio', 'nama' => 'Debt Ratio', 'formula' => 'Kewajiban / Aset', 'num' => $kew, 'den' => $aset, 'warn_above' => 0.9, 'satuan' => '%'],
            ['key' => 'der', 'nama' => 'Debt to Equity', 'formula' => 'Kewajiban / Ekuitas', 'num' => $kew, 'den' => $ekuitas, 'warn_above' => 3.0, 'satuan' => 'x'],
            ['key' => 'roa', 'nama' => 'Return on Assets', 'formula' => 'SHU / Aset', 'num' => $lr['shu'], 'den' => $aset, 'warn_below' => 0.0, 'satuan' => '%'],
            ['key' => 'roe', 'nama' => 'Return on Equity', 'formula' => 'SHU / Ekuitas', 'num' => $lr['shu'], 'den' => $ekuitas, 'warn_below' => 0.0, 'satuan' => '%'],
            ['key' => 'npm', 'nama' => 'Net Profit Margin', 'formula' => 'SHU / Pendapatan', 'num' => $lr['shu'], 'den' => $lr['total_pendapatan'], 'warn_below' => 0.0, 'satuan' => '%'],
        ];

        $simp = \App\Models\Simpanan::where('status', 'aktif')->sum('saldo');
        $pinj = Pinjaman::whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok');
        $tung = Pinjaman::whereIn('status', ['aktif', 'macet'])->where('tunggakan_hari', '>', 0)->sum('saldo_pokok');

        $rows = [];
        foreach ($def as $d) {
            if ($d['den'] == 0) continue; // jangan tampilkan bila denominator invalid
            $isPct = $d['satuan'] === '%';
            $val = $isPct ? round($d['num'] / $d['den'] * 100, 2) : round($d['num'] / $d['den'], 2);
            $warn = isset($d['warn_below']) ? $val < $d['warn_below'] : $val > $d['warn_above'];
            $rows[] = [
                'rasio' => $d['nama'], 'formula' => $d['formula'],
                'pembilang' => $d['num'], 'penyebut' => $d['den'],
                'hasil' => $val.' '.$d['satuan'], 'status' => $warn ? 'Perhatian' : 'Normal',
            ];
        }
        $rows[] = ['rasio' => 'Collection Proxy (simpanan/pinjaman)', 'formula' => 'Simpanan / Outstanding', 'pembilang' => $simp, 'penyebut' => $pinj,
            'hasil' => $pinj > 0 ? round($simp / $pinj * 100, 2).' %' : '-', 'status' => 'Normal'];
        $rows[] = ['rasio' => 'Delinquency (tunggakan/outstanding)', 'formula' => 'Tunggakan / Outstanding', 'pembilang' => $tung, 'penyebut' => $pinj,
            'hasil' => $pinj > 0 ? round($tung / $pinj * 100, 2).' %' : '-', 'status' => ($pinj > 0 && $tung / $pinj > 0.05) ? 'Perhatian' : 'Normal'];

        return new ReportResult(
            [['key' => 'rasio', 'label' => 'Rasio'], ['key' => 'formula', 'label' => 'Formula'],
                $this->moneyCol('pembilang', 'Pembilang'), $this->moneyCol('penyebut', 'Penyebut'),
                ['key' => 'hasil', 'label' => 'Hasil', 'align' => 'right'], ['key' => 'status', 'label' => 'Status', 'format' => 'badge']],
            $rows, [], []
        );
    }
}
