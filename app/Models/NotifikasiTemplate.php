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
}
