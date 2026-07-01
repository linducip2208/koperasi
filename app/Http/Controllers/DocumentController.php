<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\PinjamanPembayaran;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use App\Models\Tenant;
use App\Models\TokoPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function kuitansiSetoran(int $transaksiId)
    {
        $tx = SimpananTransaksi::with(['simpanan.anggota', 'simpanan.produk'])->findOrFail($transaksiId);
        $pdf = Pdf::loadView('documents.kuitansi-setoran', [
            'tx'     => $tx,
            'tenant' => Tenant::find(1),
        ])->setPaper('a5', 'landscape');
        return $pdf->stream("kuitansi-{$tx->id}.pdf");
    }

    public function kontrakPinjaman(int $pinjamanId)
    {
        $p = Pinjaman::with(['anggota', 'produk', 'jadwal'])->findOrFail($pinjamanId);
        $pdf = Pdf::loadView('documents.kontrak-pinjaman', [
            'p'      => $p,
            'tenant' => Tenant::find(1),
        ])->setPaper('a4');
        return $pdf->stream("kontrak-pinjaman-{$p->nomor}.pdf");
    }

    public function slipCicilan(int $pembayaranId)
    {
        $bayar = PinjamanPembayaran::with(['pinjaman.anggota', 'pinjaman.produk'])->findOrFail($pembayaranId);
        $pdf = Pdf::loadView('documents.slip-cicilan', [
            'bayar'  => $bayar,
            'tenant' => Tenant::find(1),
        ])->setPaper('a5', 'landscape');
        return $pdf->stream("slip-cicilan-{$bayar->id}.pdf");
    }

    public function invoicePenjualan(int $penjualanId)
    {
        $jual = TokoPenjualan::with(['anggota', 'detail.barang'])->findOrFail($penjualanId);
        $pdf = Pdf::loadView('documents.invoice-penjualan', [
            'jual'   => $jual,
            'tenant' => Tenant::find(1),
        ])->setPaper('a4');
        return $pdf->stream("invoice-{$jual->nomor}.pdf");
    }
}
