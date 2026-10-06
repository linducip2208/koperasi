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
    /**
     * Otorisasi dokumen: pemilik (via anggota.user_id) atau staf berizin anggota.view.
     * Mencegah IDOR — user login tidak bisa mengunduh dokumen anggota lain.
     */
    protected function authorizeAnggota(?int $anggotaId): void
    {
        $user = auth()->user();
        abort_unless($user, 403);
        if ($user->can('anggota.view')) return;
        $milikSendiri = $anggotaId && \App\Models\Anggota::where('user_id', $user->id)
            ->where('id', $anggotaId)->exists();
        abort_unless($milikSendiri, 403, 'Tidak berhak melihat dokumen ini.');
    }

    public function kuitansiSetoran(int $transaksiId)
    {
        $tx = SimpananTransaksi::with(['simpanan.anggota', 'simpanan.produk'])->findOrFail($transaksiId);
        $this->authorizeAnggota($tx->simpanan->anggota_id ?? null);
        $pdf = Pdf::loadView('documents.kuitansi-setoran', [
            'tx'     => $tx,
            'tenant' => \App\Support\CooperativeContext::current(),
        ])->setPaper('a5', 'landscape');
        return $pdf->stream("kuitansi-{$tx->id}.pdf");
    }

    public function kontrakPinjaman(int $pinjamanId)
    {
        $p = Pinjaman::with(['anggota', 'produk', 'jadwal'])->findOrFail($pinjamanId);
        $this->authorizeAnggota($p->anggota_id);
        $pdf = Pdf::loadView('documents.kontrak-pinjaman', [
            'p'      => $p,
            'tenant' => \App\Support\CooperativeContext::current(),
        ])->setPaper('a4');
        return $pdf->stream("kontrak-pinjaman-{$p->nomor}.pdf");
    }

    public function slipCicilan(int $pembayaranId)
    {
        $bayar = PinjamanPembayaran::with(['pinjaman.anggota', 'pinjaman.produk'])->findOrFail($pembayaranId);
        $this->authorizeAnggota($bayar->pinjaman->anggota_id ?? null);
        $pdf = Pdf::loadView('documents.slip-cicilan', [
            'bayar'  => $bayar,
            'tenant' => \App\Support\CooperativeContext::current(),
        ])->setPaper('a5', 'landscape');
        return $pdf->stream("slip-cicilan-{$bayar->id}.pdf");
    }

    /** Unduh dokumen anggota: via storage privat + cek kepemilikan (anti path traversal & IDOR). */
    public function memberDoc(int $id)
    {
        $doc = \App\Models\MemberDocument::findOrFail($id);
        $this->authorizeAnggota($doc->anggota_id);

        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        abort_unless($disk->exists($doc->file_path), 404);
        abort_if(str_contains($doc->file_path, '..'), 400);

        return $disk->download($doc->file_path, $doc->nama.'.'.pathinfo($doc->file_path, PATHINFO_EXTENSION));
    }

    public function invoicePenjualan(int $penjualanId)
    {
        $jual = TokoPenjualan::with(['anggota', 'detail.barang'])->findOrFail($penjualanId);        $user = auth()->user();
        // Nota toko: pemilik, kasir (pos.view), atau staf anggota.view.
        $ok = $user->can('anggota.view') || $user->can('pos.view');
        if (! $ok && $jual->anggota_id) {
            $ok = \App\Models\Anggota::where('user_id', $user->id)->where('id', $jual->anggota_id)->exists();
        }
        abort_unless($ok, 403, 'Tidak berhak melihat dokumen ini.');
        $pdf = Pdf::loadView('documents.invoice-penjualan', [
            'jual'   => $jual,
            'tenant' => \App\Support\CooperativeContext::current(),
        ])->setPaper('a4');
        return $pdf->stream("invoice-{$jual->nomor}.pdf");
    }
}
