<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatVoting extends Model
{
    protected $table = 'rat_voting';
    protected $guarded = ['id'];

    protected $casts = [
        'opsi'       => 'array',
        'mulai'      => 'datetime',
        'selesai'    => 'datetime',
        'is_aktif'   => 'boolean',
    ];

    public function rat()
    {
        return $this->belongsTo(Rat::class);
    }

    public function suara()
    {
        return $this->hasMany(RatVotingSuara::class, 'voting_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
