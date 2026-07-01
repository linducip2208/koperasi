<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Anggota extends Model
{
    use BelongsToTenant, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nomor_anggota', 'nama', 'telp', 'email', 'status', 'cabang_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('anggota');
    }

    protected $table = 'anggota';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'nomor_anggota', 'nik', 'nama',
        'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'status_perkawinan',
        'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'kode_pos',
        'telp', 'email', 'npwp', 'pekerjaan', 'nama_perusahaan',
        'penghasilan_bulanan', 'sumber_dana',
        'foto_path', 'ktp_path', 'kk_path',
        'kategori', 'status', 'tanggal_masuk', 'tanggal_keluar', 'alasan_keluar',
        'user_id', 'meta',
    ];

    protected $casts = [
        'tanggal_lahir'       => 'date',
        'tanggal_masuk'       => 'date',
        'tanggal_keluar'      => 'date',
        'penghasilan_bulanan' => 'integer',
        'meta'                => 'array',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ahliWaris(): HasMany
    {
        return $this->hasMany(AhliWaris::class);
    }

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class);
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(AnggotaStatusLog::class);
    }

    public function totalSimpanan(): int
    {
        return (int) $this->simpanan()->where('status', 'aktif')->sum('saldo');
    }

    public function pinjamanAktif()
    {
        return $this->pinjaman()->whereIn('status', ['aktif', 'macet'])->get();
    }
}
