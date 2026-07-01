<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanJadwal extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_jadwal';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'angsuran_ke', 'tanggal_jatuh_tempo',
        'pokok', 'margin', 'total_angsuran', 'saldo_pokok',
        'terbayar_pokok', 'terbayar_margin', 'denda', 'terbayar_denda',
        'tanggal_bayar', 'status',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar'       => 'date',
        'pokok'               => 'integer',
        'margin'              => 'integer',
        'total_angsuran'      => 'integer',
        'saldo_pokok'         => 'integer',
        'terbayar_pokok'      => 'integer',
        'terbayar_margin'     => 'integer',
        'denda'               => 'integer',
        'terbayar_denda'      => 'integer',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function sisa(): int
    {
        return max(0, $this->total_angsuran - $this->terbayar_pokok - $this->terbayar_margin);
    }
}
