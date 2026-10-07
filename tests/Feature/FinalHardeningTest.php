<?php

namespace Tests\Feature;

use App\Domain\Shu\ShuCalculationService;
use App\Domain\Simpanan\SimpananService;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\Kas;
use App\Models\ProdukSimpanan;
use App\Models\Simpanan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinalHardeningTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Kas $kas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['nama' => 'T', 'email' => 't@k.local', 'status' => 'aktif']);
        $coaKas = Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '1.1.1.01', 'nama' => 'Kas', 'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_kas' => true, 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '2.2.1.01', 'nama' => 'Simpanan', 'tipe' => 'kewajiban', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        $this->kas = Kas::create(['tenant_id' => $this->tenant->id, 'kode' => 'K1', 'nama' => 'Kas', 'tipe' => 'tunai', 'coa_id' => $coaKas->id, 'saldo' => 0, 'aktif' => true]);
    }

    public function test_setor_idempotency_key_ganda_satu_baris(): void
    {
        $a = Anggota::create(['tenant_id' => $this->tenant->id, 'nomor_anggota' => 'A1', 'nama' => 'A', 'status' => 'aktif', 'tanggal_masuk' => now()]);
        $p = ProdukSimpanan::create(['tenant_id' => $this->tenant->id, 'kode' => 'SP1', 'nama' => 'S', 'jenis' => 'sukarela', 'boleh_tarik' => true]);
        $r = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R1', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);

        $t1 = SimpananService::setor($r, 100_000, $this->kas->id, null, 'cash', null, 'DEV-KEY-1', 'DEV-1');
        $t2 = SimpananService::setor($r, 100_000, $this->kas->id, null, 'cash', null, 'DEV-KEY-1', 'DEV-1');

        $this->assertEquals($t1->id, $t2->id);
        $this->assertEquals(100_000, $r->refresh()->saldo);
    }

    public function test_shu_disetujui_tidak_bisa_dihitung_ulang(): void
    {
        $h = ShuCalculationService::hitung(2025, 10_000_000, []);
        $h->update(['status' => 'disetujui']);

        $this->expectException(\InvalidArgumentException::class);
        ShuCalculationService::hitung(2025, 20_000_000, []);
    }

    public function test_shu_snapshot_tersimpan(): void
    {
        $h = ShuCalculationService::hitung(2026, 10_000_000, ['jasa_modal' => 30]);
        $this->assertEquals(10_000_000, $h->meta['snapshot_terakhir']['shu_total']);
        $this->assertEquals(30, $h->meta['snapshot_terakhir']['persen']['jasa_modal']);
    }

    public function test_verifikasi_butuh_signature(): void
    {
        $a = Anggota::create(['tenant_id' => $this->tenant->id, 'nomor_anggota' => 'A9', 'nama' => 'V', 'status' => 'aktif', 'tanggal_masuk' => now()]);
        $this->get("/portal/verifikasi/{$a->id}")->assertForbidden();
    }

    public function test_api_transaksi_butuh_auth(): void
    {
        $this->getJson('/api/v1/transaksi')->assertUnauthorized();
    }

    public function test_api_transaksi_milik_sendiri(): void
    {
        $user = User::create(['tenant_id' => $this->tenant->id, 'name' => 'M', 'email' => 'm@k.local', 'password' => bcrypt('x')]);
        $a = Anggota::create(['tenant_id' => $this->tenant->id, 'nomor_anggota' => 'A7', 'nama' => 'M', 'status' => 'aktif', 'tanggal_masuk' => now(), 'user_id' => $user->id]);
        $p = ProdukSimpanan::create(['tenant_id' => $this->tenant->id, 'kode' => 'SP9', 'nama' => 'S', 'jenis' => 'sukarela', 'boleh_tarik' => true]);
        $r = Simpanan::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $p->id, 'nomor_rekening' => 'R9', 'saldo' => 0, 'tanggal_buka' => now(), 'status' => 'aktif']);
        SimpananService::setor($r, 50_000, $this->kas->id);

        $token = $user->createToken('t')->plainTextToken;
        $this->getJson('/api/v1/transaksi', ['Authorization' => 'Bearer '.$token])
            ->assertOk()->assertJsonCount(1, 'data');
    }
}
