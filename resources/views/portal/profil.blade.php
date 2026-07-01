@extends('portal.layout')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola data pribadi & password')

@section('content')
<div class="space-y-6 max-w-4xl">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-6 md:p-8">
        <div class="flex items-center gap-5 pb-6 border-b border-slate-100">
            <div class="w-20 h-20 rounded-2xl gradient-bg text-white flex items-center justify-center font-extrabold text-3xl shadow-lg shadow-emerald-500/30">
                {{ substr($anggota->nama ?? 'A', 0, 1) }}
            </div>
            <div>
                <div class="font-extrabold text-2xl text-slate-900">{{ $anggota->nama }}</div>
                <div class="text-sm text-slate-500 font-mono mt-0.5">{{ $anggota->nomor_anggota }}</div>
                <span class="inline-block mt-2 text-[10px] font-bold uppercase px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700">
                    {{ $anggota->status }} · {{ str_replace('_', ' ', $anggota->kategori ?? 'anggota biasa') }}
                </span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mt-6">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">NIK</div>
                <div class="font-mono text-slate-900">{{ $anggota->nik ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Email</div>
                <div class="text-slate-900">{{ $anggota->email }}</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Tempat / Tgl Lahir</div>
                <div class="text-slate-900">{{ $anggota->tempat_lahir }}, {{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Pekerjaan</div>
                <div class="text-slate-900">{{ $anggota->pekerjaan ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Tanggal Masuk Anggota</div>
                <div class="text-slate-900">{{ $anggota->tanggal_masuk ? \Carbon\Carbon::parse($anggota->tanggal_masuk)->translatedFormat('d F Y') : '-' }}</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Penghasilan Bulanan</div>
                <div class="text-slate-900">{{ $anggota->penghasilan_bulanan ? 'Rp ' . number_format($anggota->penghasilan_bulanan, 0, ',', '.') : '-' }}</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('portal.profil.update') }}" class="card p-6 md:p-8 space-y-5">
        @csrf
        <h3 class="font-extrabold text-lg text-slate-900">Update Kontak & Password</h3>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">No. Telepon / HP</label>
            <input name="telp" value="{{ old('telp', $anggota->telp) }}"
                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm font-mono">
            @error('telp')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Alamat Lengkap</label>
            <textarea name="alamat" rows="3"
                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">{{ old('alamat', $anggota->alamat) }}</textarea>
            @error('alamat')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="border-t border-slate-100 pt-5">
            <h4 class="font-bold text-slate-900 mb-3">Ganti Password (opsional)</h4>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" placeholder="Masukkan password saat ini"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                    @error('current_password')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div></div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Password Baru</label>
                    <input type="password" name="password" placeholder="Min. 8 karakter"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                    @error('password')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="gradient-bg text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
