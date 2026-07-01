<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanPembayaran extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_pembayaran';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'nomor', 'tanggal', 'jenis',
        'total_bayar', 'alokasi_pokok', 'alokasi_margin', 'alokasi_denda',
        'alokasi_admin', 'alokasi_titipan',
        'kas_id', 'metode_bayar', 'keterangan',
        'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'total_bayar'     => 'integer',
        'alokasi_pokok'   => 'integer',
        'alokasi_margin'  => 'integer',
        'alokasi_denda'   => 'integer',
        'alokasi_admin'   => 'integer',
        'alokasi_titipan' => 'integer',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
