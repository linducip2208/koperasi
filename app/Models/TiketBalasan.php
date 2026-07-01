<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketBalasan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'lampiran' => 'array',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
