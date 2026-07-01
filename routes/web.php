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
use App\Http\Controllers\SourceCodeSeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/demo', [\App\Http\Controllers\DemoController::class, 'index'])->name('demo');
Route::get('/docs', [DocsController::class, 'index'])->name('docs');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/feed.xml', function () {
    $posts = \App\Models\BlogPost::with('category')->published()->latest('published_at')->limit(20)->get();
    return response()->view('blog.feed', ['posts' => $posts])->header('Content-Type', 'application/xml');
})->name('blog.feed');

Route::get('/robots.txt', [LandingController::class, 'robots']);
Route::get('/sitemap.xml', [LandingController::class, 'sitemap']);
Route::get('/sitemap{id}.xml', [LandingController::class, 'sitemapChunk'])->where('id', '[1-9][0-9]*');

/* Alias `login` route — Laravel default Authenticate middleware redirect ke
   route('login') saat user belum auth. Tanpa ini, /portal & /laporan/* error 500.
   Filament admin punya halaman login sendiri di /admin/login (sudah handled). */
Route::redirect('/login', '/portal/login')->name('login');

/* Kartu anggota dengan QR Code (printable) */
Route::get('/anggota/{id}/kartu', function ($id) {
    $anggota = \App\Models\Anggota::findOrFail($id);
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
    Route::get('/kuitansi-setoran/{tx}', [\App\Http\Controllers\DocumentController::class, 'kuitansiSetoran'])->name('kuitansi');
    Route::get('/kontrak-pinjaman/{p}', [\App\Http\Controllers\DocumentController::class, 'kontrakPinjaman'])->name('kontrak');
    Route::get('/slip-cicilan/{bayar}', [\App\Http\Controllers\DocumentController::class, 'slipCicilan'])->name('slip');
    Route::get('/invoice-penjualan/{jual}', [\App\Http\Controllers\DocumentController::class, 'invoicePenjualan'])->name('invoice');
});

/* ===== Struk thermal POS — auto-print 58mm/80mm ===== */
Route::get('/struk/penjualan/{id}/{size?}', function ($id, $size = '58') {
    $jual = \App\Models\TokoPenjualan::with(['anggota', 'detail.barang'])->findOrFail($id);
    $width = in_array($size, ['58','80']) ? (int)$size : 58;
    return view('struk.thermal', ['jual' => $jual, 'tenant' => \App\Models\Tenant::find(1), 'width' => $width]);
})->middleware('auth')->name('struk.penjualan');

/* Diagnostic page — akses via browser untuk troubleshoot */
Route::get('/diagnose', function () {
    \Illuminate\Support\Facades\Artisan::call('koperasi:diagnose-auth');
    $output = \Illuminate\Support\Facades\Artisan::output();
    // Strip ANSI color codes
    $clean = preg_replace('/\x1b\[[0-9;]*m/', '', $output);
    return response('<!DOCTYPE html><html><head><title>Diagnostic — Koperasi App</title>' .
        '<style>body{font-family:Consolas,monospace;background:#0f172a;color:#e2e8f0;padding:2rem;line-height:1.6}' .
        'pre{white-space:pre-wrap;word-break:break-word}.ok{color:#10b981}.fail{color:#ef4444}.section{color:#06b6d4;font-weight:bold}' .
        'a{color:#10b981;text-decoration:none;font-weight:bold}a:hover{text-decoration:underline}</style></head><body>' .
        '<h1>🔬 Auth Diagnostic Report</h1><pre>' . htmlspecialchars($clean) . '</pre>' .
        '<hr><p><a href="/admin/login">→ Coba login Filament</a> · <a href="/login-admin">→ Coba login simple</a> · <a href="/clear-session">→ Clear session</a></p>' .
        '</body></html>');
})->name('diagnose');

/* Emergency: clear session & cookie (untuk fix login problem dari sisi browser) */
Route::get('/clear-session', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    \Illuminate\Support\Facades\DB::table('sessions')->delete();

    $cookies = collect($request->cookies->all())->keys();
    $response = redirect('/admin/login')->with('success', 'Session & cookie dibersihkan. Silakan login ulang.');
    foreach ($cookies as $name) {
        $response->withCookie(cookie()->forget($name));
    }
    return $response;
})->name('clear.session');

/* Login admin alternatif (super-simple, plain HTML, no Filament/Livewire dep)
   — fallback kalau Filament login broken di sisi user. */
Route::get('/login-admin', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/admin');
    }
    return view('auth.simple-login');
})->name('admin.simple-login');

/* Filament admin/login fallback (non-JS) — Livewire form pakai wire:submit
   yang submit via X-CSRF-TOKEN header. Saat JS gagal load, browser fallback
   POST native ke /admin/login tanpa _token field → tanpa route ini = 405,
   dengan VerifyCsrfToken aktif = 419 Page Expired.

   CSRF di-exempt karena: (1) login adalah entry point, belum ada session
   yang perlu dilindungi; (2) Auth::attempt + rate limiting Laravel sudah
   mencegah brute force; (3) hanya aktif sebagai fallback non-JS. */
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
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
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
    Route::get('/login', [PortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [PortalController::class, 'login'])->name('login.post');
    Route::get('/qr-login/{anggota}', [PortalController::class, 'qrLogin'])
        ->middleware('signed')
        ->name('qr-login');
    Route::middleware('auth')->group(function () {
        Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/simpanan', [PortalController::class, 'simpanan'])->name('simpanan');
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
    Route::get('/ringkasan-produk', [LaporanController::class, 'ringkasanProduk'])->name('ringkasan-produk');
    Route::get('/excel/{laporan}', [LaporanController::class, 'excel'])
        ->where('laporan', 'neraca|laba-rugi|arus-kas')
        ->name('excel');
});

// License Pairing v3 routes
require base_path('routes/pair-routes.php');

// Payment webhook — menerima callback dari Midtrans, Xendit, QRIS, dll
Route::post('/webhooks/payment/{providerCode}', [PaymentWebhookController::class, 'handle'])->name('webhook.payment');
