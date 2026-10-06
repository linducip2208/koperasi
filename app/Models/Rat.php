<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rat extends Model
{
    use BelongsToTenant, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['tahun_buku', 'tanggal', 'lokasi', 'jumlah_hadir', 'quorum_tercapai', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('rat');
    }

    protected $table = 'rat';

    protected $fillable = [
        'tenant_id', 'tahun_buku', 'tanggal', 'lokasi',
        'agenda', 'jumlah_anggota_terdaftar', 'jumlah_hadir',
        'quorum_persen', 'quorum_tercapai', 'notulen',
        'keputusan', 'status',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'agenda'          => 'array',
        'keputusan'       => 'array',
        'quorum_tercapai' => 'boolean',
    ];

    public function kehadiran()
    {
        return $this->hasMany(RatKehadiran::class);
    }

    public function votings()
    {
        return $this->hasMany(RatVoting::class);
    }

    /** Hitung ulang jumlah hadir + status quorum dari tabel kehadiran.
     *  RAT selesai = arsip: angka manual dipertahankan, hanya flag dihitung ulang.
     *  RAT rencana/berlangsung = angka live dari tabel kehadiran. */
    public function refreshQuorum(): void
    {
        $terdaftar = $this->jumlah_anggota_terdaftar > 0
            ? $this->jumlah_anggota_terdaftar
            : (int) Anggota::where('status', 'aktif')->count();

        $hadir = $this->status === 'selesai' && $this->kehadiran()->count() === 0
            ? (int) $this->jumlah_hadir
            : $this->kehadiran()->count();

        $this->forceFill([
            'jumlah_anggota_terdaftar' => $terdaftar,
            'jumlah_hadir' => $hadir,
            'quorum_tercapai' => $terdaftar > 0
                ? ($hadir * 100 / $terdaftar) >= (float) $this->quorum_persen
                : false,
        ])->saveQuietly();
    }

    public function quorumAktualPersen(): float
    {
        if ($this->jumlah_anggota_terdaftar <= 0) return 0;
        return round($this->jumlah_hadir * 100 / $this->jumlah_anggota_terdaftar, 1);
    }
}
