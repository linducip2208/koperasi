<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TokoPembelian extends Model
{
    use BelongsToTenant;

    protected $table = 'toko_pembelian';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'supplier_id', 'nomor', 'tanggal',
        'tanggal_jatuh_tempo', 'subtotal', 'diskon', 'pajak', 'biaya_lain',
        'total', 'terbayar', 'status', 'keterangan', 'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'             => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'subtotal'            => 'integer',
        'diskon'              => 'integer',
        'pajak'               => 'integer',
        'biaya_lain'          => 'integer',
        'total'               => 'integer',
        'terbayar'            => 'integer',
    ];

    public function supplier() { return $this->belongsTo(TokoSupplier::class, 'supplier_id'); }
    public function cabang()   { return $this->belongsTo(Cabang::class); }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
