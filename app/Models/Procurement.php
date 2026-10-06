<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Procurement extends Model
{
    use BelongsToTenant, LogsActivity;

    protected $fillable = [
        'tenant_id', 'nomor', 'judul', 'deskripsi', 'supplier_id',
        'estimasi', 'aktual', 'status', 'catatan', 'created_by',
    ];

    protected $casts = ['estimasi' => 'integer', 'aktual' => 'integer'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor', 'judul', 'estimasi', 'aktual', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('procurement');
    }

    public function supplier() { return $this->belongsTo(TokoSupplier::class, 'supplier_id'); }

    public function allowedTransitions(): array
    {
        return \App\Workflow\WorkflowService::allowed($this->status);
    }
}
