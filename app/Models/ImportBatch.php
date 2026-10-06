<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ImportBatch extends Model
{
    use LogsActivity;

    protected $fillable = [
        'tipe', 'file_name', 'file_path', 'total_rows', 'valid_rows',
        'invalid_rows', 'duplicate_rows', 'imported_rows', 'error_file',
        'status', 'summary', 'user_id',
    ];

    protected $casts = [
        'total_rows' => 'integer', 'valid_rows' => 'integer', 'invalid_rows' => 'integer',
        'duplicate_rows' => 'integer', 'imported_rows' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['tipe', 'file_name', 'status', 'imported_rows'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('import');
    }
}
