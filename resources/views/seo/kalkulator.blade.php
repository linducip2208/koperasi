@extends('seo._layout')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="#" class="hover:text-indigo-600">Kalkulator</a>
        <span>/</span>
        <span class="text-slate-700">{{ $kalkulator['judul'] }}</span>
    </nav>

    <header class="mb-10">
        <span class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">Kalkulator Online · Gratis</span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight text-slate-900">{{ $kalkulator['judul'] }}</h1>
        <p class="text-lg text-slate-600 leading-relaxed">{{ $kalkulator['deskripsi'] }}</p>
    </header>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-10 mb-10 shadow-sm">
        @switch($kalkulator['slug'])
            @case('cicilan-pinjaman')
                <form id="calc-form" class="space-y-5" onsubmit="return false">
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Plafon Pinjaman (Rp)</label>
                        <input type="number" id="plafon" value="10000000" class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Tenor (bulan)</label>
                        <input type="number" id="tenor" value="12" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Bunga per Tahun (%)</label>
                        <input type="number" id="bunga" value="12" step="0.1" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Metode Bunga</label>
                        <select id="metode" class="w-full rounded-xl border-slate-300 px-4 py-3">
                            <option value="flat">Flat</option>
                            <option value="efektif">Efektif</option>
                            <option value="anuitas">Anuitas</option>
                        </select>
                    </div>
                    <button onclick="hitungCicilan()" class="gradient-bg text-white font-bold w-full py-4 rounded-xl shadow-lg hover:shadow-xl transition">Hitung Cicilan</button>
                </form>
                <div id="result" class="mt-8 hidden">
                    <h3 class="font-extrabold text-xl mb-4 text-slate-900">Hasil Perhitungan</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-indigo-50 rounded-xl p-4"><div class="text-xs text-slate-600">Cicilan Bulanan</div><div id="r-cicilan" class="font-extrabold text-xl text-indigo-700"></div></div>
                        <div class="bg-purple-50 rounded-xl p-4"><div class="text-xs text-slate-600">Total Bayar</div><div id="r-total" class="font-extrabold text-xl text-purple-700"></div></div>
                    </div>
                </div>
                <script>
                function fmt(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n);}
                function hitungCicilan(){
                    const P=+document.getElementById('plafon').value, n=+document.getElementById('tenor').value, b=+document.getElementById('bunga').value/100/12, m=document.getElementById('metode').value;
                    let cicilan=0;
                    if(m==='flat'){cicilan=(P/n)+(P*b);}
                    else if(m==='anuitas'){cicilan = b===0 ? P/n : P*b/(1-Math.pow(1+b,-n));}
                    else {/* efektif rata2 */ cicilan = P/n + (P*b);}
                    const total=cicilan*n;
                    document.getElementById('r-cicilan').textContent=fmt(cicilan);
                    document.getElementById('r-total').textContent=fmt(total);
                    document.getElementById('result').classList.remove('hidden');
                }
                </script>
                @break

            @case('bagi-hasil-syariah')
                <form class="space-y-5" onsubmit="return false">
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Modal Pembiayaan (Rp)</label>
                        <input type="number" id="modal" value="50000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Nisbah Anggota (%)</label>
                        <input type="number" id="nisbah" value="60" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Proyeksi Untung Bulanan (Rp)</label>
                        <input type="number" id="untung" value="3000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <button onclick="hitungBagiHasil()" class="gradient-bg text-white font-bold w-full py-4 rounded-xl shadow-lg">Hitung Bagi Hasil</button>
                </form>
                <div id="result" class="mt-8 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-emerald-50 rounded-xl p-4"><div class="text-xs text-slate-600">Bagian Anggota/bulan</div><div id="r-anggota" class="font-extrabold text-xl text-emerald-700"></div></div>
                        <div class="bg-indigo-50 rounded-xl p-4"><div class="text-xs text-slate-600">Bagian Koperasi/bulan</div><div id="r-koperasi" class="font-extrabold text-xl text-indigo-700"></div></div>
                    </div>
                </div>
                <script>
                function fmt(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n);}
                function hitungBagiHasil(){
                    const u=+document.getElementById('untung').value, n=+document.getElementById('nisbah').value/100;
                    document.getElementById('r-anggota').textContent=fmt(u*n);
                    document.getElementById('r-koperasi').textContent=fmt(u*(1-n));
                    document.getElementById('result').classList.remove('hidden');
                }
                </script>
                @break

            @case('shu-anggota')
                <form class="space-y-5" onsubmit="return false">
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">SHU Total Koperasi (Rp)</label>
                        <input type="number" id="shu" value="100000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Persen Jasa Modal & Jasa Usaha (masing-masing %)</label>
                        <input type="number" id="persen" value="25" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Simpanan Anggota Ini (Rp)</label>
                        <input type="number" id="sa" value="5000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Total Simpanan Semua Anggota (Rp)</label>
                        <input type="number" id="ts" value="500000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Transaksi Anggota Ini (Rp)</label>
                        <input type="number" id="ta" value="3000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Total Transaksi Semua Anggota (Rp)</label>
                        <input type="number" id="tt" value="100000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <button onclick="hitungShu()" class="gradient-bg text-white font-bold w-full py-4 rounded-xl shadow-lg">Hitung SHU Anggota</button>
                </form>
                <div id="result" class="mt-8 hidden space-y-3">
                    <div class="bg-indigo-50 rounded-xl p-4 flex justify-between items-center"><span class="text-sm">Jasa Modal</span><span id="r-jm" class="font-extrabold text-indigo-700"></span></div>
                    <div class="bg-purple-50 rounded-xl p-4 flex justify-between items-center"><span class="text-sm">Jasa Usaha</span><span id="r-ju" class="font-extrabold text-purple-700"></span></div>
                    <div class="bg-emerald-100 rounded-xl p-4 flex justify-between items-center"><span class="font-bold">SHU Total Anggota</span><span id="r-total" class="font-extrabold text-xl text-emerald-700"></span></div>
                </div>
                <script>
                function fmt(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n);}
                function hitungShu(){
                    const shu=+document.getElementById('shu').value, p=+document.getElementById('persen').value/100;
                    const sa=+document.getElementById('sa').value, ts=+document.getElementById('ts').value;
                    const ta=+document.getElementById('ta').value, tt=+document.getElementById('tt').value;
                    const jm = ts>0 ? (sa/ts)*(shu*p) : 0;
                    const ju = tt>0 ? (ta/tt)*(shu*p) : 0;
                    document.getElementById('r-jm').textContent=fmt(jm);
                    document.getElementById('r-ju').textContent=fmt(ju);
                    document.getElementById('r-total').textContent=fmt(jm+ju);
                    document.getElementById('result').classList.remove('hidden');
                }
                </script>
                @break

            @case('simpanan-berjangka')
                <form class="space-y-5" onsubmit="return false">
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Setoran Awal (Rp)</label>
                        <input type="number" id="nominal" value="10000000" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Tenor (bulan)</label>
                        <input type="number" id="tenor" value="12" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <div>
                        <label class="font-semibold text-slate-900 mb-1 block">Bunga/Bagi Hasil per Tahun (%)</label>
                        <input type="number" id="bunga" value="6" step="0.1" class="w-full rounded-xl border-slate-300 px-4 py-3" />
                    </div>
                    <button onclick="hitungBerjangka()" class="gradient-bg text-white font-bold w-full py-4 rounded-xl shadow-lg">Proyeksi Hasil</button>
                </form>
                <div id="result" class="mt-8 hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-indigo-50 rounded-xl p-4"><div class="text-xs text-slate-600">Total Bunga/Bagi Hasil</div><div id="r-bunga" class="font-extrabold text-xl text-indigo-700"></div></div>
                        <div class="bg-emerald-50 rounded-xl p-4"><div class="text-xs text-slate-600">Total Akhir</div><div id="r-akhir" class="font-extrabold text-xl text-emerald-700"></div></div>
                    </div>
                </div>
                <script>
                function fmt(n){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n);}
                function hitungBerjangka(){
                    const N=+document.getElementById('nominal').value, t=+document.getElementById('tenor').value, b=+document.getElementById('bunga').value/100;
                    const totalBunga = N*b*(t/12);
                    document.getElementById('r-bunga').textContent=fmt(totalBunga);
                    document.getElementById('r-akhir').textContent=fmt(N+totalBunga);
                    document.getElementById('result').classList.remove('hidden');
                }
                </script>
                @break
        @endswitch
    </div>

    <section class="bg-white rounded-3xl border border-slate-200 p-8 md:p-10 mb-10">
        <h2 class="text-2xl font-extrabold mb-4 text-slate-900">Tentang {{ $kalkulator['judul'] }}</h2>
        <div class="prose-custom">
            <p>Kalkulator ini disediakan gratis oleh tim {{ $brand['nama'] }} sebagai alat bantu pengurus dan calon anggota koperasi untuk simulasi cepat. Hasil perhitungan bersifat estimasi — angka final tergantung kebijakan masing-masing koperasi (biaya admin, asuransi, denda, dan ketentuan AD/ART).</p>
            <p>Untuk perhitungan resmi yang masuk ke laporan keuangan koperasi Anda, gunakan engine perhitungan built-in {{ $brand['nama'] }} yang sudah ter-validasi oleh praktisi koperasi dan auditor PSAK 27. Engine tersebut juga otomatis menjurnal akuntansi, kirim notifikasi ke anggota via WhatsApp, dan masuk ke laporan SHU akhir tahun.</p>
            <p>Catatan penting: untuk koperasi syariah, hindari pakai metode "bunga flat/efektif/anuitas" karena masuk kategori riba — gunakan akad murabahah (margin tetap), mudharabah (bagi hasil), atau ijarah (sewa) sesuai konteks pembiayaan. Pelajari lebih lanjut di <a href="{{ route('seo.akad', 'mudharabah') }}" class="text-indigo-600 underline font-semibold">akad mudharabah</a> atau <a href="{{ route('seo.akad', 'murabahah') }}" class="text-indigo-600 underline font-semibold">akad murabahah</a>.</p>
        </div>
    </section>

    @include('seo._cta')

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Kalkulator Lainnya</h2>
        <div class="grid md:grid-cols-2 gap-5">
            @foreach($kalkulatorLain as $k)
                <a href="{{ route('seo.kalkulator', $k['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-6 block">
                    <h3 class="font-extrabold text-base mb-2 text-slate-900">{{ $k['judul'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $k['deskripsi'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
