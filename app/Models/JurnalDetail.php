<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class JurnalDetail extends Model
{
    use BelongsToTenant;

    protected $table = 'jurnal_detail';

    protected $fillable = ['tenant_id', 'jurnal_id', 'coa_id', 'debit', 'kredit', 'keterangan'];

    protected $casts = ['debit' => 'integer', 'kredit' => 'integer'];

    protected static function booted(): void
    {
        // Baris jurnal posted immutable — perubahan hanya lewat reversal.
        $guard = function (JurnalDetail $detail) {
            if ($detail->jurnal && $detail->jurnal->is_posted) {
                throw new \RuntimeException('Baris jurnal posted tidak boleh diubah/dihapus. Gunakan reversal.');
            }
        };
        static::updating($guard);
        static::deleting($guard);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }
}
