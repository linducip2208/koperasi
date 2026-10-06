<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AuditFinding extends Model
{
    use BelongsToTenant, LogsActivity;

    protected $fillable = [
        'tenant_id', 'judul', 'deskripsi', 'kategori', 'severity',
        'status', 'tindak_lanjut', 'owner_id', 'due_date', 'resolved_at', 'created_by',
    ];

    protected $casts = ['due_date' => 'date', 'resolved_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'kategori', 'severity', 'status', 'owner_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('audit');
    }

    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}
