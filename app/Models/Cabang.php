<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabang extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'cabang';

    protected $fillable = ['tenant_id', 'kode', 'nama', 'alamat', 'telp', 'tipe', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];
}
