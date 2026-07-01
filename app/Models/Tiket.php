<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
        'sla_jam'         => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function balasan()
    {
        return $this->hasMany(TiketBalasan::class, 'tiket_id')->orderBy('created_at');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
