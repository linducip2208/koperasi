<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ShuPerhitungan extends Model
{
    use BelongsToTenant;

    protected $table = 'shu_perhitungan';

    protected $fillable = [
        'tenant_id', 'tahun', 'shu_total',
        'persen_jasa_modal', 'persen_jasa_anggota',
        'persen_dana_cadangan', 'persen_dana_pendidikan', 'persen_dana_sosial',
        'persen_dana_pengurus', 'persen_dana_karyawan',
        'jumlah_jasa_modal', 'jumlah_jasa_anggota',
        'jumlah_dana_cadangan', 'jumlah_dana_pendidikan', 'jumlah_dana_sosial',
        'jumlah_dana_pengurus', 'jumlah_dana_karyawan',
        'status', 'approved_at', 'distributed_at', 'jurnal_id', 'meta',
    ];

    protected $casts = [
        'shu_total'              => 'integer',
        'persen_jasa_modal'      => 'decimal:2',
        'persen_jasa_anggota'    => 'decimal:2',
        'persen_dana_cadangan'   => 'decimal:2',
        'persen_dana_pendidikan' => 'decimal:2',
        'persen_dana_sosial'     => 'decimal:2',
        'persen_dana_pengurus'   => 'decimal:2',
        'persen_dana_karyawan'   => 'decimal:2',
        'jumlah_jasa_modal'      => 'integer',
        'jumlah_jasa_anggota'    => 'integer',
        'jumlah_dana_cadangan'   => 'integer',
        'jumlah_dana_pendidikan' => 'integer',
        'jumlah_dana_sosial'     => 'integer',
        'jumlah_dana_pengurus'   => 'integer',
        'jumlah_dana_karyawan'   => 'integer',
        'approved_at'            => 'datetime',
        'distributed_at'         => 'datetime',
        'meta'                   => 'array',
    ];

    public function distribusi()
    {
        return $this->hasMany(ShuDistribusi::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
