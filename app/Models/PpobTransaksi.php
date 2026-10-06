<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PpobTransaksi extends Model
{
    protected $table = 'ppob_transaksi';
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor', 'no_tujuan', 'harga', 'status', 'sn'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('ppob');
    }

    protected $casts = [
        'harga'      => 'integer',
        'harga_beli' => 'integer',
        'laba'       => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function produk()
    {
        return $this->belongsTo(PpobProduk::class, 'ppob_produk_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
