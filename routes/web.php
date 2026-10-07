<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProgrammaticSeoController;
use App\Http\Controllers\RatController;
use App\Http\Controllers\SourceCodeSeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/demo', [\App\Http\Controllers\DemoController::class, 'index'])->name('demo');
Route::get('/docs', [DocsController::class, 'index'])->name('docs');

/* ===== Installer standalone (terkunci otomatis setelah selesai) ===== */
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/{step?}', [\App\Http\Controllers\InstallController::class, 'show'])
        ->where('step', '[a-z]+')->name('show');
    Route::post('/{step}', [\App\Http\Controllers\InstallController::class, 'store'])
        ->where('step', '[a-z]+')->name('store');
});

// Blog — feed.xml HARUS sebelum {slug} agar tidak tertangkap sebagai slug artikel.
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/feed.xml', function () {
    $posts = \App\Models\BlogPost::with('category')->published()->latest('published_at')->limit(20)->get();
    return response()->view('blog.feed', ['posts' => $posts])->header('Content-Type', 'application/xml');
})->name('blog.feed');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/robots.txt', [LandingController::class, 'robots']);
Route::get('/sitemap.xml', [LandingController::class, 'sitemap']);
Route::get('/sitemap{id}.xml', [LandingController::class, 'sitemapChunk'])->where('id', '[1-9][0-9]*');

/* Alias `login` route — Laravel default Authenticate middleware redirect ke
   route('login') saat user belum auth. Tanpa ini, /portal & /laporan/* error 500.
   Filament admin punya halaman login sendiri di /admin/login (sudah handled). */
Route::redirect('/login', '/portal/login')->name('login');

/* Kartu anggota dengan QR Code (printable) — pemilik atau staf berizin. */
Route::get('/anggota/{id}/kartu', function ($id) {
    $anggota = \App\Models\Anggota::findOrFail($id);
    $user = \Illuminate\Support\Facades\Auth::user();
    $milikSendiri = $user && \App\Models\Anggota::where('user_id', $user->id)->where('id', $anggota->id)->exists();
    if (! $milikSendiri && ! $user->can('anggota.view')) {
        abort(403, 'Tidak berhak melihat kartu anggota ini.');
    }
    return view('anggota.kartu', ['anggota' => $anggota]);
})->middleware('auth')->name('anggota.kartu');

/* Theme switcher — simpan pilihan tampilan admin di session */
Route::get('/admin/theme/{name}', function (string $name, \Illuminate\Http\Request $request) {
    $themes = array_keys(config('koperasi-theme.themes'));
    if (in_array($name, $themes, true)) {
        $request->session()->put('koperasi_theme', $name);
    }
    return back();
})->middleware('web')->name('admin.theme.switch');


/* ===== Document PDF (kuitansi, kontrak, slip, invoice) ===== */
Route::middleware(['auth'])->prefix('dokumen')->name('dokumen.')->group(function () {
    Route::get('/anggota-doc/{id}', [\App\Http\Controllers\DocumentController::class, 'memberDoc'])->name('anggota');
    Route::get('/kuitansi-setoran/{tx}', [\App\Http\Controllers\DocumentController::class, 'kuitansiSetoran'])->name('kuitansi');
    Route::get('/kontrak-pinjaman/{p}', [\App\Http\Controllers\DocumentController::class, 'kontrakPinjaman'])->name('kontrak');
    Route::get('/slip-cicilan/{bayar}', [\App\Http\Controllers\DocumentController::class, 'slipCicilan'])->name('slip');
    Route::get('/invoice-penjualan/{jual}', [\App\Http\Controllers\DocumentController::class, 'invoicePenjualan'])->name('invoice');
});

/* ===== Struk thermal POS — auto-print 58mm/80mm (pemilik atau kasir) ===== */
Route::get('/struk/penjualan/{id}/{size?}', function ($id, $size = '58') {
    $jual = \App\Models\TokoPenjualan::with(['anggota', 'detail.barang'])->findOrFail($id);
    $user = \Illuminate\Support\Facades\Auth::user();
    $anggotaId = $user ? \App\Models\Anggota::where('user_id', $user->id)->value('id') : null;
    $milikSendiri = $anggotaId && (int) $jual->anggota_id === (int) $anggotaId;
    if (! $milikSendiri && ! $user->can('pos.view')) {
        abort(403, 'Tidak berhak melihat struk ini.');
    }
    $width = in_array($size, ['58','80']) ? (int)$size : 58;
    return view('struk.thermal', ['jual' => $jual, 'tenant' => \App\Support\CooperativeContext::current(), 'width' => $width]);
})->middleware('auth')->name('struk.penjualan');

