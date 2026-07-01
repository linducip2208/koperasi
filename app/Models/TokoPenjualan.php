<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TokoPenjualan extends Model
{
    use BelongsToTenant;

    protected $table = 'toko_penjualan';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'anggota_id', 'nomor', 'tanggal',
        'subtotal', 'diskon', 'pajak', 'total', 'bayar', 'kembali',
        'metode_bayar', 'kas_id', 'simpanan_id', 'status',
        'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'subtotal' => 'integer', 'diskon' => 'integer', 'pajak' => 'integer',
        'total'    => 'integer', 'bayar' => 'integer', 'kembali' => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function detail()
    {
        return $this->hasMany(TokoPenjualanDetail::class, 'penjualan_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class);
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
