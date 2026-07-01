<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coa extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'coa';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'tipe', 'kelompok', 'saldo_normal',
        'parent_id', 'is_kas', 'is_bank', 'is_postable', 'is_aktif',
        'saldo_awal', 'meta',
    ];

    protected $casts = [
        'is_kas'      => 'boolean',
        'is_bank'     => 'boolean',
        'is_postable' => 'boolean',
        'is_aktif'    => 'boolean',
        'saldo_awal'  => 'integer',
        'meta'        => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(Coa::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Coa::class, 'parent_id');
    }

    public function jurnalDetails()
    {
        return $this->hasMany(JurnalDetail::class);
    }

    public function saldoBerjalan(?string $sampaiTanggal = null): int
    {
        $query = $this->jurnalDetails()
            ->whereHas('jurnal', function ($q) use ($sampaiTanggal) {
                $q->where('is_posted', true);
                if ($sampaiTanggal) {
                    $q->where('tanggal', '<=', $sampaiTanggal);
                }
            });

        $debit  = (int) $query->sum('debit');
        $kredit = (int) $query->sum('kredit');

        return $this->saldo_normal === 'debit'
            ? $this->saldo_awal + $debit - $kredit
            : $this->saldo_awal + $kredit - $debit;
    }
}
