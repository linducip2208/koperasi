<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ShuDistribusi extends Model
{
    use BelongsToTenant;

    protected $table = 'shu_distribusi';

    protected $fillable = [
        'tenant_id', 'shu_perhitungan_id', 'anggota_id',
        'total_simpanan', 'total_transaksi',
        'jasa_modal', 'jasa_anggota', 'total_shu',
        'metode_distribusi', 'status', 'distributed_at',
    ];

    protected $casts = [
        'total_simpanan'  => 'integer',
        'total_transaksi' => 'integer',
        'jasa_modal'      => 'integer',
        'jasa_anggota'    => 'integer',
        'total_shu'       => 'integer',
        'distributed_at'  => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function shuPerhitungan()
    {
        return $this->belongsTo(ShuPerhitungan::class);
    }
}
