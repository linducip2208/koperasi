<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TokoSatuan extends Model
{
    use BelongsToTenant;

    protected $table = 'toko_satuan';

    protected $fillable = ['tenant_id', 'kode', 'nama'];
}
