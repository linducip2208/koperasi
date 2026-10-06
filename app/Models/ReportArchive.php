<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportArchive extends Model
{
    use LogsActivity;

    protected $fillable = [
        'report_key', 'title', 'period', 'params', 'snapshot',
        'report_version', 'checksum', 'file_path', 'status', 'generated_by',
    ];

    protected $casts = ['params' => 'array'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['report_key', 'title', 'period', 'status'])
            ->dontSubmitEmptyLogs()
            ->useLogName('report_archive');
    }

    /** Verifikasi integritas snapshot. */
    public function verify(): bool
    {
        return hash_equals($this->checksum, hash('sha256', $this->snapshot));
    }
}
