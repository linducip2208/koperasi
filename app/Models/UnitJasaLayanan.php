<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UnitJasaLayanan extends Model
{
    use BelongsToTenant;

    protected $table = 'unit_jasa_layanan';

    protected $fillable = ['tenant_id', 'kode', 'nama', 'tarif', 'satuan_tarif', 'aktif'];

    protected $casts = ['tarif' => 'integer', 'aktif' => 'boolean'];

    public function orders()
    {
        return $this->hasMany(UnitJasaOrder::class, 'layanan_id');
    }
}
