<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CollectionFollowup extends Model
{
    use BelongsToTenant, LogsActivity;

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'user_id', 'jenis',
        'catatan', 'janji_nominal', 'janji_tanggal', 'bukti_path',
    ];

    protected $casts = ['janji_nominal' => 'integer', 'janji_tanggal' => 'date'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['pinjaman_id', 'jenis', 'janji_nominal', 'janji_tanggal'])
            ->dontSubmitEmptyLogs()
            ->useLogName('collection');
    }

    public function pinjaman() { return $this->belongsTo(Pinjaman::class); }
    public function user() { return $this->belongsTo(User::class); }
}
