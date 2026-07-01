<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatVotingSuara extends Model
{
    protected $table = 'rat_voting_suara';
    protected $guarded = ['id'];

    public function voting()
    {
        return $this->belongsTo(RatVoting::class, 'voting_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
