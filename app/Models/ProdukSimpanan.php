<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukSimpanan extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'produk_simpanan';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'jenis', 'akad_type',
        'setoran_minimum', 'setoran_wajib', 'saldo_minimum',
        'bunga_persen_tahun', 'metode_bunga',
        'nisbah_anggota', 'nisbah_koperasi',
        'boleh_tarik', 'berjangka', 'tenor_bulan', 'auto_potong_shu',
        'coa_simpanan_id', 'coa_bunga_id', 'aktif', 'meta',
    ];

    protected $casts = [
        'setoran_minimum'    => 'integer',
        'setoran_wajib'      => 'integer',
        'saldo_minimum'      => 'integer',
        'bunga_persen_tahun' => 'decimal:4',
        'nisbah_anggota'     => 'decimal:2',
        'nisbah_koperasi'    => 'decimal:2',
        'boleh_tarik'        => 'boolean',
        'berjangka'          => 'boolean',
        'auto_potong_shu'    => 'boolean',
        'aktif'              => 'boolean',
        'meta'               => 'array',
    ];

    public function isSyariah(): bool
    {
        return in_array($this->akad_type, ['wadiah', 'mudharabah']);
    }

    public function coaSimpanan()
    {
        return $this->belongsTo(Coa::class, 'coa_simpanan_id');
    }

    public function coaBunga()
    {
        return $this->belongsTo(Coa::class, 'coa_bunga_id');
    }
}
