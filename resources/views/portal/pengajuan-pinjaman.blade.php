@extends('portal.layout')

@section('title', 'Ajukan Pinjaman')
@section('page-title', 'Pengajuan Pinjaman')
@section('page-subtitle', 'Ajukan pinjaman online dengan mudah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card-gradient p-6 relative overflow-hidden">
        <div class="blob bg-emerald-300 w-72 h-72 -top-20 -right-20"></div>
        <div class="relative">
            <div class="text-emerald-50 text-xs font-bold uppercase tracking-wider mb-2">Pengajuan Pinjaman Online</div>
            <h2 class="font-extrabold text-2xl">Ajukan Pinjaman dalam 3 Menit</h2>
            <p class="text-emerald-50 text-sm mt-2">Isi form di bawah, admin akan verifikasi dalam 1x24 jam kerja.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('portal.pengajuan-pinjaman.submit') }}" class="card p-6 md:p-8 space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pilih Produk Pinjaman</label>
            <select name="produk_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                <option value="">-- Pilih Produk --</option>
                @foreach($produk as $p)
                    <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} ({{ $p->akad_type ?? 'konvensional' }}) — Bunga {{ $p->bunga_persen ?? 12 }}%
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Plafon (Rp)</label>
                <input type="number" name="plafon" min="500000" max="500000000" step="100000" required
                    value="{{ old('plafon', 5000000) }}"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 font-mono">
                <div class="text-[11px] text-slate-500 mt-1">Min Rp 500.000 · Max Rp 500.000.000</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tenor (bulan)</label>
                <input type="number" name="tenor" min="1" max="60" required
                    value="{{ old('tenor', 12) }}"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 font-mono">
                <div class="text-[11px] text-slate-500 mt-1">Max 60 bulan (5 tahun)</div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Tujuan Pinjaman</label>
            <textarea name="tujuan" rows="3" required minlength="10"
                placeholder="Contoh: Modal usaha warung makan, untuk membeli peralatan dapur dan stok bahan baku awal..."
                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">{{ old('tujuan') }}</textarea>
            <div class="text-[11px] text-slate-500 mt-1">Min 10 karakter. Sebutkan tujuan secara spesifik agar pengajuan cepat disetujui.</div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
            <strong>📋 Catatan:</strong> Pengajuan ini akan masuk ke admin untuk verifikasi. Anda akan menerima notifikasi via WhatsApp dan dashboard saat status berubah (Pengajuan → Approved → Cair).
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('portal.dashboard') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-4 py-3">Batal</a>
            <button type="submit" class="gradient-bg text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                Kirim Pengajuan →
            </button>
        </div>
    </form>

</div>
@endsection
