<?php

namespace Tests\Feature;

use App\Domain\Akuntansi\LaporanKeuanganService;
use App\Models\Anggota;
use App\Models\Rat;
use App\Models\RatKehadiran;
use App\Models\RatVoting;
use App\Models\RatVotingSuara;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SakEpRatTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'nama' => 'Test Koperasi',
            'email' => 'test@koperasi.local',
            'status' => 'aktif',
        ]);
    }

    public function test_perubahan_ekuitas_returns_sak_ep_structure(): void
    {
        $d = LaporanKeuanganService::perubahanEkuitas('2025-01-01', '2025-12-31');

        $this->assertArrayHasKey('rincian', $d);
        $this->assertArrayHasKey('total_awal', $d);
        $this->assertArrayHasKey('total_mutasi', $d);
        $this->assertArrayHasKey('total_akhir', $d);
        $this->assertArrayHasKey('shu_berjalan', $d);
        $this->assertEquals($d['total_awal'] + $d['total_mutasi'], $d['total_akhir']);
    }

    public function test_calk_returns_sak_ep_structure(): void
    {
        $d = LaporanKeuanganService::calk('2025-01-01', '2025-12-31');

        $this->assertArrayHasKey('ringkasan', $d);
        $this->assertArrayHasKey('akun_material', $d);
        $this->assertArrayHasKey('segmen_usaha', $d);
        $this->assertArrayHasKey('kebijakan', $d);
        $this->assertArrayHasKey('shu', $d['ringkasan']);
    }

    public function test_quorum_updates_from_kehadiran_rows(): void
    {
        $anggota = [];
        for ($i = 1; $i <= 4; $i++) {
            $anggota[] = Anggota::create([
                'tenant_id' => $this->tenant->id,
                'nomor_anggota' => "AGT-00{$i}",
                'nama' => "Anggota {$i}",
                'status' => 'aktif',
                'tanggal_masuk' => now(),
            ]);
        }

        $rat = Rat::create([
            'tenant_id' => $this->tenant->id,
            'tahun_buku' => 2025,
            'tanggal' => now()->toDateString(),
            'quorum_persen' => 50,
            'status' => 'berlangsung',
        ]);

        // 1 dari 4 hadir = 25% → belum quorum
        RatKehadiran::create([
            'tenant_id' => $this->tenant->id,
            'rat_id' => $rat->id,
            'anggota_id' => $anggota[0]->id,
            'checkin_at' => now(),
            'metode' => 'qr',
        ]);

        $rat->refresh();
        $this->assertEquals(1, $rat->jumlah_hadir);
        $this->assertFalse((bool) $rat->quorum_tercapai);

        // 2 dari 4 hadir = 50% → quorum tercapai
        RatKehadiran::create([
            'tenant_id' => $this->tenant->id,
            'rat_id' => $rat->id,
            'anggota_id' => $anggota[1]->id,
            'checkin_at' => now(),
            'metode' => 'manual',
        ]);

        $rat->refresh();
        $this->assertEquals(2, $rat->jumlah_hadir);
        $this->assertTrue((bool) $rat->quorum_tercapai);
    }

    public function test_selesai_rat_keeps_manual_history(): void
    {
        $rat = Rat::create([
            'tenant_id' => $this->tenant->id,
            'tahun_buku' => 2024,
            'tanggal' => '2024-03-01',
            'jumlah_anggota_terdaftar' => 100,
            'jumlah_hadir' => 80,
            'quorum_persen' => 50,
            'quorum_tercapai' => true,
            'status' => 'selesai',
        ]);

        $rat->refreshQuorum();
        $rat->refresh();

        $this->assertEquals(80, $rat->jumlah_hadir);
        $this->assertTrue((bool) $rat->quorum_tercapai);
    }

    public function test_voting_rejects_double_vote(): void
    {
        $anggota = Anggota::create([
            'tenant_id' => $this->tenant->id,
            'nomor_anggota' => 'AGT-001',
            'nama' => 'Voter',
            'status' => 'aktif',
            'tanggal_masuk' => now(),
        ]);

        $rat = Rat::create([
            'tenant_id' => $this->tenant->id,
            'tahun_buku' => 2025,
            'tanggal' => now()->toDateString(),
            'status' => 'berlangsung',
        ]);

        $voting = RatVoting::create([
            'tenant_id' => $this->tenant->id,
            'rat_id' => $rat->id,
            'judul' => 'Test Voting',
            'opsi' => ['Setuju', 'Tidak'],
            'mulai' => now()->subDay(),
            'selesai' => now()->addDay(),
            'is_aktif' => true,
        ]);

        RatVotingSuara::create([
            'voting_id' => $voting->id,
            'anggota_id' => $anggota->id,
            'opsi_index' => 0,
        ]);

        // unique [voting_id, anggota_id] → double vote ditolak DB
        $this->expectException(\Illuminate\Database\QueryException::class);
        RatVotingSuara::create([
            'voting_id' => $voting->id,
            'anggota_id' => $anggota->id,
            'opsi_index' => 1,
        ]);
    }
}
