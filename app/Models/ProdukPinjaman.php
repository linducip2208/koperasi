<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukPinjaman extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'produk_pinjaman';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'jenis', 'akad_type', 'metode_perhitungan',
        'plafon_minimum', 'plafon_maksimum', 'tenor_minimum', 'tenor_maksimum',
        'bunga_persen', 'margin_persen', 'nisbah_anggota', 'nisbah_koperasi',
        'biaya_admin_persen', 'biaya_admin_flat', 'biaya_provisi_persen',
        'biaya_asuransi_persen', 'biaya_materai',
        'denda_persen_per_hari', 'denda_flat_per_hari',
        'butuh_jaminan', 'butuh_simpanan_blokir', 'rasio_simpanan_blokir',
        'coa_pokok_id', 'coa_bunga_id', 'coa_denda_id',
        'aktif', 'meta',
    ];

    protected $casts = [
        'plafon_minimum'        => 'integer',
        'plafon_maksimum'       => 'integer',
        'bunga_persen'          => 'decimal:4',
        'margin_persen'         => 'decimal:4',
        'nisbah_anggota'        => 'decimal:2',
        'nisbah_koperasi'       => 'decimal:2',
        'biaya_admin_persen'    => 'decimal:2',
        'biaya_admin_flat'      => 'integer',
        'biaya_provisi_persen'  => 'decimal:2',
        'biaya_asuransi_persen' => 'decimal:2',
        'biaya_materai'         => 'integer',
        'denda_persen_per_hari' => 'decimal:4',
        'denda_flat_per_hari'   => 'integer',
        'butuh_jaminan'         => 'boolean',
        'butuh_simpanan_blokir' => 'boolean',
        'rasio_simpanan_blokir' => 'decimal:2',
        'aktif'                 => 'boolean',
        'meta'                  => 'array',
    ];

    public function isSyariah(): bool
    {
        return in_array($this->akad_type, [
            'murabahah', 'mudharabah', 'musyarakah', 'ijarah', 'ijarah_mb',
            'qardh', 'rahn', 'salam', 'istishna',
        ]);
    }

    public function coaPokok()
    {
        return $this->belongsTo(Coa::class, 'coa_pokok_id');
    }

    public function coaBunga()
    {
        return $this->belongsTo(Coa::class, 'coa_bunga_id');
    }

    public function coaDenda()
    {
        return $this->belongsTo(Coa::class, 'coa_denda_id');
    }
}
