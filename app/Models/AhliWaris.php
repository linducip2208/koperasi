<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AhliWaris extends Model
{
    use BelongsToTenant;

    protected $table = 'ahli_waris';

    protected $fillable = [
        'tenant_id', 'anggota_id', 'nama', 'hubungan', 'nik',
        'tanggal_lahir', 'telp', 'alamat', 'persentase',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'persentase'    => 'decimal:2',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
