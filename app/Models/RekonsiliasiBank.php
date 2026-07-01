<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiBank extends Model
{
    use BelongsToTenant;

    protected $table = 'rekonsiliasi_bank';

    protected $fillable = [
        'tenant_id', 'kas_id', 'tanggal', 'periode_akhir',
        'saldo_buku', 'saldo_bank', 'selisih', 'rincian', 'status', 'user_id',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'periode_akhir' => 'date',
        'saldo_buku'    => 'integer',
        'saldo_bank'    => 'integer',
        'selisih'       => 'integer',
        'rincian'       => 'array',
    ];

    public function kas() { return $this->belongsTo(Kas::class); }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
