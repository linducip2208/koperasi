<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use BelongsToTenant;

    protected $table = 'tagihan';

    protected $fillable = [
        'tenant_id', 'master_id', 'anggota_id', 'nomor', 'periode', 'jatuh_tempo',
        'nominal', 'terbayar', 'status', 'tanggal_bayar',
        'kas_id', 'simpanan_id', 'jurnal_id',
    ];

    protected $casts = [
        'periode'       => 'date',
        'jatuh_tempo'   => 'date',
        'tanggal_bayar' => 'date',
        'nominal'       => 'integer',
        'terbayar'      => 'integer',
    ];

    public function master()
    {
        return $this->belongsTo(TagihanMaster::class, 'master_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
