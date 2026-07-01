<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UnitProdusenSetoran extends Model
{
    use BelongsToTenant;

    protected $table = 'unit_produsen_setoran';

    protected $fillable = [
        'tenant_id', 'anggota_id', 'komoditi_id', 'tanggal',
        'jumlah', 'harga_satuan', 'total', 'kualitas', 'catatan',
        'terbayar', 'kas_id', 'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'jumlah'       => 'decimal:3',
        'harga_satuan' => 'integer',
        'total'        => 'integer',
        'terbayar'     => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function komoditi()
    {
        return $this->belongsTo(UnitProdusenKomoditi::class, 'komoditi_id');
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
