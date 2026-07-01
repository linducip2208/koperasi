<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Rat extends Model
{
    use BelongsToTenant;

    protected $table = 'rat';

    protected $fillable = [
        'tenant_id', 'tahun_buku', 'tanggal', 'lokasi',
        'agenda', 'jumlah_anggota_terdaftar', 'jumlah_hadir',
        'quorum_persen', 'quorum_tercapai', 'notulen',
        'keputusan', 'status',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'agenda'          => 'array',
        'keputusan'       => 'array',
        'quorum_tercapai' => 'boolean',
    ];
}
