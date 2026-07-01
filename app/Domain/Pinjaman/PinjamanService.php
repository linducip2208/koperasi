<?php

namespace App\Domain\Pinjaman;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Calculation\CalculatorFactory;
use App\Domain\Numbering\NumberingService;
use App\Models\Coa;
use App\Models\Pinjaman;
use App\Models\PinjamanJadwal;
use App\Models\PinjamanPembayaran;
use App\Models\ProdukPinjaman;
use App\Support\Tenant\CurrentTenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PinjamanService
{
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

            return $pinjaman;
        });
    }

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

    public static function approve(Pinjaman $pinjaman, int $userId): Pinjaman
    {
        $pinjaman->update([
            'status'      => 'aktif',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
        return $pinjaman;
    }

    public static function tolak(Pinjaman $pinjaman, int $userId, string $alasan): Pinjaman
    {
        $pinjaman->update([
            'status'       => 'ditolak',
            'rejected_by'  => $userId,
            'rejected_at'  => now(),
            'alasan_tolak' => $alasan,
        ]);
        return $pinjaman;
    }

    /**
     * Cair: pencairan dana dari kas + buat jurnal otomatis.
     */
    public static function cairkan(Pinjaman $pinjaman, int $kasId, ?Carbon $tanggal = null): Pinjaman
    {
        $tanggal ??= now();

        return DB::transaction(function () use ($pinjaman, $kasId, $tanggal) {
            $pinjaman->update([
                'tanggal_pencairan'   => $tanggal,
                'tanggal_jatuh_tempo' => $tanggal->copy()->addMonths($pinjaman->tenor),
                'status'              => 'aktif',
            ]);

            self::generateJadwal($pinjaman, $tanggal);

            // Auto-jurnal pencairan
            $coaPokok    = self::coa('1.1.3.01'); // Piutang Pinjaman Anggota
            $coaKas      = self::coaKasFromKas($kasId);
            $coaAdmin    = self::coa('4.2.1.01'); // Pendapatan Biaya Admin
            $coaProvisi  = self::coa('4.2.1.02'); // Pendapatan Provisi
            $coaAsuransi = self::coa('2.1.1.01'); // Hutang Premi Asuransi

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

    /**
     * Bayar angsuran. Alokasi: denda → margin → pokok → titipan.
     */
    public static function bayar(Pinjaman $pinjaman, int $jumlah, int $kasId, ?Carbon $tanggal = null, string $metode = 'cash'): PinjamanPembayaran
    {
        $tanggal ??= now();

        return DB::transaction(function () use ($pinjaman, $jumlah, $kasId, $tanggal, $metode) {
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

                // 1. Denda
                $sisaDenda = $j->denda - $j->terbayar_denda;
                if ($sisaDenda > 0 && $sisa > 0) {
                    $bayarDenda = min($sisa, $sisaDenda);
                    $j->terbayar_denda += $bayarDenda;
                    $alokasiDenda += $bayarDenda;
                    $sisa -= $bayarDenda;
                }

                // 2. Margin
                $sisaMargin = $j->margin - $j->terbayar_margin;
                if ($sisaMargin > 0 && $sisa > 0) {
                    $bayarMargin = min($sisa, $sisaMargin);
                    $j->terbayar_margin += $bayarMargin;
                    $alokasiMargin += $bayarMargin;
                    $sisa -= $bayarMargin;
                }

                // 3. Pokok
                $sisaPokok = $j->pokok - $j->terbayar_pokok;
                if ($sisaPokok > 0 && $sisa > 0) {
                    $bayarPokok = min($sisa, $sisaPokok);
                    $j->terbayar_pokok += $bayarPokok;
                    $alokasiPokok += $bayarPokok;
                    $sisa -= $bayarPokok;
                }

                // status jadwal
                if ($j->terbayar_pokok >= $j->pokok && $j->terbayar_margin >= $j->margin) {
                    $j->status = 'lunas';
                    $j->tanggal_bayar = $tanggal;
                }
                $j->save();
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
                'user_id'         => auth()->id(),
            ]);

            // Update saldo pinjaman
            $pinjaman->saldo_pokok  = max(0, $pinjaman->saldo_pokok - $alokasiPokok);
            $pinjaman->saldo_margin = max(0, $pinjaman->saldo_margin - $alokasiMargin);
            if ($pinjaman->saldo_pokok === 0 && $pinjaman->saldo_margin === 0) {
                $pinjaman->status = 'lunas';
            }
            $pinjaman->save();

            // Auto-jurnal pembayaran
            $coaKas         = self::coaKasFromKas($kasId);
            $coaPokok       = self::coa('1.1.3.01');
            $coaPendBunga   = $pinjaman->produk->isSyariah() ? self::coa('4.1.1.02') : self::coa('4.1.1.01');
            $coaPendDenda   = self::coa('4.2.1.03');

            $details = [
                ['coa_id' => $coaKas->id, 'debit' => $jumlah, 'kredit' => 0, 'keterangan' => "Penerimaan angsuran {$pinjaman->nomor_akad}"],
            ];
            if ($alokasiPokok > 0)  $details[] = ['coa_id' => $coaPokok->id,     'debit' => 0, 'kredit' => $alokasiPokok,  'keterangan' => 'Pelunasan pokok'];
            if ($alokasiMargin > 0) $details[] = ['coa_id' => $coaPendBunga->id, 'debit' => 0, 'kredit' => $alokasiMargin, 'keterangan' => 'Pendapatan ' . ($pinjaman->produk->isSyariah() ? 'margin' : 'bunga')];
            if ($alokasiDenda > 0)  $details[] = ['coa_id' => $coaPendDenda->id, 'debit' => 0, 'kredit' => $alokasiDenda,  'keterangan' => 'Pendapatan denda'];
            if ($titipan > 0) {
                $coaTitipan = self::coa('2.1.2.01');
                $details[] = ['coa_id' => $coaTitipan->id, 'debit' => 0, 'kredit' => $titipan, 'keterangan' => 'Titipan kelebihan'];
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
