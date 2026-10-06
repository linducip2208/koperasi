<?php

namespace Tests\Feature;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Pinjaman\PinjamanService;
use App\Domain\Ppob\PpobService;
use App\Domain\Simpanan\SimpananService;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\Kas;
use App\Models\PeriodeAkuntansi;
use App\Models\Pinjaman;
use App\Models\PpobProduk;
use App\Models\ProdukPinjaman;
use App\Models\ProdukSimpanan;
use App\Models\Simpanan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\LicenseClient;
use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommercialHardeningTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Kas $kas;
    private Coa $coaKas;
    private Coa $coaSimpanan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create(['nama' => 'Test Koperasi', 'email' => 't@k.local', 'status' => 'aktif']);

        $this->coaKas = Coa::create([
            'tenant_id' => $this->tenant->id, 'kode' => '1.1.1.01', 'nama' => 'Kas',
            'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_kas' => true, 'is_postable' => true, 'is_aktif' => true,
        ]);
        $this->coaSimpanan = Coa::create([
            'tenant_id' => $this->tenant->id, 'kode' => '2.2.1.01', 'nama' => 'Simpanan Anggota',
            'tipe' => 'kewajiban', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true,
        ]);

        $this->kas = Kas::create([
            'tenant_id' => $this->tenant->id, 'kode' => 'KAS-01', 'nama' => 'Kas Utama',
            'tipe' => 'tunai', 'coa_id' => $this->coaKas->id, 'saldo' => 0, 'aktif' => true,
        ]);
    }

    private function anggota(string $nomor = 'AGT-001'): Anggota
    {
        return Anggota::create([
            'tenant_id' => $this->tenant->id, 'nomor_anggota' => $nomor,
            'nama' => 'Anggota '.$nomor, 'status' => 'aktif', 'tanggal_masuk' => now(),
        ]);
    }

    private function produkSimpanan(): ProdukSimpanan
    {
        return ProdukSimpanan::create([
            'tenant_id' => $this->tenant->id, 'kode' => 'SP01', 'nama' => 'Sukarela',
            'jenis' => 'sukarela', 'boleh_tarik' => true, 'coa_simpanan_id' => $this->coaSimpanan->id,
        ]);
    }

    // ─── Transfer simpanan atomic ───

    public function test_transfer_simpanan_atomic_dan_berjurnal(): void
    {
        $a = $this->anggota();
        $p = $this->produkSimpanan();
        $r1 = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R1', 'saldo' => 500_000, 'tanggal_buka' => now(), 'status' => 'aktif']);
        $r2 = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R2', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);

        [$keluar, $masuk, $jurnal] = SimpananService::transfer($r1, $r2, 200_000);

        $this->assertEquals(300_000, $r1->refresh()->saldo);
        $this->assertEquals(200_000, $r2->refresh()->saldo);
        $this->assertTrue($jurnal->isBalanced());
        $this->assertEquals('mutasi_keluar', $keluar->jenis);
        $this->assertEquals('mutasi_masuk', $masuk->jenis);
    }

    public function test_transfer_simpanan_saldo_kurang_ditolak(): void
    {
        $a = $this->anggota();
        $p = $this->produkSimpanan();
        $r1 = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R1', 'saldo' => 10_000, 'tanggal_buka' => now(), 'status' => 'aktif']);
        $r2 = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R2', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);

        $this->expectException(\InvalidArgumentException::class);
        SimpananService::transfer($r1, $r2, 100_000);
        $this->assertEquals(10_000, $r1->refresh()->saldo);
    }

    // ─── Immutability ───

    public function test_transaksi_simpanan_immutable(): void
    {
        $a = $this->anggota();
        $p = $this->produkSimpanan();
        $r = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R1', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);
        $trx = SimpananService::setor($r, 100_000, $this->kas->id);

        $this->expectException(\RuntimeException::class);
        $trx->update(['jumlah' => 1]);
    }

    public function test_jurnal_posted_tidak_bisa_dihapus(): void
    {
        $j = JurnalService::create('Test', [
            ['coa_id' => $this->coaKas->id, 'debit' => 1000, 'kredit' => 0],
            ['coa_id' => $this->coaSimpanan->id, 'debit' => 0, 'kredit' => 1000],
        ]);

        $this->expectException(\RuntimeException::class);
        $j->delete();
    }

    public function test_jurnal_reverse_membalik(): void
    {
        $j = JurnalService::create('Asal', [
            ['coa_id' => $this->coaKas->id, 'debit' => 5000, 'kredit' => 0],
            ['coa_id' => $this->coaSimpanan->id, 'debit' => 0, 'kredit' => 5000],
        ]);

        $r = JurnalService::reverse($j, null, 'koreksi test');

        $this->assertEquals('balik', $r->tipe);
        $this->assertTrue($r->isBalanced());
        $kasLine = $r->details()->where('coa_id', $this->coaKas->id)->first();
        $this->assertEquals(0, $kasLine->debit);
        $this->assertEquals(5000, $kasLine->kredit);
    }

    public function test_periode_closed_menolak_jurnal(): void
    {
        PeriodeAkuntansi::create([
            'tenant_id' => $this->tenant->id, 'tahun' => 2024, 'bulan' => 1,
            'tanggal_mulai' => '2024-01-01', 'tanggal_akhir' => '2024-01-31', 'status' => 'closed',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        JurnalService::create('Closed', [
            ['coa_id' => $this->coaKas->id, 'debit' => 1000, 'kredit' => 0],
            ['coa_id' => $this->coaSimpanan->id, 'debit' => 0, 'kredit' => 1000],
        ], ['tanggal' => '2024-01-15']);
    }

    // ─── Approval role ───

    public function test_approve_ditolak_untuk_role_salah(): void
    {
        $ao = User::create(['tenant_id' => $this->tenant->id, 'name' => 'AO', 'email' => 'ao@k.local', 'password' => bcrypt('x')]);
        $kasir = User::create(['tenant_id' => $this->tenant->id, 'name' => 'Kasir', 'email' => 'ks@k.local', 'password' => bcrypt('x')]);
        Role::firstOrCreate(['name' => 'ao', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);
        $ao->assignRole('ao');
        $kasir->assignRole('kasir');

        $produk = ProdukPinjaman::create([
            'tenant_id' => $this->tenant->id, 'kode' => 'PJ01', 'nama' => 'Reguler',
            'akad_type' => 'flat', 'metode_perhitungan' => 'flat', 'bunga_persen' => 12,
            'tenor_minimum' => 1, 'tenor_maksimum' => 12, 'plafon_minimum' => 100_000, 'plafon_maksimum' => 10_000_000,
        ]);
        $pinjaman = Pinjaman::create([
            'tenant_id' => $this->tenant->id, 'anggota_id' => $this->anggota()->id, 'produk_id' => $produk->id,
            'nomor_akad' => 'PJ-001', 'tanggal_pengajuan' => now(), 'plafon' => 1_000_000, 'pokok' => 1_000_000,
            'bunga_persen' => 12, 'tenor' => 12, 'status' => 'pengajuan',
        ]);
        PinjamanService::generateApprovalLevels($pinjaman);

        $this->expectException(\InvalidArgumentException::class);
        PinjamanService::approve($pinjaman, $kasir->id);

        // Role benar lolos level 1.
        PinjamanService::approve($pinjaman->refresh(), $ao->id);
        $this->assertEquals('setuju', $pinjaman->approval()->where('level', 1)->first()->keputusan);
    }

    // ─── Webhook idempotency ───

    public function test_webhook_duplikat_tidak_dobel_entry(): void
    {
        $this->withoutExceptionHandling();
        $provider = \App\Models\PaymentProvider::create([
            'nama' => 'Test Gateway', 'kode' => 'test-gw', 'api_format' => 'redirect',
            'base_url' => 'https://example.test', 'api_key' => 'secret-123',
            'extra_headers' => ['webhook_secret' => 'secret-123'],
            'is_sandbox' => true, 'aktif' => true,
        ]);

        $simpanan = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $this->anggota()->id, 'produk_id' => $this->produkSimpanan()->id, 'nomor_rekening' => 'R1', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);

        $payload = [
            'order_id' => 'SIMP-'.$simpanan->id,
            'transaction_status' => 'settlement',
            'gross_amount' => 150_000,
            'transaction_id' => 'TRX-DUP-1',
        ];
        $payload['signature'] = hash_hmac('sha512', $payload['order_id'].'settlement'.'150000', 'secret-123');

        $this->postJson("/webhooks/payment/test-gw", $payload)->assertOk();
        $this->postJson("/webhooks/payment/test-gw", $payload)->assertOk()->assertJson(['duplicate' => true]);

        $this->assertEquals(1, \App\Models\SimpananTransaksi::where('simpanan_id', $simpanan->id)->count());
    }

    public function test_webhook_tanpa_signature_ditolak(): void
    {
        \App\Models\PaymentProvider::create([
            'nama' => 'Prod Gateway', 'kode' => 'prod-gw', 'api_format' => 'redirect',
            'base_url' => 'https://example.test', 'api_key' => 'secret-xyz',
            'extra_headers' => ['webhook_secret' => 'secret-xyz'],
            'is_sandbox' => false, 'aktif' => true,
        ]);

        $this->postJson('/webhooks/payment/prod-gw', [
            'order_id' => 'SIMP-1', 'transaction_status' => 'settlement',
            'gross_amount' => 100_000, 'transaction_id' => 'TRX-X',
        ])->assertStatus(400);
    }

    // ─── PPOB idempotency ───

    public function test_ppob_double_submit_satu_transaksi(): void
    {
        $produk = PpobProduk::create([
            'kategori' => 'pulsa', 'kode' => 'PULSA10', 'nama' => 'Pulsa 10rb',
            'harga_jual' => 12_000, 'harga_beli' => 11_000, 'aktif' => true,
        ]);
        $a = $this->anggota();

        $t1 = PpobService::beli($a->id, $this->tenant->id, $produk->id, '081234567890', 'KEY-UNIK-1');
        $t2 = PpobService::beli($a->id, $this->tenant->id, $produk->id, '081234567890', 'KEY-UNIK-1');

        $this->assertEquals($t1->id, $t2->id);
        $this->assertEquals('sukses', $t1->status);
        $this->assertNotNull($t1->sn);
    }

    // ─── License status ───

    public function test_license_unpaired_tanpa_lock(): void
    {
        config(['license.lock_file' => storage_path('app/.license-test-missing.lock')]);
        @unlink(storage_path('app/.license-test-missing.lock'));

        $st = app(LicenseClient::class)->status('example.test');
        $this->assertEquals('UNPAIRED', $st['status']);
        $this->assertFalse($st['paired']);
        $this->assertNotEmpty($st['installation_id']);
    }

    public function test_license_lock_rusak_ditolak(): void
    {
        $path = storage_path('app/.license-test-tamper.lock');
        file_put_contents($path, 'data-rusak');
        config(['license.lock_file' => $path]);

        $st = app(LicenseClient::class)->status('example.test');
        $this->assertContains($st['status'], ['UNPAIRED', 'INVALID']);

        @unlink($path);
    }

    // ─── Sanitizer ───

    public function test_sanitizer_buang_script_dan_event_handler(): void
    {
        $out = HtmlSanitizer::clean('<p>Halo</p><script>alert(1)</script><a href="javascript:alert(2)" onclick="x()">klik</a>');
        $this->assertStringNotContainsString('<script>', $out);
        $this->assertStringNotContainsString('javascript:', $out);
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringContainsString('Halo', $out);
    }
}
