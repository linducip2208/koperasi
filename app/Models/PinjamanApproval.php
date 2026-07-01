<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanApproval extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_approval';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'level', 'jabatan',
        'user_id', 'keputusan', 'catatan', 'decided_at',
    ];

    protected $casts = ['decided_at' => 'datetime'];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
