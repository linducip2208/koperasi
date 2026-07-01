<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KasTransaksi extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'kas_transaksi';

    protected $fillable = [
        'tenant_id', 'kas_id', 'nomor', 'tanggal', 'jenis',
        'jumlah', 'coa_id', 'kas_tujuan_id',
        'referensi_type', 'referensi_id', 'keterangan',
        'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer',
    ];

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function kasTujuan()
    {
        return $this->belongsTo(Kas::class, 'kas_tujuan_id');
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
