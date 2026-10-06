<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomReport extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description', 'data_source', 'columns', 'filters', 'sort', 'limit', 'created_by'];

    protected $casts = ['columns' => 'array', 'filters' => 'array', 'sort' => 'array', 'limit' => 'integer'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'data_source'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('custom_report');
    }
}
