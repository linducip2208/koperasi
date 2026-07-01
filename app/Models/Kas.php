<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kas extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'kas';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'kode', 'nama', 'tipe',
        'nomor_rekening', 'nama_bank', 'atas_nama',
        'coa_id', 'saldo_awal', 'saldo', 'limit_minimum',
        'user_id', 'aktif',
    ];

    protected $casts = [
        'saldo_awal'    => 'integer',
        'saldo'         => 'integer',
        'limit_minimum' => 'integer',
        'aktif'         => 'boolean',
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }

    public function transaksi()
    {
        return $this->hasMany(KasTransaksi::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
