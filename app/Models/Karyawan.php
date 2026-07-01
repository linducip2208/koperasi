<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'karyawan';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'user_id', 'nip', 'nama',
        'jabatan', 'departemen', 'tanggal_masuk', 'tanggal_keluar',
        'gaji_pokok', 'tunjangan',
        'npwp', 'no_bpjs_kesehatan', 'no_bpjs_naker',
        'rekening_bank', 'nomor_rekening', 'status',
    ];

    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'gaji_pokok'     => 'integer',
        'tunjangan'      => 'array',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
