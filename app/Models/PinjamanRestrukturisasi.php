<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanRestrukturisasi extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_restrukturisasi';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'tanggal', 'jenis',
        'sebelum', 'sesudah', 'alasan', 'user_id',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'sebelum'  => 'array',
        'sesudah'  => 'array',
    ];

    public function pinjaman() { return $this->belongsTo(Pinjaman::class); }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
