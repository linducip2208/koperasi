<?php

namespace Tests\Feature;

use App\Domain\Shu\ShuCalculationService;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\ProdukSimpanan;
use App\Models\ShuDistribusi;
use App\Models\ShuPerhitungan;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShuPayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_bagikan_kredit_simpanan_dan_jurnal_balance(): void
    {
        $t = Tenant::create(['nama' => 'T', 'email' => 't@k.local', 'status' => 'aktif']);
        Coa::create(['tenant_id' => $t->id, 'kode' => '3.2.1.01', 'nama' => 'SHU Ditahan', 'tipe' => 'ekuitas', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $t->id, 'kode' => '2.2.1.01', 'nama' => 'Simpanan', 'tipe' => 'kewajiban', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        $produk = ProdukSimpanan::create(['tenant_id' => $t->id, 'kode' => 'SS', 'nama' => 'Sukarela', 'jenis' => 'sukarela', 'boleh_tarik' => true]);
        $a = Anggota::create(['tenant_id' => $t->id, 'nomor_anggota' => 'A1', 'nama' => 'A', 'status' => 'aktif', 'tanggal_masuk' => now()]);

        $hit = ShuPerhitungan::create(['tenant_id' => $t->id, 'tahun' => 2025, 'shu_total' => 1_000_000, 'status' => 'disetujui',
            'jumlah_jasa_modal' => 500_000, 'jumlah_jasa_anggota' => 500_000]);
        ShuDistribusi::create(['tenant_id' => $t->id, 'shu_perhitungan_id' => $hit->id, 'anggota_id' => $a->id,
            'total_simpanan' => 1_000_000, 'total_transaksi' => 0, 'jasa_modal' => 100_000, 'jasa_anggota' => 0,
            'total_shu' => 100_000, 'status' => 'belum_dibagikan']);

        $hasil = ShuCalculationService::bagikan(2025);

        $this->assertEquals(['dibayar' => 1, 'total' => 1], $hasil);
        $this->assertEquals(100_000, $a->simpanan()->where('status', 'aktif')->sum('saldo'));
        $this->assertEquals('dibayar', ShuDistribusi::first()->status);
        $this->assertEquals('distribusi', $hit->refresh()->status);
        $j = Jurnal::where('referensi_type', ShuPerhitungan::class)->first();
        $this->assertNotNull($j);
        $this->assertTrue($j->isBalanced());
    }

    public function test_bagikan_idempotent_dan_tolak_draft(): void
    {
        $t = Tenant::create(['nama' => 'T', 'email' => 't@k.local', 'status' => 'aktif']);
        ShuPerhitungan::create(['tenant_id' => $t->id, 'tahun' => 2026, 'shu_total' => 1, 'status' => 'draft']);

        $this->expectException(\InvalidArgumentException::class);
        ShuCalculationService::bagikan(2026);
    }
}
