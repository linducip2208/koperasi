<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use BelongsToTenant;

    protected $table = 'gaji';

    protected $fillable = [
        'tenant_id', 'karyawan_id', 'tahun', 'bulan',
        'gaji_pokok', 'total_tunjangan', 'lembur',
        'total_potongan', 'pph21', 'bpjs_potongan',
        'total_bruto', 'total_netto', 'detail',
        'tanggal_bayar', 'status', 'jurnal_id',
    ];

    protected $casts = [
        'tanggal_bayar'   => 'date',
        'gaji_pokok'      => 'integer', 'total_tunjangan' => 'integer',
        'lembur'          => 'integer', 'total_potongan' => 'integer',
        'pph21'           => 'integer', 'bpjs_potongan' => 'integer',
        'total_bruto'     => 'integer', 'total_netto'  => 'integer',
        'detail'          => 'array',
    ];

    public function karyawan() { return $this->belongsTo(Karyawan::class); }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }
}
