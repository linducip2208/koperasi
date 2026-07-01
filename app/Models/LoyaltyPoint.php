<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $table = 'loyalty_points';
    protected $guarded = ['id'];

    protected $casts = [
        'poin' => 'integer',
        'total_poin_diterima' => 'integer',
        'total_poin_ditukar' => 'integer',
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
