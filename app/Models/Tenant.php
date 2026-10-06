<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama', 'short_name', 'badan_hukum', 'nik_koperasi', 'npwp',
        'akta_pendirian', 'tanggal_akta',
        'logo_path', 'logo_dark_path', 'favicon_path', 'slogan',
        'alamat', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos',
        'telp', 'whatsapp', 'email', 'website',
        'nama_ketua', 'nama_sekretaris', 'nama_bendahara',
        'operation_mode', 'mata_uang', 'timezone', 'tahun_buku',
        'theme', 'primary_color', 'secondary_color', 'footer_text',
        'prefix_invoice', 'prefix_member', 'prefix_loan', 'prefix_savings',
        'status', 'subscription_until', 'plan', 'meta',
    ];

    protected $casts = [
        'tanggal_akta' => 'date',
        'tahun_buku' => 'integer',
        'subscription_until' => 'date',
        'meta' => 'array',
    ];

    /** Identitas koperasi instalasi ini (tunggal — bukan multi-tenant). */
    public static function current(): ?self
    {
        return static::find(\App\Support\Tenant\CurrentTenant::id());
    }

    /** Nama tampilan: pendek bila ada, kalau tidak nama penuh. */
    public function displayName(): string
    {
        return $this->short_name ?: $this->nama;
    }

    /** Alamat lengkap satu baris dari komponen wilayah. */
    public function fullAddress(): string
    {
        return collect([$this->alamat, $this->desa, $this->kecamatan, $this->kabupaten, $this->provinsi, $this->kode_pos])
            ->filter()->implode(', ');
    }

    public function isSyariah(): bool
    {
        return $this->operation_mode === 'syariah';
    }

    public function isDual(): bool
    {
        return $this->operation_mode === 'dual';
    }

    public function isKonvensional(): bool
    {
        return $this->operation_mode === 'konvensional';
    }
}
