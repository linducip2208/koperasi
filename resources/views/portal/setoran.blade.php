@extends('portal.layout')

@section('title', 'Setor Simpanan')
@section('page-title', 'Setor Simpanan Online')
@section('page-subtitle', 'Submit setoran, admin akan verifikasi')

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
            <div class="text-emerald-50 text-xs font-bold uppercase tracking-wider mb-2">Setoran Simpanan Online</div>
            <h2 class="font-extrabold text-2xl">Setor Simpanan dari Mana Saja</h2>
            <p class="text-emerald-50 text-sm mt-2">Transfer ke rekening koperasi, lalu submit form ini agar admin verifikasi.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('portal.setoran.submit') }}" class="card p-6 md:p-8 space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pilih Rekening Simpanan</label>
            <select name="simpanan_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                <option value="">-- Pilih Rekening --</option>
                @foreach($simpanan as $s)
                    <option value="{{ $s->id }}" {{ old('simpanan_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->produk?->nama ?? 'Simpanan' }} — {{ $s->nomor_rekening }} (saldo: Rp {{ number_format($s->saldo, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nominal Setoran (Rp)</label>
                <input type="number" name="jumlah" min="10000" max="100000000" step="10000" required
                    value="{{ old('jumlah', 100000) }}"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 font-mono">
                <div class="text-[11px] text-slate-500 mt-1">Min Rp 10.000</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Metode Pembayaran</label>
                <select name="metode_bayar" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">
                    <option value="transfer" {{ old('metode_bayar') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="tunai" {{ old('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai (datang ke kantor)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Catatan / Bukti Transfer</label>
            <textarea name="keterangan" rows="2"
                placeholder="Contoh: Transfer dari BCA 1234567890 a/n Budi Santoso, ref: TRX2026042801"
                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring focus:ring-emerald-200 px-4 py-3 text-sm">{{ old('keterangan') }}</textarea>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800 space-y-2">
            <strong>💳 Rekening Penerima:</strong>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <div>
                    <div class="text-[10px] uppercase tracking-wider text-blue-700 font-bold">BCA</div>
                    <div class="font-mono font-bold">1234 5678 90</div>
                    <div class="text-xs">a.n. KOPERASI APP</div>
                </div>
                <div>
                    <div class="text-[10px] uppercase tracking-wider text-blue-700 font-bold">Mandiri</div>
                    <div class="font-mono font-bold">9876 5432 10</div>
                    <div class="text-xs">a.n. KOPERASI APP</div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('portal.simpanan') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-4 py-3">Batal</a>
            <button type="submit" class="gradient-bg text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                Submit Setoran →
            </button>
        </div>
    </form>

</div>
@endsection
