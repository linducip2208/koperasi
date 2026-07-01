<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TokoPenjualanDetail extends Model
{
    use BelongsToTenant;

    protected $table = 'toko_penjualan_detail';

    protected $fillable = [
        'tenant_id', 'penjualan_id', 'barang_id', 'jumlah',
        'harga_satuan', 'diskon', 'subtotal', 'hpp',
    ];

    protected $casts = [
        'jumlah' => 'integer', 'harga_satuan' => 'integer',
        'diskon' => 'integer', 'subtotal' => 'integer', 'hpp' => 'integer',
    ];

    public function penjualan()
    {
        return $this->belongsTo(TokoPenjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(TokoBarang::class);
    }
}
