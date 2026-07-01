<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama', 'badan_hukum', 'nik_koperasi', 'npwp', 'akta_pendirian',
        'logo_path', 'alamat', 'telp', 'email', 'website',
        'operation_mode', 'mata_uang', 'tahun_buku',
        'status', 'subscription_until', 'plan', 'meta',
    ];

    protected $casts = [
        'tahun_buku'         => 'integer',
        'subscription_until' => 'date',
        'meta'               => 'array',
    ];

    public function isSyariah(): bool
    {
        return $this->operation_mode === 'syariah';
    }

    public function isDual(): bool
    {
        return $this->operation_mode === 'dual';
    }

    public function isKonvensional(): bool
    {
        return $this->operation_mode === 'konvensional';
    }
}
