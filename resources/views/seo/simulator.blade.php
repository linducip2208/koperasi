<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Simulator Pinjaman Koperasi — Hitung Cicilan, Bunga & Margin Syariah | {{ $brand['nama'] ?? config('app.name') }}</title>
<meta name="description" content="Simulator cicilan pinjaman koperasi gratis. Hitung jadwal angsuran lengkap untuk pinjaman konvensional (flat, efektif, anuitas) atau syariah (murabahah, mudharabah, ijarah, dll).">
<meta name="keywords" content="simulator pinjaman koperasi, kalkulator cicilan, simulasi murabahah, hitung bunga koperasi">
<link rel="canonical" href="{{ url('/simulasi-pinjaman') }}">
<meta property="og:title" content="Simulator Pinjaman Koperasi — Free">
<meta property="og:description" content="Hitung cicilan koperasi konvensional & syariah dengan akurat. Gratis, tanpa daftar.">
<meta property="og:type" content="website">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebApplication',
    'name'     => 'Simulator Pinjaman Koperasi',
    'description' => 'Kalkulator cicilan pinjaman koperasi konvensional dan syariah dengan jadwal angsuran lengkap.',
    'applicationCategory' => 'FinanceApplication',
    'operatingSystem' => 'Any',
    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'IDR'],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">

<header class="bg-gradient-to-br from-emerald-600 to-cyan-600 text-white">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <a href="/" class="text-sm opacity-80 hover:opacity-100">← Beranda</a>
        <h1 class="text-4xl md:text-5xl font-extrabold mt-3">🧮 Simulator Pinjaman Koperasi</h1>
        <p class="text-lg opacity-95 mt-3 max-w-3xl">Hitung cicilan pinjaman dengan 12 metode lengkap — Konvensional (flat, efektif, anuitas) hingga akad syariah (murabahah, mudharabah, ijarah, qardh, dll). Gratis, instan, tanpa daftar.</p>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-10">

    <form method="GET" action="{{ url('/simulasi-pinjaman') }}" class="bg-white rounded-2xl shadow-md p-6 grid md:grid-cols-4 gap-4">
        <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Plafon</label>
            <div class="relative mt-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">Rp</span>
                <input type="number" name="plafon" value="{{ $plafon }}" min="100000" step="100000"
                    class="w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>
        <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Tenor (bulan)</label>
            <input type="number" name="tenor" value="{{ $tenor }}" min="1" max="360"
                class="w-full px-3 py-2.5 mt-1 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Bunga/Margin %/thn</label>
            <input type="number" name="rate" value="{{ $rate }}" step="0.01" min="0"
                class="w-full px-3 py-2.5 mt-1 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Metode</label>
            <select name="metode" class="w-full px-3 py-2.5 mt-1 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                @foreach($methods as $group => $items)
                    <optgroup label="{{ $group }}">
                        @foreach($items as $key => $label)
                            <option value="{{ $key }}" @selected($metode === $key)>{{ $label }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-lg shadow">
                🔍 Hitung Cicilan
            </button>
        </div>
    </form>

    @if($error)
        <div class="mt-4 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-lg">{{ $error }}</div>
    @endif

    @if($jadwal)
        <div class="grid md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white border border-emerald-200 rounded-2xl p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold">Total Pokok</div>
                <div class="text-2xl font-extrabold text-emerald-700 mt-1">Rp {{ number_format($totalPokok, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wider text-slate-500 font-bold">Total Margin/Bunga</div>
                <div class="text-2xl font-extrabold text-amber-700 mt-1">Rp {{ number_format($totalMargin, 0, ',', '.') }}</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-600 to-cyan-600 text-white rounded-2xl p-5 shadow-md">
                <div class="text-xs uppercase tracking-wider opacity-80 font-bold">Total Bayar</div>
                <div class="text-2xl font-extrabold mt-1">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                <div class="text-xs opacity-80 mt-1">Cicilan ~Rp {{ number_format($tenor > 0 ? $totalBayar / $tenor : 0, 0, ',', '.') }}/bulan</div>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-2xl shadow-sm overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-emerald-50">
                    <tr class="text-left text-xs uppercase tracking-wider text-emerald-800">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Pokok</th>
                        <th class="px-4 py-3">Margin/Bunga</th>
                        <th class="px-4 py-3">Cicilan</th>
                        <th class="px-4 py-3">Sisa Pokok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $row)
                        <tr class="border-t border-slate-100 hover:bg-emerald-50/30">
                            <td class="px-4 py-2.5 font-bold text-slate-700">{{ $row['angsuran_ke'] }}</td>
                            <td class="px-4 py-2.5">Rp {{ number_format($row['pokok'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-amber-700">Rp {{ number_format($row['margin'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 font-bold">Rp {{ number_format($row['total'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-slate-500">Rp {{ number_format($row['saldo_pokok'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <section class="mt-12 prose max-w-none">
        <h2 class="text-2xl font-bold text-slate-900">Cara Pakai Simulator Pinjaman Koperasi</h2>
        <p class="text-slate-600">Simulator ini membantu calon peminjam memperkirakan cicilan bulanan sebelum mengajukan ke koperasi. Cocok untuk koperasi simpan-pinjam (KSP) maupun syariah (KSPPS).</p>
        <h3 class="text-lg font-bold mt-6">12 Metode Perhitungan</h3>
        <ul class="list-disc pl-6 text-slate-700 space-y-1">
            <li><strong>Flat:</strong> Bunga dihitung dari pokok awal — angsuran tetap tiap bulan</li>
            <li><strong>Efektif (Menurun):</strong> Bunga dihitung dari sisa pokok — angsuran menurun</li>
            <li><strong>Anuitas:</strong> Total angsuran tetap, komposisi pokok &amp; bunga berubah</li>
            <li><strong>Murabahah:</strong> Jual-beli syariah dengan margin tetap</li>
            <li><strong>Mudharabah:</strong> Bagi hasil sesuai nisbah</li>
            <li><strong>Ijarah / IMBT:</strong> Sewa atau sewa-beli syariah</li>
            <li><strong>Qardh:</strong> Pinjaman kebajikan tanpa imbalan (admin only)</li>
            <li><strong>Rahn:</strong> Gadai syariah dengan ujrah pemeliharaan</li>
            <li><strong>Wakalah / Kafalah / Hawalah:</strong> Akad jasa &amp; pengalihan</li>
        </ul>

        <h2 class="text-2xl font-bold text-slate-900 mt-10">Mau Pakai Software Koperasi Lengkap?</h2>
        <p class="text-slate-600">Simulator ini hanya 1 fitur kecil. {{ $brand['nama'] ?? 'KoperasiApp' }} adalah software koperasi siap pakai dengan 19 modul — anggota, simpan-pinjam, akuntansi PSAK 27, RAT, SHU, toko POS, dan masih banyak lagi.</p>
        <a href="https://wa.me/6281296052010?text=Halo%20saya%20mau%20tanya%20software%20Koperasi"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-lg shadow no-underline">
            💬 Tanya Sales: 0812-9605-2010
        </a>
    </section>
</main>

<footer class="bg-slate-900 text-slate-400 py-8 mt-16">
    <div class="max-w-6xl mx-auto px-4 text-center text-sm">
        © {{ date('Y') }} {{ $brand['nama'] ?? config('app.name') }}. Simulator gratis tanpa registrasi.
    </div>
</footer>

</body>
</html>
