<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NotifikasiTemplate extends Model
{
    use BelongsToTenant;

    protected $table = 'notifikasi_template';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'event', 'channel',
        'subject', 'body', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];

    protected static function booted(): void
    {
        // Body template dirender {!! !!} di email — sanitasi saat simpan.
        static::saving(fn ($m) => $m->body = \App\Support\HtmlSanitizer::clean($m->body));
    }
}