/* Diagnostic page — hanya admin berizin (berisi info sensitif environment). */
Route::get('/diagnose', function () {
    if (! \Illuminate\Support\Facades\Auth::user()->can('setting.view')) {
        abort(403, 'Hanya administrator.');
    }
    \Illuminate\Support\Facades\Artisan::call('koperasi:diagnose-auth');
    $output = \Illuminate\Support\Facades\Artisan::output();
    // Strip ANSI color codes
    $clean = preg_replace('/\x1b\[[0-9;]*m/', '', $output);
    return response('<!DOCTYPE html><html><head><title>Diagnostic — Koperasi App</title>' .
        '<style>body{font-family:Consolas,monospace;background:#0f172a;color:#e2e8f0;padding:2rem;line-height:1.6}' .
        'pre{white-space:pre-wrap;word-break:break-word}.ok{color:#10b981}.fail{color:#ef4444}.section{color:#06b6d4;font-weight:bold}' .
        'a{color:#10b981;text-decoration:none;font-weight:bold}a:hover{text-decoration:underline}</style></head><body>' .
        '<h1>🔬 Auth Diagnostic Report</h1><pre>' . htmlspecialchars($clean) . '</pre>' .
        '<hr><p><a href="/admin/login">→ Coba login Filament</a> · <a href="/login-admin">→ Coba login simple</a></p>' .
        '</body></html>');
})->middleware(['auth'])->name('diagnose');

/* Reset session sendiri (POST + auth + CSRF). Dipakai saat ada masalah login di browser. */
Route::post('/clear-session', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/portal/login')->with('success', 'Session dibersihkan. Silakan login ulang.');
})->middleware('auth')->name('clear.session');

/* Login admin alternatif (super-simple, plain HTML, no Filament/Livewire dep)
   — fallback kalau Filament login broken di sisi user. */
Route::get('/login-admin', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/admin');
    }
    return view('auth.simple-login');
})->name('admin.simple-login');

/* Filament admin/login fallback (non-JS) — form menyertakan @csrf normal.
   CSRF tetap aktif: login-CSRF dicegah, brute force dibatasi rate limiter. */
Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $key = 'login.' . $request->ip();
    if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
        return back()->withErrors(['email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."]);
    }

    if (\Illuminate\Support\Facades\Auth::attempt(
        $request->only('email', 'password'),
        (bool) $request->boolean('remember')
    )) {
        \Illuminate\Support\Facades\RateLimiter::clear($key);
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }

    \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
    return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->only('email'));
})
    ->middleware('throttle:10,1')
    ->name('filament.admin.auth.login.fallback');

/* ----------------------------- Programmatic SEO ---------------------------- */

// Core pages
Route::get('/aplikasi-koperasi/{kota}', [ProgrammaticSeoController::class, 'aplikasiKoperasiKota'])
    ->where('kota', '[a-z0-9-]+')->name('seo.kota');
Route::get('/jenis-koperasi/{jenis}', [ProgrammaticSeoController::class, 'jenisKoperasi'])
    ->where('jenis', '[a-z0-9-]+')->name('seo.jenis');
Route::get('/akad-syariah/{akad}', [ProgrammaticSeoController::class, 'akadSyariah'])
    ->where('akad', '[a-z0-9-]+')->name('seo.akad');
Route::get('/panduan/{slug}', [ProgrammaticSeoController::class, 'panduan'])
    ->where('slug', '[a-z0-9-]+')->name('seo.panduan');
Route::get('/kalkulator/{slug}', [ProgrammaticSeoController::class, 'kalkulator'])
    ->where('slug', '[a-z0-9-]+')->name('seo.kalkulator');
Route::get('/alternatif-{competitor}', [ProgrammaticSeoController::class, 'alternatives'])
    ->where('competitor', '[a-z0-9-]+')->name('seo.alternatives');
Route::get('/bandingkan/{slug}', [ProgrammaticSeoController::class, 'compare'])
    ->where('slug', '[a-z0-9-]+')->name('seo.compare');

