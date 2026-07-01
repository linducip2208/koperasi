<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SimpananTransaksi extends Model
{
    use BelongsToTenant;

    protected $table = 'simpanan_transaksi';

    protected $fillable = [
        'tenant_id', 'simpanan_id', 'nomor', 'tanggal', 'jenis',
        'jumlah', 'saldo_sebelum', 'saldo_sesudah',
        'kas_id', 'metode_bayar', 'keterangan',
        'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'jumlah'        => 'integer',
        'saldo_sebelum' => 'integer',
        'saldo_sesudah' => 'integer',
    ];

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class);
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
