<?php

namespace Tests\Feature;

use App\Domain\Pinjaman\CreditScoringService;
use App\Imports\ImportEngine;
use App\Models\Anggota;
use App\Models\CollectionFollowup;
use App\Models\ImportBatch;
use App\Models\Pinjaman;
use App\Models\Procurement;
use App\Models\ProdukPinjaman;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Ocr\OcrManager;
use App\Workflow\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CollectionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['nama' => 'T', 'email' => 't@k.local', 'status' => 'aktif']);
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);
    }

    private function admin(): User
    {
        $u = User::create(['tenant_id' => $this->tenant->id, 'name' => 'SA', 'email' => 'sa@k.local', 'password' => bcrypt('x')]);
        $u->assignRole('super-admin');
        return $u;
    }

    public function test_credit_scoring_deterministik(): void
    {
        $a = Anggota::create(['tenant_id' => $this->tenant->id, 'nomor_anggota' => 'AGT-1', 'nama' => 'A',
            'status' => 'aktif', 'tanggal_masuk' => now()->subYears(3), 'penghasilan_bulanan' => 5_000_000]);
        $s1 = CreditScoringService::skor($a, 10_000_000);
        $s2 = CreditScoringService::skor($a, 10_000_000);
        $this->assertEquals($s1, $s2);
        $this->assertContains($s1['level'], ['BAIK', 'CUKUP', 'KURANG']);
        $this->assertGreaterThan(0, $s1['rekomendasi_plafon'] + $s1['rekomendasi_tenor']);
        $this->assertLessThanOrEqual(100, $s1['skor']);
    }

    public function test_collection_followup_tercatat(): void
    {
        $u = $this->admin();
        $produk = ProdukPinjaman::create(['tenant_id' => $this->tenant->id, 'kode' => 'PJ1', 'nama' => 'P',
            'akad_type' => 'flat', 'metode_perhitungan' => 'flat', 'bunga_persen' => 12,
            'tenor_minimum' => 1, 'tenor_maksimum' => 12, 'plafon_minimum' => 1, 'plafon_maksimum' => 99_000_000]);
        $a = Anggota::create(['tenant_id' => $this->tenant->id, 'nomor_anggota' => 'AGT-2', 'nama' => 'B', 'status' => 'aktif', 'tanggal_masuk' => now()]);
        $p = Pinjaman::create(['tenant_id' => $this->tenant->id, 'anggota_id' => $a->id, 'produk_id' => $produk->id,
            'nomor_akad' => 'PJ-1', 'tanggal_pengajuan' => now(), 'plafon' => 1_000_000, 'pokok' => 1_000_000,
            'bunga_persen' => 12, 'tenor' => 12, 'status' => 'aktif', 'tunggakan_hari' => 10]);

        CollectionFollowup::create(['tenant_id' => $this->tenant->id, 'pinjaman_id' => $p->id, 'user_id' => $u->id,
            'jenis' => 'janji_bayar', 'catatan' => 'Janji bayar Jumat', 'janji_nominal' => 500_000, 'janji_tanggal' => now()->addDays(3)->toDateString()]);

        $this->assertEquals(1, $p->followups()->count());
        $this->assertEquals(1, $u->followups()->count());
    }

    public function test_workflow_transisi_legal_dan_ilegal(): void
    {
        $u = $this->admin();
        $this->actingAs($u);
        $pr = Procurement::create(['tenant_id' => $this->tenant->id, 'nomor' => 'PR-1', 'judul' => 'Beli ATK', 'estimasi' => 1_000_000, 'status' => 'draft']);

        WorkflowService::transition($pr, 'submitted', 'procurement');
        $this->assertEquals('submitted', $pr->refresh()->status);

        $this->expectException(\InvalidArgumentException::class);
        WorkflowService::transition($pr->refresh(), 'closed', 'procurement'); // lompat ilegal
    }

    public function test_workflow_tolak_tanpa_izin(): void
    {
        $kasir = User::create(['tenant_id' => $this->tenant->id, 'name' => 'K', 'email' => 'k@k.local', 'password' => bcrypt('x')]);
        $kasir->assignRole('kasir');
        $this->actingAs($kasir);
        $pr = Procurement::create(['tenant_id' => $this->tenant->id, 'nomor' => 'PR-2', 'judul' => 'X', 'estimasi' => 1, 'status' => 'review']);

        $this->expectException(\InvalidArgumentException::class);
        WorkflowService::transition($pr, 'approved', 'procurement'); // kasir tanpa procurement.approve
    }

    public function test_import_anggota_end_to_end(): void
    {
        $u = $this->admin();
        $this->actingAs($u);
        $rows = [
            ['Nama Lengkap', 'NIK', 'Telepon'],
            ['Citra', '9990001112223331', '0811'],
            ['Dodi', '9990001112223332', '0812'],
        ];
        $v = ImportEngine::validate('members', $rows);
        $this->assertCount(2, $v['valid']);

        $batch = ImportBatch::create(['tipe' => 'members', 'file_name' => 't.csv', 'total_rows' => 2,
            'valid_rows' => 2, 'status' => 'preview', 'user_id' => $u->id]);
        ImportEngine::import($batch, $v['valid']);

        $this->assertDatabaseHas('anggota', ['nik' => '9990001112223331']);
        $this->assertEquals('completed', $batch->refresh()->status);
    }

    public function test_ocr_tidak_menimpa_otomatis(): void
    {
        $usul = OcrManager::propose('/tmp/ktp.jpg', 'ktp', ['nama' => 'Budi']);
        $this->assertEquals([], $usul); // provider manual → tidak ada usulan otomatis
    }
}
