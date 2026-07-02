<?php

namespace App\Domain\Pinjaman;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Calculation\CalculatorFactory;
use App\Domain\Numbering\NumberingService;
use App\Models\Coa;
use App\Models\Pinjaman;
use App\Models\PinjamanApproval;
use App\Models\PinjamanJadwal;
use App\Models\PinjamanPembayaran;
use App\Models\PinjamanRestrukturisasi;
use App\Models\ProdukPinjaman;
use App\Support\Tenant\CurrentTenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PinjamanService
{
    public const APPROVAL_LEVELS = [
        1 => ['jabatan' => 'AO', 'role' => 'ao'],
        2 => ['jabatan' => 'Manajer', 'role' => 'manajer'],
        3 => ['jabatan' => 'Ketua/Admin', 'role' => 'admin'],
    ];

    public const DENDA_PERSEN_PER_HARI = 0.5; // 0.5% per hari dari total angsuran

    public static function cekKemampuanBayar(int $anggotaId, int $cicilanBaru): array
    {
        $anggota = \App\Models\Anggota::findOrFail($anggotaId);
        $totalCicilan  = $anggota->totalCicilanPerBulan();
        $maksCicilan   = $anggota->maxCicilanBulanan();
        $totalSetelah  = $totalCicilan + $cicilanBaru;
        $rasioSetelah  = $maksCicilan > 0 ? round(($totalSetelah / $anggota->penghasilan_bulanan) * 100, 2) : 100;

        return [
            'anggota_id'            => $anggotaId,
            'penghasilan_bulanan'   => $anggota->penghasilan_bulanan,
            'total_hutang'          => $anggota->totalHutang(),
            'cicilan_saat_ini'      => $totalCicilan,
            'cicilan_baru'          => $cicilanBaru,
            'total_cicilan_setelah' => $totalSetelah,
            'maks_cicilan_40pct'    => $maksCicilan,
            'rasio_saat_ini'        => $anggota->rasioHutang(),
            'rasio_setelah'         => $rasioSetelah,
            'layak'                 => $totalSetelah <= $maksCicilan || $maksCicilan === 0,
        ];
    }

    public static function ajukan(array $data): Pinjaman
    {
        $produk = ProdukPinjaman::findOrFail($data['produk_id']);

        if ($data['plafon'] < $produk->plafon_minimum || $data['plafon'] > $produk->plafon_maksimum) {
            throw new InvalidArgumentException('Plafon di luar batas produk.');
        }
        if ($data['tenor'] < $produk->tenor_minimum || $data['tenor'] > $produk->tenor_maksimum) {
            throw new InvalidArgumentException('Tenor di luar batas produk.');
        }

        $rate     = $produk->isSyariah() ? (float) $produk->margin_persen : (float) $produk->bunga_persen;
        $calc     = CalculatorFactory::for($produk->metode_perhitungan);
        $hasil    = $calc->generate((int) $data['plafon'], $rate, (int) $data['tenor'], $data['opts'] ?? []);

        $biayaAdmin    = (int) ($produk->biaya_admin_flat + $data['plafon'] * $produk->biaya_admin_persen / 100);
        $biayaProvisi  = (int) ($data['plafon'] * $produk->biaya_provisi_persen / 100);
        $biayaAsuransi = (int) ($data['plafon'] * $produk->biaya_asuransi_persen / 100);
        $biayaMaterai  = (int) $produk->biaya_materai;
        $totalBiaya    = $biayaAdmin + $biayaProvisi + $biayaAsuransi + $biayaMaterai;
        $cair          = (int) $data['plafon'] - $totalBiaya;

        return DB::transaction(function () use ($data, $produk, $rate, $hasil, $biayaAdmin, $biayaProvisi, $biayaAsuransi, $biayaMaterai, $totalBiaya, $cair) {
            $pinjaman = Pinjaman::create([
                'tenant_id'         => CurrentTenant::id(),
                'cabang_id'         => $data['cabang_id'] ?? auth()->user()?->cabang_id,
                'anggota_id'        => $data['anggota_id'],
                'produk_id'         => $produk->id,
                'nomor_akad'        => NumberingService::next('pinjaman', 'PJM-', '{prefix}{ymd}-{seq:5}'),
                'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
                'plafon'            => $data['plafon'],
                'pokok'             => $data['plafon'],
                'margin_total'      => $hasil['total_margin'],
                'total_bayar'       => $hasil['total_bayar'],
                'tenor'             => $data['tenor'],
                'bunga_persen'      => $produk->isSyariah() ? 0 : $rate,
                'margin_persen'     => $produk->isSyariah() ? $rate : 0,
                'nisbah_anggota'    => $produk->nisbah_anggota,
                'nisbah_koperasi'   => $produk->nisbah_koperasi,
                'biaya_admin'       => $biayaAdmin,
                'biaya_provisi'     => $biayaProvisi,
                'biaya_asuransi'    => $biayaAsuransi,
                'biaya_materai'     => $biayaMaterai,
                'total_biaya'       => $totalBiaya,
                'pencairan_bersih'  => $cair,
                'saldo_pokok'       => $data['plafon'],
                'saldo_margin'      => $hasil['total_margin'],
                'tujuan'            => $data['tujuan'] ?? null,
                'status'            => 'pengajuan',
                'kolektabilitas'    => 'lancar',
                'ao_id'             => $data['ao_id'] ?? auth()->id(),
            ]);

            self::generateApprovalLevels($pinjaman);

            return $pinjaman;
        });
    }

    // ─── Multi-Level Approval ───────────────────────────────────────

    public static function generateApprovalLevels(Pinjaman $pinjaman): void
    {
        foreach (self::APPROVAL_LEVELS as $level => $config) {
            PinjamanApproval::create([
                'tenant_id'   => $pinjaman->tenant_id,
                'pinjaman_id' => $pinjaman->id,
                'level'       => $level,
                'jabatan'     => $config['jabatan'],
                'keputusan'   => 'pending',
            ]);
        }
    }

    public static function approve(Pinjaman $pinjaman, int $userId): Pinjaman
    {
        $user = \App\Models\User::findOrFail($userId);
        $userRole = $user->roles->first()?->name;

        $nextPending = $pinjaman->approval()
            ->where('keputusan', 'pending')
            ->orderBy('level')
            ->first();

        if (! $nextPending) {
            throw new InvalidArgumentException('Semua level sudah memberikan keputusan.');
        }

        $nextPending->update([
            'user_id'    => $userId,
            'keputusan'  => 'setuju',
            'decided_at' => now(),
        ]);

        $allApproved = $pinjaman->approval()
            ->where('keputusan', '!=', 'setuju')
            ->doesntExist();

        if ($allApproved) {
            $pinjaman->update([
                'status'      => 'aktif',
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);
        }

        return $pinjaman->refresh();
    }

    public static function tolak(Pinjaman $pinjaman, int $userId, string $alasan): Pinjaman
    {
        DB::transaction(function () use ($pinjaman, $userId, $alasan) {
            $pinjaman->approval()->where('keputusan', 'pending')->update([
                'user_id'    => $userId,
                'keputusan'  => 'tolak',
                'catatan'    => $alasan,
                'decided_at' => now(),
            ]);

            $pinjaman->update([
                'status'       => 'ditolak',
                'rejected_by'  => $userId,
                'rejected_at'  => now(),
                'alasan_tolak' => $alasan,
            ]);
        });

        return $pinjaman->refresh();
    }

    // ─── Jadwal ─────────────────────────────────────────────────────

    public static function generateJadwal(Pinjaman $pinjaman, ?Carbon $tanggalMulai = null): void
    {
        $produk = $pinjaman->produk;
        $rate   = $produk->isSyariah() ? (float) $produk->margin_persen : (float) $produk->bunga_persen;
        $calc   = CalculatorFactory::for($produk->metode_perhitungan);
        $hasil  = $calc->generate($pinjaman->pokok, $rate, $pinjaman->tenor);

        $tanggalMulai ??= Carbon::parse($pinjaman->tanggal_pencairan ?? now())->startOfDay();
        $pinjaman->jadwal()->delete();

        foreach ($hasil['schedule'] as $row) {
            PinjamanJadwal::create([
                'tenant_id'           => $pinjaman->tenant_id,
                'pinjaman_id'         => $pinjaman->id,
                'angsuran_ke'         => $row['angsuran_ke'],
                'tanggal_jatuh_tempo' => $tanggalMulai->copy()->addMonths($row['angsuran_ke']),
                'pokok'               => $row['pokok'],
                'margin'              => $row['margin'],
                'total_angsuran'      => $row['total'],
                'saldo_pokok'         => $row['saldo_pokok'],
                'status'              => 'belum_jatuh_tempo',
            ]);
        }
    }

    // ─── Denda Keterlambatan ────────────────────────────────────────

    public static function hitungDendaHarian(Pinjaman $pinjaman): void
    {
        $hariIni = now()->startOfDay();
        $dendaPersen = (float) ($pinjaman->produk->denda_persen_per_hari ?? self::DENDA_PERSEN_PER_HARI);

        $jadwalTelat = $pinjaman->jadwal()
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
            ->where('tanggal_jatuh_tempo', '<', $hariIni)
            ->where(function ($q) {
                $q->where('terbayar_pokok', '<', \DB::raw('pokok'))
                  ->orWhere('terbayar_margin', '<', \DB::raw('margin'));
            })
            ->get();

        foreach ($jadwalTelat as $j) {
            if ($j->status === 'belum_jatuh_tempo') {
                $j->status = 'jatuh_tempo';
            }

            $telatHari = (int) $j->tanggal_jatuh_tempo->diffInDays($hariIni);
            if ($telatHari < 1) continue;

            $dendaBaru = (int) round($j->total_angsuran * ($dendaPersen / 100) * $telatHari);
            $j->denda = max($j->denda, $dendaBaru);

            if ($j->status !== 'telat' && $telatHari > 1) {
                $j->status = 'telat';
            }

            $j->save();
        }

        // Update kolektabilitas
        $maxTelat = $pinjaman->jadwal()
            ->where('status', 'telat')
            ->max(\DB::raw('DATEDIFF(NOW(), tanggal_jatuh_tempo)'));

        $kolektabilitas = 'lancar';
        if ($maxTelat >= 1  && $maxTelat <= 30)  $kolektabilitas = 'dpk';
        if ($maxTelat >= 31 && $maxTelat <= 60)  $kolektabilitas = 'kurang_lancar';
        if ($maxTelat >= 61 && $maxTelat <= 90)  $kolektabilitas = 'diragukan';
        if ($maxTelat >= 91)                       $kolektabilitas = 'macet';

        if ($maxTelat !== null) {
            $pinjaman->update([
                'kolektabilitas'   => $kolektabilitas,
                'tunggakan_hari'   => $maxTelat,
                'tunggakan_pokok'  => $jadwalTelat->sum(fn ($j) => max(0, $j->pokok - $j->terbayar_pokok)),
                'tunggakan_margin' => $jadwalTelat->sum(fn ($j) => max(0, $j->margin - $j->terbayar_margin)),
                'tunggakan_denda'  => $jadwalTelat->sum(fn ($j) => max(0, $j->denda - $j->terbayar_denda)),
            ]);

            if ($kolektabilitas === 'macet') {
                $pinjaman->update(['status' => 'macet']);
            }
        }
    }

    // ─── Pencairan ──────────────────────────────────────────────────

    public static function cairkan(Pinjaman $pinjaman, int $kasId, ?Carbon $tanggal = null, ?string $buktiPencairan = null): Pinjaman
    {
        $tanggal ??= now();

        return DB::transaction(function () use ($pinjaman, $kasId, $tanggal, $buktiPencairan) {
            $pinjaman->update([
                'tanggal_pencairan'   => $tanggal,
                'tanggal_jatuh_tempo' => $tanggal->copy()->addMonths($pinjaman->tenor),
                'status'              => 'aktif',
                'bukti_pencairan'     => $buktiPencairan,
            ]);

            self::generateJadwal($pinjaman, $tanggal);

            $coaPokok    = self::coa('1.1.3.01');
            $coaKas      = self::coaKasFromKas($kasId);
            $coaAdmin    = self::coa('4.2.1.01');
            $coaProvisi  = self::coa('4.2.1.02');
            $coaAsuransi = self::coa('2.1.1.01');

            $details = [
                ['coa_id' => $coaPokok->id, 'debit' => $pinjaman->pokok, 'kredit' => 0, 'keterangan' => "Pencairan {$pinjaman->nomor_akad}"],
                ['coa_id' => $coaKas->id,   'debit' => 0, 'kredit' => $pinjaman->pencairan_bersih, 'keterangan' => "Pencairan ke anggota"],
            ];

            if ($pinjaman->biaya_admin > 0)    $details[] = ['coa_id' => $coaAdmin->id,    'debit' => 0, 'kredit' => $pinjaman->biaya_admin, 'keterangan' => 'Biaya admin'];
            if ($pinjaman->biaya_provisi > 0)  $details[] = ['coa_id' => $coaProvisi->id,  'debit' => 0, 'kredit' => $pinjaman->biaya_provisi, 'keterangan' => 'Provisi'];
            if ($pinjaman->biaya_asuransi > 0) $details[] = ['coa_id' => $coaAsuransi->id, 'debit' => 0, 'kredit' => $pinjaman->biaya_asuransi, 'keterangan' => 'Premi asuransi'];

            JurnalService::create(
                "Pencairan pinjaman {$pinjaman->nomor_akad}",
                $details,
                ['referensi_type' => Pinjaman::class, 'referensi_id' => $pinjaman->id, 'tanggal' => $tanggal->toDateString()]
            );

            return $pinjaman->refresh();
        });
    }

    // ─── Pembayaran ─────────────────────────────────────────────────

    public static function bayar(Pinjaman $pinjaman, int $jumlah, int $kasId, ?Carbon $tanggal = null, string $metode = 'cash', ?string $buktiBayar = null): PinjamanPembayaran
    {
        $tanggal ??= now();

        return DB::transaction(function () use ($pinjaman, $jumlah, $kasId, $tanggal, $metode, $buktiBayar) {
            $sisa = $jumlah;
            $alokasiPokok = 0;
            $alokasiMargin = 0;
            $alokasiDenda = 0;

            $jadwalBelumLunas = $pinjaman->jadwal()
                ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
                ->orderBy('angsuran_ke')
                ->get();

            foreach ($jadwalBelumLunas as $j) {
                if ($sisa <= 0) break;

                $sisaDenda = $j->denda - $j->terbayar_denda;
                if ($sisaDenda > 0 && $sisa > 0) {
                    $bayarDenda = min($sisa, $sisaDenda);
                    $alokasiDenda += $bayarDenda;
                    $sisa -= $bayarDenda;
                }

                $sisaMargin = $j->margin - $j->terbayar_margin;
                if ($sisaMargin > 0 && $sisa > 0) {
                    $bayarMargin = min($sisa, $sisaMargin);
                    $alokasiMargin += $bayarMargin;
                    $sisa -= $bayarMargin;
                }

                $sisaPokok = $j->pokok - $j->terbayar_pokok;
                if ($sisaPokok > 0 && $sisa > 0) {
                    $bayarPokok = min($sisa, $sisaPokok);
                    $alokasiPokok += $bayarPokok;
                    $sisa -= $bayarPokok;
                }
            }

            $titipan = $sisa;

            $pembayaran = PinjamanPembayaran::create([
                'tenant_id'       => $pinjaman->tenant_id,
                'pinjaman_id'     => $pinjaman->id,
                'nomor'           => NumberingService::next('pinjaman_bayar', 'PB-', '{prefix}{ymd}-{seq:5}'),
                'tanggal'         => $tanggal,
                'jenis'           => 'angsuran',
                'total_bayar'     => $jumlah,
                'alokasi_pokok'   => $alokasiPokok,
                'alokasi_margin'  => $alokasiMargin,
                'alokasi_denda'   => $alokasiDenda,
                'alokasi_titipan' => $titipan,
                'kas_id'          => $kasId,
                'metode_bayar'    => $metode,
                'status'          => 'pending',
                'bukti_bayar'     => $buktiBayar,
                'user_id'         => auth()->id(),
            ]);

            return $pembayaran;
        });
    }

    public static function verifikasiPembayaran(PinjamanPembayaran $pembayaran, bool $disetujui, ?string $catatan = null): PinjamanPembayaran
    {
        if ($pembayaran->status !== 'pending') {
            throw new InvalidArgumentException('Pembayaran sudah diverifikasi sebelumnya.');
        }

        return DB::transaction(function () use ($pembayaran, $disetujui, $catatan) {
            $pembayaran->update([
                'verified_by'        => auth()->id(),
                'verified_at'        => now(),
                'catatan_verifikasi' => $catatan,
                'status'             => $disetujui ? 'disetujui' : 'ditolak',
            ]);

            if (! $disetujui) {
                return $pembayaran;
            }

            $pinjaman = $pembayaran->pinjaman;
            $tanggal  = $pembayaran->tanggal;

            $sisa = $pembayaran->total_bayar;
            $jadwalBelumLunas = $pinjaman->jadwal()
                ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
                ->orderBy('angsuran_ke')
                ->get();

            foreach ($jadwalBelumLunas as $j) {
                if ($sisa <= 0) break;

                $sisaDenda = $j->denda - $j->terbayar_denda;
                if ($sisaDenda > 0 && $sisa > 0) {
                    $bayarDenda = min($sisa, $sisaDenda);
                    $j->terbayar_denda += $bayarDenda;
                    $sisa -= $bayarDenda;
                }

                $sisaMargin = $j->margin - $j->terbayar_margin;
                if ($sisaMargin > 0 && $sisa > 0) {
                    $bayarMargin = min($sisa, $sisaMargin);
                    $j->terbayar_margin += $bayarMargin;
                    $sisa -= $bayarMargin;
                }

                $sisaPokok = $j->pokok - $j->terbayar_pokok;
                if ($sisaPokok > 0 && $sisa > 0) {
                    $bayarPokok = min($sisa, $sisaPokok);
                    $j->terbayar_pokok += $bayarPokok;
                    $sisa -= $bayarPokok;
                }

                if ($j->terbayar_pokok >= $j->pokok && $j->terbayar_margin >= $j->margin) {
                    $j->status = 'lunas';
                    $j->tanggal_bayar = $tanggal;
                }
                $j->save();
            }

            $pinjaman->saldo_pokok  = max(0, $pinjaman->saldo_pokok - $pembayaran->alokasi_pokok);
            $pinjaman->saldo_margin = max(0, $pinjaman->saldo_margin - $pembayaran->alokasi_margin);
            if ($pinjaman->saldo_pokok === 0 && $pinjaman->saldo_margin === 0) {
                $pinjaman->status = 'lunas';
            }
            $pinjaman->save();

            $coaKas         = self::coaKasFromKas($pembayaran->kas_id);
            $coaPokok       = self::coa('1.1.3.01');
            $coaPendBunga   = $pinjaman->produk->isSyariah() ? self::coa('4.1.1.02') : self::coa('4.1.1.01');
            $coaPendDenda   = self::coa('4.2.1.03');

            $details = [
                ['coa_id' => $coaKas->id, 'debit' => $pembayaran->total_bayar, 'kredit' => 0, 'keterangan' => "Penerimaan angsuran {$pinjaman->nomor_akad}"],
            ];
            if ($pembayaran->alokasi_pokok > 0)  $details[] = ['coa_id' => $coaPokok->id,     'debit' => 0, 'kredit' => $pembayaran->alokasi_pokok,  'keterangan' => 'Pelunasan pokok'];
            if ($pembayaran->alokasi_margin > 0) $details[] = ['coa_id' => $coaPendBunga->id, 'debit' => 0, 'kredit' => $pembayaran->alokasi_margin, 'keterangan' => 'Pendapatan ' . ($pinjaman->produk->isSyariah() ? 'margin' : 'bunga')];
            if ($pembayaran->alokasi_denda > 0)  $details[] = ['coa_id' => $coaPendDenda->id, 'debit' => 0, 'kredit' => $pembayaran->alokasi_denda,  'keterangan' => 'Pendapatan denda'];
            if ($pembayaran->alokasi_titipan > 0) {
                $coaTitipan = self::coa('2.1.2.01');
                $details[] = ['coa_id' => $coaTitipan->id, 'debit' => 0, 'kredit' => $pembayaran->alokasi_titipan, 'keterangan' => 'Titipan kelebihan'];
            }

            $jurnal = JurnalService::create(
                "Pembayaran angsuran {$pinjaman->nomor_akad}",
                $details,
                ['referensi_type' => Pinjaman::class, 'referensi_id' => $pinjaman->id, 'tanggal' => $tanggal->toDateString()]
            );

            $pembayaran->update(['jurnal_id' => $jurnal->id]);

            return $pembayaran;
        });
    }

    // ─── Auto-Debet Simpanan ────────────────────────────────────────

    public static function autoDebetAngsuran(Pinjaman $pinjaman): ?PinjamanPembayaran
    {
        $jadwalJatuhTempo = $pinjaman->jadwal()
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
            ->where('tanggal_jatuh_tempo', '<=', now()->toDateString())
            ->orderBy('angsuran_ke')
            ->first();

        if (! $jadwalJatuhTempo) return null;

        $sisaPokok  = max(0, $jadwalJatuhTempo->pokok - $jadwalJatuhTempo->terbayar_pokok);
        $sisaMargin = max(0, $jadwalJatuhTempo->margin - $jadwalJatuhTempo->terbayar_margin);
        $sisaDenda  = max(0, $jadwalJatuhTempo->denda - $jadwalJatuhTempo->terbayar_denda);
        $totalTagihan = $sisaPokok + $sisaMargin + $sisaDenda;

        $simpananSukarela = $pinjaman->anggota->simpanan()
            ->where('status', 'aktif')
            ->whereHas('produk', fn ($q) => $q->whereIn('akad_type', ['konvensional', 'wadiah', 'mudharabah']))
            ->first();

        if (! $simpananSukarela || $simpananSukarela->saldo < $totalTagihan) return null;

        $kasDefault = \App\Models\Kas::where('aktif', true)->first();
        if (! $kasDefault) return null;

        return DB::transaction(function () use ($pinjaman, $simpananSukarela, $totalTagihan, $kasDefault) {
            \App\Domain\Simpanan\SimpananService::tarik($simpananSukarela, $totalTagihan, $kasDefault->id, now(), 'auto_debet', 'Auto-debet angsuran ' . $pinjaman->nomor_akad);

            // Bayar angsuran (auto-verifikasi)
            $pembayaran = self::bayar($pinjaman, $totalTagihan, $kasDefault->id, now(), 'auto_debet', null);
            return self::verifikasiPembayaran($pembayaran, true, 'Auto-debet dari simpanan');
        });
    }

    // ─── Restrukturisasi ────────────────────────────────────────────

    public static function restrukturisasi(Pinjaman $pinjaman, string $jenis, array $dataBaru, string $alasan): Pinjaman
    {
        $snapshot = [
            'plafon'           => $pinjaman->plafon,
            'pokok'            => $pinjaman->pokok,
            'saldo_pokok'      => $pinjaman->saldo_pokok,
            'saldo_margin'     => $pinjaman->saldo_margin,
            'margin_total'     => $pinjaman->margin_total,
            'total_bayar'      => $pinjaman->total_bayar,
            'tenor'            => $pinjaman->tenor,
            'bunga_persen'     => $pinjaman->bunga_persen,
            'margin_persen'    => $pinjaman->margin_persen,
            'tanggal_jatuh_tempo' => $pinjaman->tanggal_jatuh_tempo,
        ];

        return DB::transaction(function () use ($pinjaman, $jenis, $dataBaru, $alasan, $snapshot) {
            match ($jenis) {
                'perpanjangan' => self::restrukturisasiPerpanjangan($pinjaman, $dataBaru),
                'reschedule'   => self::restrukturisasiReschedule($pinjaman, $dataBaru),
                'reconditioning' => self::restrukturisasiReconditioning($pinjaman, $dataBaru),
                default => throw new InvalidArgumentException("Jenis restrukturisasi '{$jenis}' tidak dikenal."),
            };

            PinjamanRestrukturisasi::create([
                'tenant_id'   => $pinjaman->tenant_id,
                'pinjaman_id' => $pinjaman->id,
                'tanggal'     => now()->toDateString(),
                'jenis'       => $jenis,
                'sebelum'     => $snapshot,
                'sesudah'     => [
                    'saldo_pokok'      => $pinjaman->saldo_pokok,
                    'saldo_margin'     => $pinjaman->saldo_margin,
                    'tenor'            => $pinjaman->tenor,
                    'tanggal_jatuh_tempo' => $pinjaman->tanggal_jatuh_tempo,
                ],
                'alasan'      => $alasan,
                'user_id'     => auth()->id(),
            ]);

            if ($pinjaman->status === 'macet') {
                $pinjaman->update(['status' => 'aktif', 'kolektabilitas' => 'lancar']);
            }

            return $pinjaman->refresh();
        });
    }

    private static function restrukturisasiPerpanjangan(Pinjaman $pinjaman, array $data): void
    {
        $tambahanTenor = (int) ($data['tambahan_tenor'] ?? 0);
        if ($tambahanTenor <= 0) throw new InvalidArgumentException('Tambahan tenor harus > 0.');

        $pinjaman->update([
            'tenor'               => $pinjaman->tenor + $tambahanTenor,
            'tanggal_jatuh_tempo' => Carbon::parse($pinjaman->tanggal_jatuh_tempo)->addMonths($tambahanTenor),
        ]);

        // Regenerate jadwal untuk sisa
        $produk = $pinjaman->produk;
        $rate   = $produk->isSyariah() ? (float) $produk->margin_persen : (float) $produk->bunga_persen;
        $calc   = CalculatorFactory::for($produk->metode_perhitungan);
        $hasil  = $calc->generate($pinjaman->saldo_pokok, $rate, $tambahanTenor);

        $lastAngsuran = $pinjaman->jadwal()->max('angsuran_ke') ?? 0;
        $tanggalMulai = Carbon::parse($pinjaman->tanggal_jatuh_tempo)->subMonths($tambahanTenor);

        // Hapus jadwal yang belum lunas
        $pinjaman->jadwal()->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])->delete();

        foreach ($hasil['schedule'] as $row) {
            $lastAngsuran++;
            PinjamanJadwal::create([
                'tenant_id'           => $pinjaman->tenant_id,
                'pinjaman_id'         => $pinjaman->id,
                'angsuran_ke'         => $lastAngsuran,
                'tanggal_jatuh_tempo' => $tanggalMulai->copy()->addMonths($lastAngsuran),
                'pokok'               => $row['pokok'],
                'margin'              => $row['margin'],
                'total_angsuran'      => $row['total'],
                'saldo_pokok'         => $row['saldo_pokok'],
                'status'              => 'belum_jatuh_tempo',
            ]);
        }
    }

    private static function restrukturisasiReschedule(Pinjaman $pinjaman, array $data): void
    {
        $tanggalMulaiBaru = Carbon::parse($data['tanggal_mulai_baru'] ?? now());

        $jadwalBelumLunas = $pinjaman->jadwal()
            ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
            ->orderBy('angsuran_ke')
            ->get();

        $bulanOffset = 0;
        foreach ($jadwalBelumLunas as $j) {
            $bulanOffset++;
            $j->update([
                'tanggal_jatuh_tempo' => $tanggalMulaiBaru->copy()->addMonths($bulanOffset),
                'status'              => 'belum_jatuh_tempo',
                'denda'               => 0,
                'terbayar_denda'      => 0,
            ]);
        }
    }

    private static function restrukturisasiReconditioning(Pinjaman $pinjaman, array $data): void
    {
        if (isset($data['bunga_persen'])) {
            $pinjaman->update(['bunga_persen' => (float) $data['bunga_persen']]);
        }
        if (isset($data['margin_persen'])) {
            $pinjaman->update(['margin_persen' => (float) $data['margin_persen']]);
        }
        if (isset($data['hapus_denda']) && $data['hapus_denda']) {
            $pinjaman->jadwal()->update(['denda' => 0, 'terbayar_denda' => 0]);
        }
        if (isset($data['diskon_pokok'])) {
            $pinjaman->saldo_pokok = max(0, $pinjaman->saldo_pokok - (int) $data['diskon_pokok']);
            $pinjaman->save();
        }
    }

    // ─── Helpers ────────────────────────────────────────────────────

    private static function coa(string $kode): Coa
    {
        $coa = Coa::where('kode', $kode)->first();
        if (! $coa) {
            throw new InvalidArgumentException("COA dengan kode {$kode} tidak ditemukan. Jalankan ChartOfAccountsSeeder.");
        }
        return $coa;
    }

    private static function coaKasFromKas(int $kasId): Coa
    {
        $kas = \App\Models\Kas::findOrFail($kasId);
        return $kas->coa;
    }
}
