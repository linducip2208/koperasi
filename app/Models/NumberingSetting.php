<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class NumberingSetting extends Model
{
    use BelongsToTenant;

    protected $table = 'numbering_setting';

    protected $fillable = [
        'tenant_id', 'kode', 'prefix', 'format',
        'panjang_seq', 'reset_period', 'next_number', 'last_reset_at',
    ];

    protected $casts = [
        'last_reset_at' => 'date',
        'panjang_seq'   => 'integer',
        'reset_period'  => 'integer',
        'next_number'   => 'integer',
    ];
}
