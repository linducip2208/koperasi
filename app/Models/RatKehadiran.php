<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RatKehadiran extends Model
{
    use BelongsToTenant, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['rat_id', 'anggota_id', 'metode'])
            ->dontSubmitEmptyLogs()
            ->useLogName('rat_kehadiran');
    }

    protected $table = 'rat_kehadiran';

    protected $fillable = [
        'tenant_id', 'rat_id', 'anggota_id', 'checkin_at', 'metode',
    ];

    protected $casts = [
        'checkin_at' => 'datetime',
    ];

    public function rat()
    {
        return $this->belongsTo(Rat::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    protected static function booted(): void
    {
        static::created(fn ($m) => $m->rat?->refreshQuorum());
        static::deleted(fn ($m) => $m->rat?->refreshQuorum());
    }
}
