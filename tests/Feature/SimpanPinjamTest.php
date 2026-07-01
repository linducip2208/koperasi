<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\ProdukSimpanan;
use App\Models\ProdukPinjaman;
use App\Models\Simpanan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpanPinjamTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private User $user;
    private Anggota $anggota;
    private ProdukSimpanan $produkSimpanan;
    private ProdukPinjaman $produkPinjaman;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'nama'   => 'Test Koperasi',
            'email'  => 'test@koperasi.local',
            'status' => 'aktif',
        ]);

        $this->user = User::create([
            'tenant_id' => $this->tenant->id,
            'name'      => 'Test User',
            'email'     => 'user@koperasi.local',
            'password'  => bcrypt('secret123'),
        ]);

        $this->anggota = Anggota::create([
            'tenant_id'     => $this->tenant->id,
            'nomor_anggota' => 'AGT-001',
            'nama'          => 'Test Anggota',
            'status'        => 'aktif',
            'tanggal_masuk' => now(),
        ]);

        $this->produkSimpanan = ProdukSimpanan::create([
            'tenant_id' => $this->tenant->id,
            'kode'      => 'SP01',
            'nama'      => 'Simpanan Pokok',
            'jenis'     => 'pokok',
        ]);

        $this->produkPinjaman = ProdukPinjaman::create([
            'tenant_id'          => $this->tenant->id,
            'kode'               => 'PJ01',
            'nama'               => 'Pinjaman Reguler',
            'akad_type'          => 'flat',
            'metode_perhitungan' => 'flat',
            'bunga_persen'       => 12,
            'tenor_minimum'      => 1,
            'tenor_maksimum'     => 36,
            'plafon_minimum'     => 100_000,
            'plafon_maksimum'    => 50_000_000,
        ]);
    }

    // ─── SIMPANAN ───

    public function test_create_simpanan(): void
    {
        $simpanan = Simpanan::create([
            'tenant_id'     => $this->tenant->id,
            'anggota_id'    => $this->anggota->id,
            'produk_id'     => $this->produkSimpanan->id,
            'nomor_rekening' => 'REK-001',
            'saldo'         => 100_000,
            'tanggal_buka'  => now(),
            'status'        => 'aktif',
        ]);

        $this->assertDatabaseHas('simpanan', [
            'id'           => $simpanan->id,
            'saldo'        => 100_000,
            'status'       => 'aktif',
        ]);
    }

    public function test_simpanan_has_correct_relationships(): void
    {
        $simpanan = Simpanan::create([
            'tenant_id'     => $this->tenant->id,
            'anggota_id'    => $this->anggota->id,
            'produk_id'     => $this->produkSimpanan->id,
            'nomor_rekening' => 'REK-002',
            'saldo'         => 50_000,
            'tanggal_buka'  => now(),
            'status'        => 'aktif',
        ]);

        $this->assertEquals($this->anggota->id, $simpanan->anggota->id);
        $this->assertEquals($this->produkSimpanan->id, $simpanan->produk->id);
    }

    // ─── PINJAMAN ───

    public function test_create_pinjaman(): void
    {
        $pinjaman = \App\Models\Pinjaman::create([
            'tenant_id'          => $this->tenant->id,
            'anggota_id'         => $this->anggota->id,
            'produk_id'          => $this->produkPinjaman->id,
            'nomor_akad'         => 'PJ-2026-001',
            'tanggal_pengajuan'  => now(),
            'plafon'             => 5_000_000,
            'pokok'              => 5_000_000,
            'bunga_persen'       => 12,
            'tenor'              => 12,
            'tanggal_akad'       => now(),
            'tanggal_jatuh_tempo' => now()->addMonths(12),
            'status'             => 'aktif',
        ]);

        $this->assertDatabaseHas('pinjaman', [
            'id'     => $pinjaman->id,
            'plafon' => 5_000_000,
            'status' => 'aktif',
        ]);
    }

    public function test_pinjaman_has_correct_relationships(): void
    {
        $pinjaman = \App\Models\Pinjaman::create([
            'tenant_id'          => $this->tenant->id,
            'anggota_id'         => $this->anggota->id,
            'produk_id'          => $this->produkPinjaman->id,
            'nomor_akad'         => 'PJ-2026-002',
            'tanggal_pengajuan'  => now(),
            'plafon'             => 2_000_000,
            'pokok'              => 2_000_000,
            'bunga_persen'       => 12,
            'tenor'              => 12,
            'tanggal_akad'       => now(),
            'tanggal_jatuh_tempo' => now()->addMonths(12),
            'status'             => 'aktif',
        ]);

        $this->assertEquals($this->anggota->id, $pinjaman->anggota->id);
        $this->assertEquals($this->produkPinjaman->id, $pinjaman->produk->id);
    }

    // ─── PRODUK ───

    public function test_produk_simpanan_tipe_enum(): void
    {
        $tipe = ['pokok', 'wajib', 'sukarela', 'berjangka', 'wadiah', 'mudharabah'];

        foreach ($tipe as $t) {
            $p = ProdukSimpanan::create([
                'tenant_id' => $this->tenant->id,
                'kode'      => 'SP-' . strtoupper(substr($t, 0, 3)),
                'nama'      => 'Simpanan ' . ucfirst($t),
                'jenis'     => $t,
            ]);
            $this->assertEquals($t, $p->jenis);
        }
    }

    public function test_produk_pinjaman_akad_types(): void
    {
        $akadTypes = ['flat', 'efektif', 'anuitas', 'murabahah', 'mudharabah'];

        foreach ($akadTypes as $akad) {
            $p = ProdukPinjaman::create([
                'tenant_id'          => $this->tenant->id,
                'kode'               => 'PJ-' . strtoupper(substr($akad, 0, 4)),
                'nama'               => 'Pinjaman ' . ucfirst($akad),
                'akad_type'          => $akad,
                'metode_perhitungan' => $akad,
                'bunga_persen'       => 12,
                'tenor_minimum'      => 1,
                'tenor_maksimum'     => 12,
                'plafon_minimum'     => 100_000,
                'plafon_maksimum'    => 10_000_000,
            ]);
            $this->assertEquals($akad, $p->akad_type);
        }
    }
}
