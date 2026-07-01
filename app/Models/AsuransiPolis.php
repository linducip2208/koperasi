<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AsuransiPolis extends Model
{
    use BelongsToTenant;

    protected $table = 'asuransi_polis';

    protected $fillable = [
        'tenant_id', 'produk_id', 'pinjaman_id', 'anggota_id',
        'nomor_polis', 'nilai_pertanggungan', 'premi',
        'tanggal_mulai', 'tanggal_akhir', 'status',
    ];

    protected $casts = [
        'nilai_pertanggungan' => 'integer',
        'premi'               => 'integer',
        'tanggal_mulai'       => 'date',
        'tanggal_akhir'       => 'date',
    ];

    public function produk()    { return $this->belongsTo(AsuransiProduk::class, 'produk_id'); }
    public function anggota()   { return $this->belongsTo(Anggota::class); }
    public function pinjaman()  { return $this->belongsTo(Pinjaman::class); }
    public function klaim()     { return $this->hasMany(AsuransiKlaim::class, 'polis_id'); }
}
