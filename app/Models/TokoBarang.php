<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokoBarang extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'toko_barang';

    protected $fillable = [
        'tenant_id', 'sku', 'barcode', 'nama', 'kategori_id', 'satuan_id', 'brand',
        'harga_beli', 'harga_jual_umum', 'harga_jual_anggota', 'harga_jual_grosir',
        'stok', 'stok_minimum', 'stok_maksimum',
        'foto_path', 'metode_hpp', 'is_jasa', 'aktif', 'meta',
    ];

    protected $casts = [
        'harga_beli'         => 'integer',
        'harga_jual_umum'    => 'integer',
        'harga_jual_anggota' => 'integer',
        'harga_jual_grosir'  => 'integer',
        'stok'               => 'integer',
        'stok_minimum'       => 'integer',
        'stok_maksimum'      => 'integer',
        'is_jasa'            => 'boolean',
        'aktif'              => 'boolean',
        'meta'               => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(TokoKategori::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(TokoSatuan::class);
    }
}
