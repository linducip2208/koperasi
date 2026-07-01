<?php

namespace App\Http\Controllers;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Exports\LaporanExport;
use App\Models\Cabang;
use App\Models\ProdukPinjaman;
use App\Models\ProdukSimpanan;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function neraca(Request $request)
    {
        $sampai   = $request->input('sampai', now()->toDateString());
        $cabangId = $request->input('cabang_id') ? (int) $request->input('cabang_id') : null;

        $data = LaporanKeuanganService::neraca($sampai, $cabangId);
        $data = array_merge($data, $this->commonHeaderData($request, $cabangId));

        return $this->render('laporan.neraca', $data, "neraca-{$sampai}", $request);
    }

    public function labaRugi(Request $request)
    {
        $dari     = $request->input('dari', now()->startOfYear()->toDateString());
        $sampai   = $request->input('sampai', now()->toDateString());
        $cabangId = $request->input('cabang_id') ? (int) $request->input('cabang_id') : null;

        $data = LaporanKeuanganService::labaRugi($dari, $sampai, $cabangId);
        $data['dari']   = $dari;
        $data['sampai'] = $sampai;
        $data = array_merge($data, $this->commonHeaderData($request, $cabangId));

        return $this->render('laporan.laba-rugi', $data, "laba-rugi-{$dari}-{$sampai}", $request);
    }

    public function arusKas(Request $request)
    {
        $dari     = $request->input('dari', now()->startOfYear()->toDateString());
        $sampai   = $request->input('sampai', now()->toDateString());
        $cabangId = $request->input('cabang_id') ? (int) $request->input('cabang_id') : null;

        $data = LaporanKeuanganService::arusKas($dari, $sampai, $cabangId);
        $data = array_merge($data, $this->commonHeaderData($request, $cabangId));

        return $this->render('laporan.arus-kas', $data, "arus-kas-{$dari}-{$sampai}", $request);
    }

    /**
     * Ringkasan operasional per produk (simpanan & pinjaman) — bisa difilter cabang & produk.
     */
    public function ringkasanProduk(Request $request)
    {
        $cabangId          = $request->input('cabang_id') ? (int) $request->input('cabang_id') : null;
        $produkSimpananId  = $request->input('produk_simpanan_id') ? (int) $request->input('produk_simpanan_id') : null;
        $produkPinjamanId  = $request->input('produk_pinjaman_id') ? (int) $request->input('produk_pinjaman_id') : null;

        $simpananQ = \App\Models\Simpanan::query()
            ->select('produk_id', DB::raw('COUNT(*) as jml'), DB::raw('SUM(saldo) as total'))
            ->groupBy('produk_id')
            ->with('produk');
        if ($cabangId) $simpananQ->where('cabang_id', $cabangId);
        if ($produkSimpananId) $simpananQ->where('produk_id', $produkSimpananId);

        $pinjamanQ = \App\Models\Pinjaman::query()
            ->select('produk_id', DB::raw('COUNT(*) as jml'), DB::raw('SUM(saldo_pokok) as outstanding'))
            ->where('status', 'aktif')
            ->groupBy('produk_id')
            ->with('produk');
        if ($cabangId) $pinjamanQ->where('cabang_id', $cabangId);
        if ($produkPinjamanId) $pinjamanQ->where('produk_id', $produkPinjamanId);

        $data = array_merge([
            'simpanan'        => $simpananQ->get(),
            'pinjaman'        => $pinjamanQ->get(),
            'produkSimpanan'  => $produkSimpananId ? ProdukSimpanan::find($produkSimpananId) : null,
            'produkPinjaman'  => $produkPinjamanId ? ProdukPinjaman::find($produkPinjamanId) : null,
            'periode'         => 'Posisi ' . now()->format('d M Y'),
        ], $this->commonHeaderData($request, $cabangId));

        return $this->render('laporan.ringkasan-produk', $data, 'ringkasan-produk-' . now()->toDateString(), $request);
    }

    public function excel(Request $request, string $laporan)
    {
        $jenis = $laporan;
        abort_unless(in_array($jenis, ['neraca', 'laba-rugi', 'arus-kas'], true), 404);

        $dari     = $request->input('dari', now()->startOfYear()->toDateString());
        $sampai   = $request->input('sampai', now()->toDateString());
        $cabangId = $request->input('cabang_id') ? (int) $request->input('cabang_id') : null;

        $filename = "{$jenis}-" . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new LaporanExport($jenis, $dari, $sampai, $cabangId), $filename);
    }

    private function commonHeaderData(Request $request, ?int $cabangId): array
    {
        return [
            'tenant' => Tenant::find(1),
            'cabang' => $cabangId ? Cabang::find($cabangId) : null,
        ];
    }

    private function render(string $view, array $data, string $filename, Request $request)
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4');
        return $request->boolean('download')
            ? $pdf->download("{$filename}.pdf")
            : $pdf->stream("{$filename}.pdf");
    }
}
