<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpobProduk extends Model
{
    protected $table = 'ppob_produk';
    protected $guarded = ['id'];

    protected $casts = ['aktif' => 'boolean'];
}
