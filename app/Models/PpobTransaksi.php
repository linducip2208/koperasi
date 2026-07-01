<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpobTransaksi extends Model
{
    protected $table = 'ppob_transaksi';
    protected $guarded = ['id'];

    protected $casts = [
        'harga'      => 'integer',
        'harga_beli' => 'integer',
        'laba'       => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function produk()
    {
        return $this->belongsTo(PpobProduk::class, 'ppob_produk_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
