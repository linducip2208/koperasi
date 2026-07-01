<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTransaksi extends Model
{
    protected $table = 'loyalty_transaksi';
    protected $guarded = ['id'];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
