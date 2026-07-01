<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanJaminan extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_jaminan';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'jenis', 'nama', 'nomor_dokumen', 'atas_nama',
        'nilai_taksiran', 'nilai_pasar', 'ltv', 'foto_path', 'dokumen_path',
        'status', 'tanggal_lepas', 'catatan',
    ];

    protected $casts = [
        'nilai_taksiran' => 'integer',
        'nilai_pasar'    => 'integer',
        'ltv'            => 'decimal:2',
        'foto_path'      => 'array',
        'dokumen_path'   => 'array',
        'tanggal_lepas'  => 'date',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}
