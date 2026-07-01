<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Simpanan extends Model
{
    use BelongsToTenant, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_rekening', 'saldo', 'saldo_blokir', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('simpanan');
    }

    protected $table = 'simpanan';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'anggota_id', 'produk_id',
        'nomor_rekening', 'saldo', 'saldo_blokir', 'setoran_pokok',
        'tanggal_buka', 'tanggal_jatuh_tempo', 'tanggal_tutup',
        'status', 'meta',
    ];

    protected $casts = [
        'saldo'               => 'integer',
        'saldo_blokir'        => 'integer',
        'setoran_pokok'       => 'integer',
        'tanggal_buka'        => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_tutup'       => 'date',
        'meta'                => 'array',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function produk()
    {
        return $this->belongsTo(ProdukSimpanan::class, 'produk_id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(SimpananTransaksi::class)->orderBy('tanggal');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function saldoTersedia(): int
    {
        return max(0, $this->saldo - $this->saldo_blokir);
    }
}
