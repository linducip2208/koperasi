<?php

namespace App\Services\Ocr;

/** Kontrak OCR — provider dikonfigurasi eksternal, tidak di-hardcode di caller. */
interface OcrProviderInterface
{
    public function name(): string;

    /**
     * Ekstrak field dari file dokumen. Return ['field' => value, ...] + ['_raw' => ...].
     * Gagal → exception; caller memutuskan fallback manual.
     */
    public function extract(string $filePath, string $jenis): array;
}
