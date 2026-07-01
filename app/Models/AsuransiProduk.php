<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AsuransiProduk extends Model
{
    use BelongsToTenant;

    protected $table = 'asuransi_produk';

    protected $fillable = ['tenant_id', 'nama', 'penanggung', 'jenis', 'rate_premi', 'aktif'];

    protected $casts = ['rate_premi' => 'decimal:4', 'aktif' => 'boolean'];

    public function polis()
    {
        return $this->hasMany(AsuransiPolis::class, 'produk_id');
    }
}
