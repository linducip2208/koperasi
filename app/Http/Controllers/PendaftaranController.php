<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\AnggotaStatusLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    public function show()
    {
        return view('pendaftaran.form', ['brand' => config('pseo.brand')]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'nik'             => 'required|string|min:16|max:20|unique:anggota,nik',
            'nama'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'telp'            => 'required|string|max:20',
            'tempat_lahir'    => 'nullable|string|max:255',
            'tanggal_lahir'   => 'required|date|before:today',
            'jenis_kelamin'   => 'required|in:L,P',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:255',
            'pekerjaan'       => 'nullable|string|max:255',
            'penghasilan_bulanan' => 'nullable|integer|min:0',
        ]);

        $tempPassword = Str::random(8);

        $user = User::create([
            'tenant_id' => 1,
            'name'     => $data['nama'],
            'email'    => $data['email'],
            'password' => Hash::make($tempPassword),
        ]);

        $user->assignRole('anggota');

        $anggota = Anggota::create([
            'tenant_id'           => 1,
            'cabang_id'           => 1,
            'user_id'             => $user->id,
            'nomor_anggota'       => 'CALON-' . str_pad((string) ($user->id + 100000), 6, '0', STR_PAD_LEFT),
            'nik'                 => $data['nik'],
            'nama'                => $data['nama'],
            'telp'                => $data['telp'],
            'email'               => $data['email'],
            'tempat_lahir'        => $data['tempat_lahir'] ?? null,
            'tanggal_lahir'       => $data['tanggal_lahir'],
            'jenis_kelamin'       => $data['jenis_kelamin'],
            'alamat'              => $data['alamat'],
            'kota'                => $data['kota'],
            'pekerjaan'           => $data['pekerjaan'] ?? null,
            'penghasilan_bulanan' => $data['penghasilan_bulanan'] ?? 0,
            'kategori'            => 'biasa',
            'status'              => 'calon',
            'tanggal_masuk'       => now()->toDateString(),
        ]);

        AnggotaStatusLog::create([
            'tenant_id'  => 1,
            'anggota_id' => $anggota->id,
            'dari_status' => null,
            'ke_status'  => 'calon',
            'tanggal'    => now()->toDateString(),
            'catatan'    => 'Registrasi online via /daftar',
        ]);

        return view('pendaftaran.sukses', [
            'anggota'      => $anggota,
            'tempPassword' => $tempPassword,
            'brand'        => config('pseo.brand'),
        ]);
    }
}
