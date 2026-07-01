<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokoSupplier extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'toko_supplier';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'telp', 'email',
        'alamat', 'npwp', 'saldo_hutang', 'aktif',
    ];

    protected $casts = ['saldo_hutang' => 'integer', 'aktif' => 'boolean'];
}
