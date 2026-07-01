<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AsuransiKlaim extends Model
{
    use BelongsToTenant;

    protected $table = 'asuransi_klaim';

    protected $fillable = [
        'tenant_id', 'polis_id', 'tanggal_kejadian', 'tanggal_pengajuan',
        'nilai_klaim', 'uraian', 'status', 'nilai_diterima', 'tanggal_diterima',
    ];

    protected $casts = [
        'tanggal_kejadian'  => 'date',
        'tanggal_pengajuan' => 'date',
        'tanggal_diterima'  => 'date',
        'nilai_klaim'       => 'integer',
        'nilai_diterima'    => 'integer',
    ];

    public function polis() { return $this->belongsTo(AsuransiPolis::class, 'polis_id'); }
}
