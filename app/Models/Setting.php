<?php

namespace App\Models;

use App\Support\Tenant\BelongsToTenant;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'group', 'key', 'value', 'type'];

    public static function get(string $key, mixed $default = null, string $group = 'general'): mixed
    {
        $tenantId = CurrentTenant::id();
        return Cache::remember("setting:{$tenantId}:{$group}:{$key}", 3600, function () use ($key, $group, $default) {
            $row = static::where('group', $group)->where('key', $key)->first();
            return $row ? static::cast($row->value, $row->type) : $default;
        });
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        $tenantId = CurrentTenant::id();
        $stored = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

        static::updateOrCreate(
            ['tenant_id' => $tenantId, 'group' => $group, 'key' => $key],
            ['value' => $stored, 'type' => $type]
        );

        Cache::forget("setting:{$tenantId}:{$group}:{$key}");
    }

    private static function cast(?string $value, string $type): mixed
    {
        return match ($type) {
            'int'     => (int) $value,
            'bool'    => filter_var($value, FILTER_VALIDATE_BOOL),
            'json'    => json_decode($value ?? 'null', true),
            'decimal' => (float) $value,
            default   => $value,
        };
    }
}
