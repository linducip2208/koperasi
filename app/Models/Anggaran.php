<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use BelongsToTenant;

    protected $table = 'anggaran';

    protected $fillable = [
        'tenant_id', 'tahun', 'coa_id',
        'jan', 'feb', 'mar', 'apr', 'mei', 'jun',
        'jul', 'agu', 'sep', 'okt', 'nov', 'des',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jan'   => 'integer', 'feb' => 'integer', 'mar' => 'integer',
        'apr'   => 'integer', 'mei' => 'integer', 'jun' => 'integer',
        'jul'   => 'integer', 'agu' => 'integer', 'sep' => 'integer',
        'okt'   => 'integer', 'nov' => 'integer', 'des' => 'integer',
    ];

    public function coa() { return $this->belongsTo(Coa::class); }

    public function getTotalAttribute(): int
    {
        return $this->jan + $this->feb + $this->mar + $this->apr + $this->mei + $this->jun
             + $this->jul + $this->agu + $this->sep + $this->okt + $this->nov + $this->des;
    }
}
