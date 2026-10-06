<?php

namespace App\Services\Ocr;

/**
 * OcrManager: registry + aturan main — hasil OCR TIDAK PERNAH menimpa data
 * anggota terverifikasi tanpa konfirmasi eksplisit operator.
 */
class OcrManager
{
    public static function provider(?string $name = null): OcrProviderInterface
    {
        $name ??= \App\Models\Setting::get('ocr_provider', 'manual', 'integrasi');
        return match ($name) {
            default => new ManualOcrProvider(),
        };
    }

    /**
     * Bandingkan hasil OCR vs data tersimpan → usulan perubahan (bukan apply).
     * @return array{field, lama, usulan}[]
     */
    public static function propose(string $filePath, string $jenis, array $dataTersimpan): array
    {
        $hasil = self::provider()->extract($filePath, $jenis);
        unset($hasil['_raw'], $hasil['_note']);
        $usul = [];
        foreach ($hasil as $field => $nilai) {
            $lama = $dataTersimpan[$field] ?? null;
            if ($nilai !== null && $nilai !== '' && (string) $nilai !== (string) $lama) {
                $usul[] = ['field' => $field, 'lama' => $lama, 'usulan' => $nilai];
            }
        }
        return $usul;
    }
}
