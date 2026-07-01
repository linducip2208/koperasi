<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\PinjamanPembayaran;
use App\Models\SimpananTransaksi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PortalController extends Controller
{
    public function showLogin(): View
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        RateLimiter::hit($throttleKey, 300);

        $remember = $request->boolean('remember');

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return redirect()->intended(route('portal.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function qrLogin(Request $request, Anggota $anggota)
    {
        if (!$anggota->user_id) {
            return redirect()->route('portal.login')
                ->withErrors(['email' => 'Akun anggota belum aktif. Hubungi admin koperasi.']);
        }

        Auth::loginUsingId($anggota->user_id);
        $request->session()->regenerate();

        return redirect()->route('portal.dashboard')
            ->with('flash', '✅ Selamat datang, ' . $anggota->nama . '! Anda login via QR Kartu Anggota.');
    }

    public function dashboard(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);

        return view('portal.dashboard', [
            'anggota'       => $anggota,
            'simpanan'      => $anggota->simpanan()->with('produk')->get(),
            'pinjaman'      => $anggota->pinjaman()->with('produk')->whereIn('status', ['aktif', 'macet', 'cair'])->get(),
            'totalTabungan' => $anggota->totalSimpanan(),
        ]);
    }

    public function simpanan(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $simpanan = $anggota->simpanan()->with('produk')->get();
        $simpananIds = $simpanan->pluck('id');

        $transaksi = SimpananTransaksi::whereIn('simpanan_id', $simpananIds)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(20);

        return view('portal.simpanan', [
            'anggota'   => $anggota,
            'simpanan'  => $simpanan,
            'transaksi' => $transaksi,
            'total'     => $simpanan->sum('saldo'),
        ]);
    }

    public function pinjaman(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);

        $pinjaman = $anggota->pinjaman()
            ->with(['produk', 'jadwal' => fn ($q) => $q->orderBy('angsuran_ke')])
            ->orderByDesc('tanggal_pencairan')
            ->get();

        return view('portal.pinjaman', [
            'anggota'  => $anggota,
            'pinjaman' => $pinjaman,
        ]);
    }

    public function transaksi(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $simpananIds = $anggota->simpanan()->pluck('id');
        $pinjamanIds = $anggota->pinjaman()->pluck('id');

        $simpananTrx = SimpananTransaksi::whereIn('simpanan_id', $simpananIds)
            ->select('id', 'tanggal', 'jenis', 'jumlah', 'keterangan')
            ->selectRaw("'simpanan' as kategori")
            ->get();

        $pinjamanTrx = PinjamanPembayaran::whereIn('pinjaman_id', $pinjamanIds)
            ->select('id', 'tanggal', 'jenis', 'total_bayar as jumlah', 'keterangan')
            ->selectRaw("'pinjaman' as kategori")
            ->get();

        $semuaTransaksi = $simpananTrx->concat($pinjamanTrx)
            ->sortByDesc('tanggal')
            ->values()
            ->take(50);

        return view('portal.transaksi', [
            'anggota'    => $anggota,
            'transaksi'  => $semuaTransaksi,
        ]);
    }

    public function profil(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);

        return view('portal.profil', [
            'anggota' => $anggota,
        ]);
    }

    public function updateProfil(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $validated = $request->validate([
            'telp'    => ['nullable', 'string', 'max:20'],
            'alamat'  => ['nullable', 'string', 'max:500'],
            'current_password' => ['required_with:password', 'string', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $anggota->update([
            'telp'   => $validated['telp']   ?? $anggota->telp,
            'alamat' => $validated['alamat'] ?? $anggota->alamat,
        ]);

        if (!empty($validated['password'])) {
            $request->user()->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('portal.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login');
    }

    /* ============= Pengajuan Pinjaman Online ============= */

    public function pengajuanPinjamanForm(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $produk = \App\Models\ProdukPinjaman::where('aktif', true)->orderBy('nama')->get();

        return view('portal.pengajuan-pinjaman', [
            'anggota' => $anggota,
            'produk'  => $produk,
        ]);
    }

    public function pengajuanPinjamanSubmit(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $validated = $request->validate([
            'produk_id' => ['required', 'exists:produk_pinjaman,id'],
            'plafon'    => ['required', 'numeric', 'min:500000', 'max:500000000'],
            'tenor'     => ['required', 'integer', 'min:1', 'max:60'],
            'tujuan'    => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $produk = \App\Models\ProdukPinjaman::where('id', $validated['produk_id'])->where('aktif', true)->first();

        if (!$produk) {
            return back()->withErrors(['produk_id' => 'Produk pinjaman tidak tersedia.'])->withInput();
        }

        $plafon = (int) $validated['plafon'];
        $tenor = (int) $validated['tenor'];

        if ($produk->plafon_minimum && $plafon < $produk->plafon_minimum) {
            return back()->withErrors(['plafon' => 'Plafon minimal Rp ' . number_format($produk->plafon_minimum, 0, ',', '.')])->withInput();
        }
        if ($produk->plafon_maksimum && $plafon > $produk->plafon_maksimum) {
            return back()->withErrors(['plafon' => 'Plafon maksimal Rp ' . number_format($produk->plafon_maksimum, 0, ',', '.')])->withInput();
        }
        if ($produk->tenor_minimum && $tenor < $produk->tenor_minimum) {
            return back()->withErrors(['tenor' => 'Tenor minimal ' . $produk->tenor_minimum . ' bulan'])->withInput();
        }
        if ($produk->tenor_maksimum && $tenor > $produk->tenor_maksimum) {
            return back()->withErrors(['tenor' => 'Tenor maksimal ' . $produk->tenor_maksimum . ' bulan'])->withInput();
        }

        $bunga = (float) ($produk->bunga_persen ?? 12);
        $marginTotal = (int) ($plafon * ($bunga / 100) * ($tenor / 12));

        $nextId = \App\Models\Pinjaman::max('id') + 1;
        \App\Models\Pinjaman::create([
            'tenant_id'         => $anggota->tenant_id,
            'anggota_id'        => $anggota->id,
            'produk_id'         => $produk->id,
            'nomor_akad'        => 'PJM-' . str_pad((string) $nextId, 8, '0', STR_PAD_LEFT),
            'tanggal_pengajuan' => now()->toDateString(),
            'plafon'            => $plafon,
            'pokok'             => $plafon,
            'margin_total'      => $marginTotal,
            'total_bayar'       => $plafon + $marginTotal,
            'tenor'              => $tenor,
            'bunga_persen'      => $bunga,
            'saldo_pokok'       => $plafon,
            'saldo_margin'      => $marginTotal,
            'tujuan'            => $validated['tujuan'],
            'status'            => 'pengajuan',
        ]);

        return redirect()->route('portal.pinjaman')->with('success', 'Pengajuan pinjaman berhasil dikirim. Tunggu verifikasi admin.');
    }

    /* ============= Setoran Simpanan Online ============= */

    public function setoranForm(Request $request): View
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $simpanan = $anggota->simpanan()->with('produk')->where('status', 'aktif')->get();

        return view('portal.setoran', [
            'anggota'  => $anggota,
            'simpanan' => $simpanan,
        ]);
    }

    public function setoranSubmit(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $validated = $request->validate([
            'simpanan_id' => ['required', 'exists:simpanan,id'],
            'jumlah'      => ['required', 'numeric', 'min:10000', 'max:100000000'],
            'metode_bayar' => ['required', 'in:tunai,transfer'],
            'keterangan'  => ['nullable', 'string', 'max:255'],
        ]);

        $simpanan = \App\Models\Simpanan::where('id', $validated['simpanan_id'])
            ->where('anggota_id', $anggota->id)
            ->firstOrFail();

        $nextId = \App\Models\SimpananTransaksi::max('id') + 1;
        \App\Models\SimpananTransaksi::create([
            'tenant_id'     => $anggota->tenant_id,
            'simpanan_id'   => $simpanan->id,
            'nomor'         => 'TRX-' . str_pad((string) $nextId, 8, '0', STR_PAD_LEFT),
            'tanggal'       => now()->toDateString(),
            'jenis'         => 'setor',
            'jumlah'        => (int) $validated['jumlah'],
            'saldo_sebelum' => $simpanan->saldo,
            'saldo_sesudah' => $simpanan->saldo, // belum ditambah, menunggu admin approve
            'metode_bayar'  => $validated['metode_bayar'],
            'keterangan'   => '[Pengajuan Online] ' . ($validated['keterangan'] ?? ''),
        ]);

        return redirect()->route('portal.simpanan')->with('success', 'Pengajuan setoran berhasil dikirim. Tunggu verifikasi admin (max 1x24 jam).');
    }

    private function getAnggotaOrAbort(Request $request): Anggota
    {
        $user = $request->user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        if (!$anggota) {
            abort(403, 'Akun ini bukan akun anggota.');
        }
        return $anggota;
    }

    public function ppob(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        return view('portal.ppob', compact('anggota'));
    }

    public function ppobBeli(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $request->validate(['ppob_produk_id' => 'required|exists:ppob_produk,id', 'no_tujuan' => 'required|string|max:30']);
        $produk = \App\Models\PpobProduk::findOrFail($request->ppob_produk_id);
        \App\Models\PpobTransaksi::create([
            'tenant_id' => $anggota->tenant_id, 'anggota_id' => $anggota->id, 'ppob_produk_id' => $produk->id,
            'nomor' => 'PPOB-' . now()->format('Ymd-His') . '-' . rand(100, 999),
            'no_tujuan' => $request->no_tujuan, 'harga' => $produk->harga_jual,
            'harga_beli' => $produk->harga_beli, 'laba' => $produk->harga_jual - $produk->harga_beli,
            'status' => 'sukses',
        ]);
        return back()->with('success', "PPOB berhasil! {$produk->nama} → {$request->no_tujuan}");
    }

    public function voting(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        return view('portal.voting', compact('anggota'));
    }

    public function votingSubmit(Request $request)
    {
        $anggota = $this->getAnggotaOrAbort($request);
        $request->validate(['voting_id' => 'required|exists:rat_voting,id', 'opsi_index' => 'required|integer']);
        if (\App\Models\RatVotingSuara::where('voting_id', $request->voting_id)->where('anggota_id', $anggota->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan suara.');
        }
        \App\Models\RatVotingSuara::create(['voting_id' => $request->voting_id, 'anggota_id' => $anggota->id, 'opsi_index' => (int) $request->opsi_index]);
        return back()->with('success', 'Suara diterima!');
    }
}
