<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokoKategori extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'toko_kategori';

    protected $fillable = ['tenant_id', 'parent_id', 'nama', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(TokoKategori::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TokoKategori::class, 'parent_id');
    }
}
