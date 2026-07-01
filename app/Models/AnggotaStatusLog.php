<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AnggotaStatusLog extends Model
{
    use BelongsToTenant;

    protected $table = 'anggota_status_log';

    protected $fillable = [
        'tenant_id', 'anggota_id', 'dari_status', 'ke_status', 'tanggal', 'catatan', 'user_id',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
