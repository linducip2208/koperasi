<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MemberDocument extends Model
{
    use BelongsToTenant, LogsActivity;

    protected $fillable = [
        'tenant_id', 'anggota_id', 'jenis', 'nama', 'file_path',
        'mime', 'ukuran', 'versi', 'tanggal_berlaku', 'tanggal_kedaluwarsa',
        'status', 'uploaded_by',
    ];

    protected $casts = [
        'ukuran' => 'integer', 'versi' => 'integer',
        'tanggal_berlaku' => 'date', 'tanggal_kedaluwarsa' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['anggota_id', 'jenis', 'nama', 'versi', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('documents');
    }

    public function anggota() { return $this->belongsTo(Anggota::class); }

    public function isExpired(): bool
    {
        return $this->tanggal_kedaluwarsa && $this->tanggal_kedaluwarsa->isPast();
    }
}
