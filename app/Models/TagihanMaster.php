<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TagihanMaster extends Model
{
    use BelongsToTenant;

    protected $table = 'tagihan_master';

    protected $fillable = [
        'tenant_id', 'kode', 'nama', 'nominal', 'siklus',
        'coa_id', 'auto_potong_simpanan', 'aktif',
    ];

    protected $casts = [
        'nominal'              => 'integer',
        'auto_potong_simpanan' => 'boolean',
        'aktif'                => 'boolean',
    ];

    public function coa()     { return $this->belongsTo(Coa::class); }
    public function tagihan() { return $this->hasMany(Tagihan::class, 'master_id'); }
}
