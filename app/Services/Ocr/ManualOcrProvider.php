<?php

namespace App\Services\Ocr;

/**
 * Provider manual: tanpa OCR otomatis — operator input manual + konfirmasi.
 * Dipakai default agar tidak ada data terverifikasi yang tertimpa otomatis.
 */
class ManualOcrProvider implements OcrProviderInterface
{
    public function name(): string { return 'manual'; }

    public function extract(string $filePath, string $jenis): array
    {
        return ['_raw' => null, '_note' => 'OCR manual: input dan konfirmasi oleh operator.'];
    }
}
