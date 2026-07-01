<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PeriodeAkuntansi extends Model
{
    use BelongsToTenant;

    protected $table = 'periode_akuntansi';

    protected $fillable = [
        'tenant_id', 'tahun', 'bulan',
        'tanggal_mulai', 'tanggal_akhir',
        'status', 'closed_at', 'closed_by',
    ];

    protected $casts = [
        'tahun'         => 'integer',
        'bulan'         => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'closed_at'     => 'datetime',
    ];

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
