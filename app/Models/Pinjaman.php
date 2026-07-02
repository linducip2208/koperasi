<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pinjaman extends Model
{
    use BelongsToTenant, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_akad', 'plafon', 'tenor', 'status', 'kolektabilitas', 'tanggal_pencairan', 'approved_at', 'rejected_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pinjaman');
    }

    protected $table = 'pinjaman';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'anggota_id', 'produk_id',
        'nomor_akad', 'tanggal_pengajuan', 'tanggal_akad',
        'tanggal_pencairan', 'tanggal_jatuh_tempo',
        'plafon', 'pokok', 'margin_total', 'total_bayar',
        'tenor', 'bunga_persen', 'margin_persen',
        'nisbah_anggota', 'nisbah_koperasi',
        'biaya_admin', 'biaya_provisi', 'biaya_asuransi', 'biaya_materai',
        'total_biaya', 'pencairan_bersih', 'bukti_pencairan',
        'saldo_pokok', 'saldo_margin',
        'tujuan', 'analisa_kredit', 'survey_data',
        'status', 'kolektabilitas',
        'tunggakan_hari', 'tunggakan_pokok', 'tunggakan_margin', 'tunggakan_denda',
        'ao_id', 'approved_by', 'approved_at',
        'rejected_by', 'rejected_at', 'alasan_tolak',
        'meta',
    ];

    protected $casts = [
        'tanggal_pengajuan'   => 'date',
        'tanggal_akad'        => 'date',
        'tanggal_pencairan'   => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'approved_at'         => 'datetime',
        'rejected_at'         => 'datetime',
        'plafon'              => 'integer',
        'pokok'               => 'integer',
        'margin_total'        => 'integer',
        'total_bayar'         => 'integer',
        'tenor'               => 'integer',
        'bunga_persen'        => 'decimal:4',
        'margin_persen'       => 'decimal:4',
        'nisbah_anggota'      => 'decimal:2',
        'nisbah_koperasi'     => 'decimal:2',
        'biaya_admin'         => 'integer',
        'biaya_provisi'       => 'integer',
        'biaya_asuransi'      => 'integer',
        'biaya_materai'       => 'integer',
        'total_biaya'         => 'integer',
        'pencairan_bersih'    => 'integer',
        'saldo_pokok'         => 'integer',
        'saldo_margin'        => 'integer',
        'tunggakan_hari'      => 'integer',
        'tunggakan_pokok'     => 'integer',
        'tunggakan_margin'    => 'integer',
        'tunggakan_denda'     => 'integer',
        'survey_data'         => 'array',
        'meta'                => 'array',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function produk()
    {
        return $this->belongsTo(ProdukPinjaman::class, 'produk_id');
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(PinjamanJadwal::class)->orderBy('angsuran_ke');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PinjamanPembayaran::class)->orderBy('tanggal');
    }

    public function jaminan(): HasMany
    {
        return $this->hasMany(PinjamanJaminan::class);
    }

    public function approval(): HasMany
    {
        return $this->hasMany(PinjamanApproval::class)->orderBy('level');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function ao()
    {
        return $this->belongsTo(User::class, 'ao_id');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedByUser()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
