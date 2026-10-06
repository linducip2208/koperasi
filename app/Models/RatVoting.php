<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RatVoting extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'is_aktif', 'mulai', 'selesai'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('rat_voting');
    }    protected $table = 'rat_voting';
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
