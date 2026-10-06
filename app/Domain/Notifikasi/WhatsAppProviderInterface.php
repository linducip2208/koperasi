<?php

namespace App\Domain\Notifikasi;

/** Kontrak provider WhatsApp — tambah driver baru tanpa ubah caller. */
interface WhatsAppProviderInterface
{
    public function name(): string;
    public function send(string $phone, string $message): bool;
}
