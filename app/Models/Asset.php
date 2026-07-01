<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'asset';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'kode', 'nama', 'kategori',
        'tanggal_perolehan', 'harga_perolehan', 'nilai_residu',
        'umur_ekonomis_bulan', 'metode_susut',
        'akumulasi_susut', 'nilai_buku',
        'coa_aset_id', 'coa_susut_id', 'coa_akumulasi_id',
        'status', 'tanggal_dilepas',
    ];

    protected $casts = [
        'tanggal_perolehan'   => 'date',
        'tanggal_dilepas'     => 'date',
        'harga_perolehan'     => 'integer',
        'nilai_residu'        => 'integer',
        'umur_ekonomis_bulan' => 'integer',
        'akumulasi_susut'     => 'integer',
        'nilai_buku'          => 'integer',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function coaAset()
    {
        return $this->belongsTo(Coa::class, 'coa_aset_id');
    }

    public function coaSusut()
    {
        return $this->belongsTo(Coa::class, 'coa_susut_id');
    }

    public function coaAkumulasi()
    {
        return $this->belongsTo(Coa::class, 'coa_akumulasi_id');
    }

    public function susutBulanan(): int
    {
        if ($this->metode_susut === 'garis_lurus') {
            return (int) round(($this->harga_perolehan - $this->nilai_residu) / $this->umur_ekonomis_bulan);
        }
        // saldo menurun: 2x dari rate garis lurus
        return (int) round($this->nilai_buku * 2 / $this->umur_ekonomis_bulan);
    }
}
