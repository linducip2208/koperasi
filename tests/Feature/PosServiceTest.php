<?php

namespace Tests\Feature;

use App\Domain\Toko\PosService;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\Kas;
use App\Models\ProdukSimpanan;
use App\Models\Simpanan;
use App\Models\Tenant;
use App\Models\TokoBarang;
use App\Models\TokoPenjualan;
use App\Models\TokoPenjualanDetail;
use App\Support\Tenant\CurrentTenant;
use Database\Seeders\ChartOfAccountsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosServiceTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Kas $kas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'nama'   => 'Test Koperasi',
            'email'  => 'test@koperasi.local',
            'status' => 'aktif',
        ]);

        CurrentTenant::set($this->tenant->id);
        $this->seed(ChartOfAccountsSeeder::class);

        $this->kas = Kas::create([
            'tenant_id' => $this->tenant->id,
            'kode'      => 'KAS-01',
            'nama'      => 'Kas Besar',
            'tipe'      => 'kas',
            'coa_id'    => Coa::where('kode', '1.1.1.01')->first()->id,
            'saldo'     => 0,
            'aktif'     => true,
        ]);
    }

    private function barang(int $stok, int $hargaJual, int $hargaBeli): TokoBarang
    {
        return TokoBarang::create([
            'tenant_id'       => $this->tenant->id,
            'sku'             => 'SKU-' . uniqid(),
            'nama'            => 'Barang Test',
            'harga_beli'      => $hargaBeli,
            'harga_jual_umum' => $hargaJual,
            'stok'            => $stok,
            'aktif'           => true,
        ]);
    }

    private function penjualan(string $metode, int $total, ?int $simpananId = null): TokoPenjualan
    {
        $barang = $this->barang(10, 15_000, 10_000);

        $jual = TokoPenjualan::create([
            'tenant_id'    => $this->tenant->id,
            'nomor'        => 'INV-' . uniqid(),
            'tanggal'      => now(),
            'subtotal'     => $total,
            'total'        => $total,
            'metode_bayar' => $metode,
            'kas_id'       => $this->kas->id,
            'simpanan_id'  => $simpananId,
            'status'       => 'lunas',
        ]);

        TokoPenjualanDetail::create([
            'tenant_id'    => $this->tenant->id,
            'penjualan_id' => $jual->id,
            'barang_id'    => $barang->id,
            'jumlah'       => 2,
            'harga_satuan' => 15_000,
            'subtotal'     => $total,
            'hpp'          => 10_000,
        ]);

        return $jual->fresh('detail');
    }

    public function test_penjualan_cash_kurangi_stok_dan_buat_jurnal(): void
    {
        $jual = $this->penjualan('cash', 30_000);
        $barangId = $jual->detail->first()->barang_id;

        PosService::proses($jual);

        $this->assertNotNull($jual->fresh()->jurnal_id);
        $this->assertEquals(8, TokoBarang::find($barangId)->stok);

        $jurnal = $jual->fresh()->jurnal;
        $this->assertEquals($jurnal->total_debit, $jurnal->total_kredit);
        $this->assertEquals(50_000, $jurnal->total_debit); // 30k penjualan + 20k HPP
    }

    public function test_penjualan_potong_simpanan_kurangi_saldo(): void
    {
        $anggota = Anggota::create([
            'tenant_id'     => $this->tenant->id,
            'nomor_anggota' => 'AGT-001',
            'nama'          => 'Anggota Test',
            'status'        => 'aktif',
            'tanggal_masuk' => now(),
        ]);

        $produk = ProdukSimpanan::create([
            'tenant_id' => $this->tenant->id,
            'kode'      => 'SUK',
            'nama'      => 'Sukarela',
            'jenis'     => 'sukarela',
        ]);

        $simpanan = Simpanan::create([
            'tenant_id'      => $this->tenant->id,
            'anggota_id'     => $anggota->id,
            'produk_id'      => $produk->id,
            'nomor_rekening' => 'REK-001',
            'saldo'          => 100_000,
            'tanggal_buka'   => now(),
            'status'         => 'aktif',
        ]);

        $jual = $this->penjualan('simpanan', 30_000, $simpanan->id);
        $jual->update(['anggota_id' => $anggota->id]);

        PosService::proses($jual);

        $this->assertEquals(70_000, $simpanan->fresh()->saldo);
        $this->assertDatabaseHas('simpanan_transaksi', [
            'simpanan_id' => $simpanan->id,
            'jenis'       => 'tarik',
            'jumlah'      => 30_000,
        ]);
    }

    public function test_proses_idempotent(): void
    {
        $jual = $this->penjualan('cash', 30_000);
        $barangId = $jual->detail->first()->barang_id;

        PosService::proses($jual);
        PosService::proses($jual->fresh('detail'));

        $this->assertEquals(8, TokoBarang::find($barangId)->stok);
    }
}
