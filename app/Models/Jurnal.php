<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurnal extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'jurnal';

    protected $fillable = [
        'tenant_id', 'cabang_id', 'nomor', 'tanggal', 'tipe',
        'referensi_type', 'referensi_id', 'keterangan',
        'total_debit', 'total_kredit', 'is_posted', 'posted_at',
        'posted_by', 'created_by', 'meta',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'posted_at'    => 'datetime',
        'is_posted'    => 'boolean',
        'total_debit'  => 'integer',
        'total_kredit' => 'integer',
        'meta'         => 'array',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(JurnalDetail::class);
    }

    public function referensi(): MorphTo
    {
        return $this->morphTo();
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function postedByUser()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isBalanced(): bool
    {
        return $this->total_debit === $this->total_kredit;
    }
}
