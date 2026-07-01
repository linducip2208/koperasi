<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentProvider extends Model
{
    protected $fillable = [
        'nama', 'api_format', 'base_url', 'session_endpoint',
        'api_key', 'api_key_encrypted', 'merchant_id', 'extra_headers',
        'is_sandbox', 'aktif', 'catatan',
    ];

    protected $casts = [
        'extra_headers' => 'array',
        'is_sandbox'    => 'boolean',
        'aktif'         => 'boolean',
    ];

    /** Virtual attribute — never persisted to DB column. */
    public function setApiKeyAttribute(?string $value): void
    {
        $this->attributes['api_key_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getApiKeyAttribute(): ?string
    {
        if (empty($this->api_key_encrypted)) return null;
        try { return Crypt::decryptString($this->api_key_encrypted); }
        catch (\Throwable) { return null; }
    }

    /** Hide raw encrypted blob from JSON output. */
    protected $hidden = ['api_key_encrypted'];
}
