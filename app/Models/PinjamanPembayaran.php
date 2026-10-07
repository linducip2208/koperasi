<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PinjamanPembayaran extends Model
{
    use BelongsToTenant;

    protected $table = 'pinjaman_pembayaran';

    protected $fillable = [
        'tenant_id', 'pinjaman_id', 'nomor', 'idempotency_key', 'device_id',
        'tanggal', 'jenis',
        'total_bayar', 'alokasi_pokok', 'alokasi_margin', 'alokasi_denda',
        'alokasi_admin', 'alokasi_titipan',
        'kas_id', 'metode_bayar', 'keterangan',
        'status', 'bukti_bayar', 'verified_by', 'verified_at', 'catatan_verifikasi',
        'jurnal_id', 'user_id',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'total_bayar'     => 'integer',
        'alokasi_pokok'   => 'integer',
        'alokasi_margin'  => 'integer',
        'alokasi_denda'   => 'integer',
        'alokasi_admin'   => 'integer',
        'alokasi_titipan' => 'integer',
        'verified_at'     => 'datetime',
    ];

    protected static function booted(): void
    {
        // Nominal pembayaran yang sudah diverifikasi immutable.
        // Verifikasi pending→disetujui/ditolak tetap lewat service (kolom non-nominal).
        static::updating(function (PinjamanPembayaran $b) {
            $nominal = ['total_bayar', 'alokasi_pokok', 'alokasi_margin', 'alokasi_denda', 'alokasi_admin', 'alokasi_titipan', 'pinjaman_id', 'tanggal'];
            $ubahNominal = count(array_intersect($nominal, array_keys($b->getDirty()))) > 0;
            if ($ubahNominal && $b->getOriginal('status') !== 'pending') {
                throw new \RuntimeException("Pembayaran {$b->nomor} sudah diverifikasi — nominal immutable.");
            }
        });
        static::deleting(function (PinjamanPembayaran $b) {
            throw new \RuntimeException("Pembayaran {$b->nomor} tidak boleh dihapus.");
        });
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
