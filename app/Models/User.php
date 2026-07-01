<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'cabang_id', 'name', 'nip', 'email', 'telp',
        'password', 'avatar_path', 'aktif',
        'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'aktif'             => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->aktif === true
            && ! empty($this->email)
            && ! $this->hasRole('anggota');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
