<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RatVotingSuara extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        // Tanpa opsi_index (kerahasiaan pilihan) — catat partisipasi saja.
        return LogOptions::defaults()
            ->logOnly(['voting_id', 'anggota_id'])
            ->dontSubmitEmptyLogs()
            ->useLogName('rat_suara');
    }    protected $table = 'rat_voting_suara';
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
