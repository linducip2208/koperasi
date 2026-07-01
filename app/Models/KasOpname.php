<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class KasOpname extends Model
{
    use BelongsToTenant;

    protected $table = 'kas_opname';

    protected $fillable = [
        'tenant_id', 'kas_id', 'tanggal',
        'saldo_sistem', 'saldo_fisik', 'selisih', 'catatan', 'user_id',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'saldo_sistem' => 'integer',
        'saldo_fisik'  => 'integer',
        'selisih'      => 'integer',
    ];

    public function kas() { return $this->belongsTo(Kas::class); }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
