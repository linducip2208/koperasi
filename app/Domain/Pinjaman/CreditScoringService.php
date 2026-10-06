<?php

namespace App\Domain\Pinjaman;

use App\Models\Anggota;
use App\Models\Setting;

/**
 * Credit scoring configurable. Bobot default 100 poin, bisa diubah via
 * settings group=credit_scoring (JSON {"bobot": {...}, "batas": {...}}).
 * Semua ambang configurable — tidak hardcode satu koperasi.
 */
class CreditScoringService
{
    public static function config(): array
    {
        return Setting::get('skor_kredit', [
            'bobot' => [
                'usia_keanggotaan' => 15, 'simpanan' => 15, 'riwayat_pinjaman' => 15,
                'riwayat_bayar' => 20, 'tunggakan' => 15, 'rasio_hutang' => 10,
                'penjamin' => 5, 'agunan' => 5,
            ],
            'batas' => ['baik' => 75, 'cukup' => 50],
            'plafon_kelipatan_simpanan' => 10,
            'tenor_maks_baik' => 36, 'tenor_maks_cukup' => 24, 'tenor_maks_kurang' => 12,
        ], 'kredit') ?? [];
    }

    /**
     * @return array{skor:int, level:string, rekomendasi_plafon:int, rekomendasi_tenor:int, rincian:array}
     */
    public static function skor(Anggota $anggota, ?int $plafonDiminta = null): array
    {
        $cfg = self::config();
        $bobot = $cfg['bobot'];
        $rincian = [];

        // 1. Usia keanggotaan (tahun penuh, maks 5 thn = penuh).
        $tahun = $anggota->tanggal_masuk ? (int) $anggota->tanggal_masuk->diffInYears(now()) : 0;
        $rincian['usia_keanggotaan'] = (int) round(min(1, $tahun / 5) * $bobot['usia_keanggotaan']);

        // 2. Simpanan (vs plafon diminta / 1 jt default).
        $simpanan = $anggota->totalSimpanan();
        $acuan = max(1_000_000, $plafonDiminta ?? 1_000_000);
        $rincian['simpanan'] = (int) round(min(1, $simpanan / ($acuan * 0.2)) * $bobot['simpanan']);

        // 3. Riwayat pinjaman lunas.
        $lunas = $anggota->pinjaman()->where('status', 'lunas')->count();
        $macet = $anggota->pinjaman()->where('status', 'macet')->count();
        $rincian['riwayat_pinjaman'] = (int) round(min(1, $lunas / 3) * $bobot['riwayat_pinjaman']);

        // 4. Riwayat bayar: % angsuran tepat waktu (pendekatan: jadwal lunas vs telat).
        $totalJadwal = $anggota->pinjaman()->withCount('jadwal')->get()->sum('jadwal_count');
        $telat = $anggota->pinjaman()->withCount(['jadwal as telat_count' => fn ($q) => $q->where('status', 'telat')])->get()->sum('telat_count');
        $rincian['riwayat_bayar'] = $totalJadwal > 0
            ? (int) round(max(0, 1 - $telat / $totalJadwal) * $bobot['riwayat_bayar'])
            : (int) round($bobot['riwayat_bayar'] * 0.5);

        // 5. Tunggakan berjalan (0 = penuh).
        $rincian['tunggakan'] = $anggota->totalHutang() > 0 && $macet > 0 ? 0 : $bobot['tunggakan'];

        // 6. Rasio hutang vs penghasilan (DTI <= 40% penuh).
        $dti = $anggota->rasioHutang();
        $rincian['rasio_hutang'] = $dti <= 0 ? (int) round($bobot['rasio_hutang'] * 0.5)
            : (int) round(max(0, 1 - max(0, $dti - 40) / 60) * $bobot['rasio_hutang']);

        // 7-8. Penjamin & agunan (ada pinjaman dengan jaminan).
        $adaJaminan = $anggota->pinjaman()->whereHas('jaminan')->exists();
        $rincian['penjamin'] = $adaJaminan ? $bobot['penjamin'] : 0;
        $rincian['agunan'] = $adaJaminan ? $bobot['agunan'] : 0;

        $skor = min(100, array_sum($rincian));
        $level = $skor >= $cfg['batas']['baik'] ? 'BAIK' : ($skor >= $cfg['batas']['cukup'] ? 'CUKUP' : 'KURANG');

        return [
            'skor' => $skor,
            'level' => $level,
            'rekomendasi_plafon' => (int) ($simpanan * $cfg['plafon_kelipatan_simpanan']),
            'rekomendasi_tenor' => $level === 'BAIK' ? $cfg['tenor_maks_baik'] : ($level === 'CUKUP' ? $cfg['tenor_maks_cukup'] : $cfg['tenor_maks_kurang']),
            'rincian' => $rincian,
        ];
    }
}
