<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referral';
    protected $guarded = ['id'];

    protected $casts = [
        'komisi' => 'integer',
        'tanggal_daftar' => 'datetime',
        'tanggal_aktif' => 'datetime',
        'tanggal_komisi' => 'datetime',
    ];

    public function pengajak()
    {
        return $this->belongsTo(Anggota::class, 'pengajak_id');
    }

    public function diajak()
    {
        return $this->belongsTo(Anggota::class, 'diajak_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
