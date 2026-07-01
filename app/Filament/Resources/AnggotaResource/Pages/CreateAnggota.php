<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function afterCreate(): void
    {
        $anggota = $this->record;

        if (empty($anggota->email)) {
            return;
        }

        if ($anggota->user_id) {
            return;
        }

        $existingUser = User::where('email', $anggota->email)->first();

        if ($existingUser) {
            $anggota->updateQuietly(['user_id' => $existingUser->id]);
            if (!$existingUser->hasRole('anggota')) {
                $existingUser->assignRole('anggota');
            }
            return;
        }

        $tempPassword = Str::random(8);
        $user = User::create([
            'tenant_id' => $anggota->tenant_id,
            'name'      => $anggota->nama,
            'email'     => $anggota->email,
            'password'  => Hash::make($tempPassword),
            'aktif'     => true,
        ]);

        $user->assignRole('anggota');

        $anggota->updateQuietly(['user_id' => $user->id]);

        \Filament\Notifications\Notification::make()
            ->title('Akun anggota dibuat')
            ->body("Password sementara: {$tempPassword}. Beritahu anggota untuk login di /portal/login.")
            ->success()
            ->send();
    }
}
