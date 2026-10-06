<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Installer — {{ config('product.name') }}</title>
@vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-2xl">
    <div class="text-center mb-2">
        <div class="text-4xl mb-2">🏭</div>
        <h1 class="text-2xl font-extrabold text-slate-800">{{ config('product.name') }} v{{ config('product.version') }}</h1>
        <p class="text-sm text-slate-500">Installer standalone — 1 instalasi = 1 koperasi</p>
    </div>

    <div class="flex flex-wrap gap-1 justify-center my-6">
        @foreach($steps as $i => $s)
            <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ $s === $step ? 'bg-emerald-600 text-white' : (array_search($s, $steps) < array_search($step, $steps) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400') }}">{{ $i + 1 }}. {{ ucfirst($s) }}</span>
        @endforeach
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    @if($step === 'welcome')
        <div class="text-sm text-slate-600 space-y-3">
            <p>Selamat datang. Installer ini menyiapkan <strong>database, profil koperasi, akun admin, dan lisensi</strong> dalam sekali jalan.</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Pastikan PHP 8.2+, ekstensi lengkap, dan folder <code>storage/</code> writable.</li>
                <li>Siapkan database kosong (SQLite file atau MySQL).</li>
                <li>Setelah selesai, installer <strong>terkunci otomatis</strong> dan tidak bisa diakses lagi.</li>
            </ul>
        </div>
        <a href="{{ route('install.show', 'requirements') }}" class="mt-6 block text-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Mulai →</a>

    @elseif($step === 'requirements')
        <table class="w-full text-sm mb-4">
            @foreach($checks as $c)
                <tr class="border-b"><td class="py-2">{{ $c['label'] }}</td><td class="py-2 text-right font-bold {{ $c['ok'] ? 'text-emerald-600' : 'text-red-600' }}">{{ $c['ok'] ? '✅ OK' : '❌ Gagal' }}</td></tr>
            @endforeach
        </table>
        @php $fail = collect($checks)->contains(fn ($c) => ! $c['ok']); @endphp
        @if($fail)
            <p class="text-sm text-red-600 font-semibold">Perbaiki item yang gagal di server, lalu refresh halaman ini.</p>
        @else
            <a href="{{ route('install.show', 'database') }}" class="mt-2 block text-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Lanjut: Database →</a>
        @endif

    @elseif($step === 'database')
        <form method="POST" action="{{ route('install.store', 'database') }}" class="space-y-3 text-sm">
            @csrf
            <label class="font-bold">Koneksi</label>
            <select name="db_connection" class="w-full border rounded-xl px-3 py-2">
                <option value="sqlite" {{ ($data['database']['db_connection'] ?? '') === 'sqlite' ? 'selected' : '' }}>SQLite (file lokal)</option>
                <option value="mysql" {{ ($data['database']['db_connection'] ?? '') === 'mysql' ? 'selected' : '' }}>MySQL / MariaDB</option>
            </select>
            <label class="font-bold">Database (nama DB / nama file sqlite tanpa .sqlite)</label>
            <input name="db_database" value="{{ $data['database']['db_database'] ?? 'koperasi' }}" class="w-full border rounded-xl px-3 py-2" required>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="font-bold">Host (MySQL)</label><input name="db_host" value="{{ $data['database']['db_host'] ?? '127.0.0.1' }}" class="w-full border rounded-xl px-3 py-2"></div>
                <div><label class="font-bold">Port (MySQL)</label><input name="db_port" value="{{ $data['database']['db_port'] ?? '3306' }}" class="w-full border rounded-xl px-3 py-2"></div>
                <div><label class="font-bold">Username (MySQL)</label><input name="db_username" value="{{ $data['database']['db_username'] ?? '' }}" class="w-full border rounded-xl px-3 py-2"></div>
                <div><label class="font-bold">Password (MySQL)</label><input type="password" name="db_password" class="w-full border rounded-xl px-3 py-2"></div>
            </div>
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Test koneksi & simpan →</button>
        </form>

    @elseif($step === 'application')
        <form method="POST" action="{{ route('install.store', 'application') }}" class="space-y-3 text-sm">
            @csrf
            <label class="font-bold">Nama aplikasi (browser title)</label>
            <input name="app_name" value="{{ $data['application']['app_name'] ?? config('product.name') }}" class="w-full border rounded-xl px-3 py-2" required>
            <label class="font-bold">App URL</label>
            <input name="app_url" value="{{ $data['application']['app_url'] ?? config('app.url') }}" class="w-full border rounded-xl px-3 py-2" required>
            <label class="font-bold">Zona waktu</label>
            <select name="timezone" class="w-full border rounded-xl px-3 py-2">
                @foreach(['Asia/Jakarta' => 'WIB', 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT'] as $tz => $label)
                    <option value="{{ $tz }}" {{ ($data['application']['timezone'] ?? '') === $tz ? 'selected' : '' }}>{{ $label }} ({{ $tz }})</option>
                @endforeach
            </select>
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Simpan →</button>
        </form>

    @elseif($step === 'cooperative')
        <form method="POST" action="{{ route('install.store', 'cooperative') }}" class="space-y-3 text-sm">
            @csrf
            <label class="font-bold">Nama koperasi</label>
            <input name="nama" value="{{ $data['cooperative']['nama'] ?? '' }}" class="w-full border rounded-xl px-3 py-2" required>
            <label class="font-bold">Mode operasi</label>
            <select name="operation_mode" class="w-full border rounded-xl px-3 py-2">
                @foreach(['konvensional' => 'Konvensional', 'syariah' => 'Syariah', 'dual' => 'Dual'] as $m => $label)
                    <option value="{{ $m }}" {{ ($data['cooperative']['operation_mode'] ?? '') === $m ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="font-bold">Telepon</label><input name="telp" value="{{ $data['cooperative']['telp'] ?? '' }}" class="w-full border rounded-xl px-3 py-2"></div>
                <div><label class="font-bold">Email</label><input name="email" type="email" value="{{ $data['cooperative']['email'] ?? '' }}" class="w-full border rounded-xl px-3 py-2"></div>
            </div>
            <label class="font-bold">Alamat</label>
            <textarea name="alamat" class="w-full border rounded-xl px-3 py-2" rows="2">{{ $data['cooperative']['alamat'] ?? '' }}</textarea>
            <label class="font-bold">Tahun buku</label>
            <input name="tahun_buku" type="number" value="{{ $data['cooperative']['tahun_buku'] ?? date('Y') }}" class="w-full border rounded-xl px-3 py-2" required>
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Simpan →</button>
        </form>

    @elseif($step === 'admin')
        <form method="POST" action="{{ route('install.store', 'admin') }}" class="space-y-3 text-sm">
            @csrf
            <label class="font-bold">Nama administrator</label>
            <input name="name" value="{{ $data['admin']['name'] ?? '' }}" class="w-full border rounded-xl px-3 py-2" required>
            <label class="font-bold">Email login</label>
            <input name="email" type="email" value="{{ $data['admin']['email'] ?? '' }}" class="w-full border rounded-xl px-3 py-2" required>
            <label class="font-bold">Password (min. 12 karakter)</label>
            <input name="password" type="password" class="w-full border rounded-xl px-3 py-2" required minlength="12">
            <label class="font-bold">Konfirmasi password</label>
            <input name="password_confirmation" type="password" class="w-full border rounded-xl px-3 py-2" required minlength="12">
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Simpan →</button>
        </form>

    @elseif($step === 'license')
        <div class="text-sm text-slate-600 space-y-3">
            <p>Setelah database + admin dibuat, <strong>aktivasi lisensi</strong> di halaman pairing resmi. Klik tombol di bawah untuk menjalankan migrasi + seed dasar, lalu Anda diarahkan ke aktivasi.</p>
        </div>
        <form method="POST" action="{{ route('install.store', 'license') }}">
            @csrf
            <button class="mt-4 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl">Jalankan instalasi →</button>
        </form>

    @elseif($step === 'finish')
        <div class="text-center py-6">
            <div class="text-6xl mb-3">🎉</div>
            <h2 class="text-xl font-extrabold text-emerald-700">Instalasi selesai & terkunci</h2>
            <p class="text-sm text-slate-500 mt-2">Installer tidak bisa diakses lagi. Langkah berikutnya: aktivasi lisensi.</p>
            <div class="flex gap-2 justify-center mt-6">
                <a href="/__pair" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl">Aktivasi Lisensi →</a>
                <a href="/admin/login" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-6 py-3 rounded-xl">Login Admin</a>
            </div>
        </div>
    @endif
</div>
</body>
</html>
