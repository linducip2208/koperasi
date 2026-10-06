<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledReport extends Model
{
    protected $fillable = [
        'report_key', 'name', 'params', 'frequency', 'action',
        'email_to', 'aktif', 'last_run_at', 'next_run_at', 'created_by',
    ];

    protected $casts = [
        'params' => 'array', 'aktif' => 'boolean',
        'last_run_at' => 'datetime', 'next_run_at' => 'datetime',
    ];

    public static function nextRun(string $frequency, ?\Carbon\Carbon $from = null): \Carbon\Carbon
    {
        $from ??= now();
        return match ($frequency) {
            'daily' => $from->copy()->addDay()->startOfDay()->addHours(6),
            'weekly' => $from->copy()->next('monday')->startOfDay()->addHours(6),
            'monthly' => $from->copy()->addMonthNoOverflow()->startOfMonth()->addHours(6),
            'quarterly' => $from->copy()->addQuarterNoOverflow()->firstOfQuarter()->startOfDay()->addHours(6),
            'yearly' => $from->copy()->addYearNoOverflow()->startOfYear()->addHours(6),
            default => $from->copy()->addDay(),
        };
    }
}