// Combo: kota × jenis
Route::get('/aplikasi-koperasi/{kota}/{jenis}', [ProgrammaticSeoController::class, 'aplikasiKoperasiKotaJenis'])
    ->where('kota', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->name('seo.kota-jenis');
Route::get('/jenis-koperasi/{jenis}/di-{kota}', [ProgrammaticSeoController::class, 'jenisKoperasiKota'])
    ->where('kota', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->name('seo.jenis-kota');
// NEW: SEO-friendly short combo URL
Route::get('/{kota}/koperasi-{jenis}', [ProgrammaticSeoController::class, 'aplikasiKoperasiKotaJenis'])
    ->where('kota', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->name('seo.kota-jenis-baru');

// Combo: kota × akad
Route::get('/akad-syariah/{akad}/di/{kota}', [ProgrammaticSeoController::class, 'akadSyariahKota'])
    ->where('kota', '[a-z0-9-]+')->where('akad', '[a-z0-9-]+')->name('seo.akad-kota');

// Combo: kota × panduan
Route::get('/panduan/{slug}/di-{kota}', [ProgrammaticSeoController::class, 'panduanKota'])
    ->where('slug', '[a-z0-9-]+')->where('kota', '[a-z0-9-]+')->name('seo.panduan-kota');

Route::get('/simulasi-pinjaman', [\App\Http\Controllers\SimulatorController::class, 'show'])->name('seo.simulator');

// ===== SOURCE CODE MARKETING PSEO (30% = ~300K pages) =====
Route::get('/beli-aplikasi-koperasi', [SourceCodeSeoController::class, 'landing'])->name('seo.source-landing');
Route::get('/source-code-koperasi-{kota}', [SourceCodeSeoController::class, 'kota'])->where('kota', '[a-z0-9-]+')->name('seo.source-kota');
Route::get('/beli-aplikasi-koperasi-{jenis}', [SourceCodeSeoController::class, 'jenis'])->where('jenis', '[a-z0-9-]+')->name('seo.source-jenis');
Route::get('/source-code-koperasi-{kota}-{jenis}', [SourceCodeSeoController::class, 'kotaJenis'])->where('kota', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->name('seo.source-kota-jenis');
Route::get('/aplikasi-koperasi-{fitur}', [SourceCodeSeoController::class, 'fitur'])->where('fitur', '[a-z0-9-]+')->name('seo.source-fitur');
Route::get('/beli-aplikasi-{app}', [SourceCodeSeoController::class, 'crossSell'])->where('app', '[a-z0-9-]+')->name('seo.source-cross-sell');

// ===== KECAMATAN COMBO (massive volume: 7K+ × 6 × 12 = 500K+) =====
Route::get('/aplikasi-koperasi/kecamatan/{kecamatan}', [ProgrammaticSeoController::class, 'kecamatanPage'])
    ->where('kecamatan', '[a-z0-9-]+')->name('seo.kecamatan');
Route::get('/aplikasi-koperasi/kecamatan/{kecamatan}/{jenis}', [ProgrammaticSeoController::class, 'kecamatanJenis'])
    ->where('kecamatan', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->name('seo.kecamatan-jenis');
Route::get('/aplikasi-koperasi/kecamatan/{kecamatan}/{jenis}/{akad}', [ProgrammaticSeoController::class, 'kecamatanJenisAkad'])
    ->where('kecamatan', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->where('akad', '[a-z0-9-]+')->name('seo.kecamatan-jenis-akad');

// ===== KOTA 3-WAY COMBO =====
Route::get('/{kota}/koperasi-{jenis}-{akad}', [ProgrammaticSeoController::class, 'kotaJenisAkad'])
    ->where('kota', '[a-z0-9-]+')->where('jenis', '[a-z0-9-]+')->where('akad', '[a-z0-9-]+')->name('seo.kota-jenis-akad');

Route::get('/daftar', [\App\Http\Controllers\PendaftaranController::class, 'show'])->name('pendaftaran.show');
Route::post('/daftar', [\App\Http\Controllers\PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

Route::prefix('activation')->name('activation.')->group(function () {
    Route::get('/', [ActivationController::class, 'show'])->name('show');
    Route::post('/activate', [ActivationController::class, 'activate'])->name('activate');
    Route::post('/revoke', [ActivationController::class, 'revoke'])->name('revoke');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/verifikasi/{anggota}', [PortalController::class, 'verifikasi'])
        ->middleware(['signed', 'throttle:30,1'])->name('verifikasi');
    Route::get('/login', [PortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [PortalController::class, 'login'])->name('login.post');
    Route::get('/qr-login/{anggota}', [PortalController::class, 'qrLogin'])
        ->middleware(['signed', 'throttle:10,1'])
        ->name('qr-login');
    Route::middleware('auth')->group(function () {
        Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/simpanan', [PortalController::class, 'simpanan'])->name('simpanan');
        Route::get('/statement', [PortalController::class, 'statement'])->name('statement');
        Route::get('/pinjaman', [PortalController::class, 'pinjaman'])->name('pinjaman');
        Route::get('/transaksi', [PortalController::class, 'transaksi'])->name('transaksi');
        Route::get('/profil', [PortalController::class, 'profil'])->name('profil');
        Route::post('/profil', [PortalController::class, 'updateProfil'])->name('profil.update');

        Route::get('/pengajuan-pinjaman', [PortalController::class, 'pengajuanPinjamanForm'])->name('pengajuan-pinjaman');
        Route::post('/pengajuan-pinjaman', [PortalController::class, 'pengajuanPinjamanSubmit'])->name('pengajuan-pinjaman.submit');

        Route::get('/setoran', [PortalController::class, 'setoranForm'])->name('setoran');
        Route::post('/setoran', [PortalController::class, 'setoranSubmit'])->name('setoran.submit');

        Route::get('/ppob', [PortalController::class, 'ppob'])->name('ppob');
        Route::post('/ppob/beli', [PortalController::class, 'ppobBeli'])->name('ppob.beli');
        Route::get('/voting', [PortalController::class, 'voting'])->name('voting');
        Route::post('/voting', [PortalController::class, 'votingSubmit'])->name('voting.submit');

        Route::post('/logout', [PortalController::class, 'logout'])->name('logout');
    });
});

Route::middleware('auth')->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/neraca', [LaporanController::class, 'neraca'])->name('neraca');
    Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laba-rugi');
    Route::get('/arus-kas', [LaporanController::class, 'arusKas'])->name('arus-kas');
    Route::get('/perubahan-ekuitas', [LaporanController::class, 'perubahanEkuitas'])->name('perubahan-ekuitas');
    Route::get('/calk', [LaporanController::class, 'calk'])->name('calk');
    Route::get('/buku-besar', [LaporanController::class, 'bukuBesar'])->name('buku-besar');
    Route::get('/trial-balance', [LaporanController::class, 'trialBalance'])->name('trial-balance');
    Route::get('/aging', [LaporanController::class, 'aging'])->name('aging');
    Route::get('/ringkasan-produk', [LaporanController::class, 'ringkasanProduk'])->name('ringkasan-produk');
    Route::get('/excel/{laporan}', [LaporanController::class, 'excel'])
        ->where('laporan', 'neraca|laba-rugi|arus-kas|perubahan-ekuitas|calk|trial-balance|aging')
        ->name('excel');
});

/* ===== Report Center export (otorisasi granular di ReportRunner) ===== */
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/export/{key}/{format}', [\App\Http\Controllers\ReportExportController::class, 'export'])
        ->where('format', 'pdf|excel|csv')
        ->name('export');
});

/* ===== Import Center (otorisasi di controller; finansial perlu reports.import_financial) ===== */
Route::middleware('auth')->prefix('imports')->name('imports.')->group(function () {
    Route::get('/template/{tipe}', [\App\Http\Controllers\ImportController::class, 'template'])->name('template');
    Route::post('/upload/{tipe}', [\App\Http\Controllers\ImportController::class, 'upload'])->name('upload');
    Route::post('/confirm/{batch}', [\App\Http\Controllers\ImportController::class, 'confirm'])->name('confirm');
    Route::get('/errors/{batch}', [\App\Http\Controllers\ImportController::class, 'errorFile'])->name('errors');
});
/* ===== E-RAT: QR check-in + Buku Tahunan =====
   Check-in memakai signed URL (dibuat dari admin, kedaluwarsa 30 hari) + throttle.
   POST memvalidasi signature juga — URL publik tanpa signature valid ditolak. */
Route::prefix('rat')->name('rat.')->group(function () {
    Route::get('/{rat}/checkin', [RatController::class, 'checkin'])
        ->middleware(['signed', 'throttle:60,1'])->name('checkin');
    Route::post('/{rat}/checkin', [RatController::class, 'storeCheckin'])
        ->middleware(['signed', 'throttle:60,1'])->name('checkin.store');
    Route::get('/{rat}/buku-tahunan', [RatController::class, 'bukuTahunan'])
        ->middleware('auth')->name('buku-tahunan');
});

// License Pairing v3 routes
require base_path('routes/pair-routes.php');

// Payment webhook — menerima callback dari Midtrans, Xendit, QRIS, dll
Route::post('/webhooks/payment/{providerCode}', [PaymentWebhookController::class, 'handle'])->name('webhook.payment');
