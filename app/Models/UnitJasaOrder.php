<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UnitJasaOrder extends Model
{
    use BelongsToTenant;

    protected $table = 'unit_jasa_order';

    protected $fillable = [
        'tenant_id', 'layanan_id', 'anggota_id', 'nomor', 'tanggal',
        'nama_pelanggan', 'total', 'komisi_anggota', 'status',
        'bayar', 'kas_id', 'jurnal_id',
    ];

    protected $casts = [
        'tanggal'        => 'date',
        'total'          => 'integer',
        'komisi_anggota' => 'integer',
        'bayar'          => 'integer',
    ];

    public function layanan()
    {
        return $this->belongsTo(UnitJasaLayanan::class, 'layanan_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
