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
        'penghasilan_bulanan', 'penghasilan_bukti', 'sumber_dana',
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

    public function totalHutang(): int
    {
        return (int) $this->pinjaman()->where('status', 'aktif')->sum('saldo_pokok');
    }

    public function totalCicilanPerBulan(): int
    {
        $total = 0;
        $pinjamanAktif = $this->pinjaman()->where('status', 'aktif')->get();

        foreach ($pinjamanAktif as $p) {
            $nextJadwal = $p->jadwal()
                ->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo', 'telat'])
                ->orderBy('angsuran_ke')
                ->first();

            if ($nextJadwal) {
                $total += $nextJadwal->total_angsuran;
            }
        }

        return $total;
    }

    public function rasioHutang(): float
    {
        if (! $this->penghasilan_bulanan || $this->penghasilan_bulanan <= 0) {
            return 0;
        }
        return round(($this->totalCicilanPerBulan() / $this->penghasilan_bulanan) * 100, 2);
    }

    public function maxCicilanBulanan(): int
    {
        return (int) ($this->penghasilan_bulanan * 0.4);
    }

    public function sisaKemampuanCicilan(): int
    {
        return max(0, $this->maxCicilanBulanan() - $this->totalCicilanPerBulan());
    }
}
