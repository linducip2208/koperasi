<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UnitProdusenKomoditi extends Model
{
    use BelongsToTenant;

    protected $table = 'unit_produsen_komoditi';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'jenis', 'satuan',
        'harga_beli_default', 'harga_jual_default', 'aktif',
    ];

    protected $casts = [
        'harga_beli_default' => 'integer',
        'harga_jual_default' => 'integer',
        'aktif' => 'boolean',
    ];

    public function setoran()
    {
        return $this->hasMany(UnitProdusenSetoran::class, 'komoditi_id');
    }
}
