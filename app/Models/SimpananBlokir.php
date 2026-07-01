<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SimpananBlokir extends Model
{
    use BelongsToTenant;

    protected $table = 'simpanan_blokir';

    protected $fillable = [
        'tenant_id', 'simpanan_id', 'jumlah', 'alasan',
        'referensi_type', 'referensi_id', 'tanggal_blokir', 'tanggal_lepas', 'aktif',
    ];

    protected $casts = [
        'jumlah'         => 'integer',
        'tanggal_blokir' => 'date',
        'tanggal_lepas'  => 'date',
        'aktif'          => 'boolean',
    ];

    public function simpanan() { return $this->belongsTo(Simpanan::class); }
}
