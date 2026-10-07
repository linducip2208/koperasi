<?php

namespace Tests\Feature;

use App\Domain\Asset\AssetService;
use App\Models\Anggaran;
use App\Models\Asset;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\Kas;
use App\Models\Tenant;
use App\Reports\ReportRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetBudgetTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;

    private Kas $kas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['nama' => 'T', 'email' => 't@k.local', 'status' => 'aktif']);
        $coaKas = Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '1.1.1.01', 'nama' => 'Kas', 'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_kas' => true, 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '1.2.1.01', 'nama' => 'Peralatan', 'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '1.2.9.01', 'nama' => 'Akumulasi Peralatan', 'tipe' => 'aset', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '4.9.1.01', 'nama' => 'Pendapatan Lain', 'tipe' => 'pendapatan', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '5.9.1.01', 'nama' => 'Beban Lain', 'tipe' => 'beban', 'saldo_normal' => 'debit', 'is_postable' => true, 'is_aktif' => true]);
        $this->kas = Kas::create(['tenant_id' => $this->tenant->id, 'kode' => 'K1', 'nama' => 'Kas', 'tipe' => 'tunai', 'coa_id' => $coaKas->id, 'saldo' => 0, 'aktif' => true]);
    }

    public function test_lepas_aset_untung_jurnal_balance(): void
    {
        $aset = Asset::create(['tenant_id' => $this->tenant->id, 'kode' => 'AST-1', 'nama' => 'Laptop',
            'tanggal_perolehan' => now()->subYear(), 'harga_perolehan' => 10_000_000, 'umur_ekonomis_bulan' => 48,
            'akumulasi_susut' => 4_000_000, 'nilai_buku' => 6_000_000,
            'coa_aset_id' => Coa::where('kode', '1.2.1.01')->first()->id,
            'coa_akumulasi_id' => Coa::where('kode', '1.2.9.01')->first()->id,
            'status' => 'aktif']);

        AssetService::lepas($aset, 7_000_000, $this->kas->id, 'dijual');

        $this->assertEquals('dijual', $aset->refresh()->status);
        $j = Jurnal::where('referensi_type', Asset::class)->first();
        $this->assertNotNull($j);
        $this->assertTrue($j->isBalanced());
        // kas 7jt debit, akumulasi 4jt debit, aset 10jt kredit, laba 1jt kredit
        $this->assertEquals(11_000_000, $j->total_debit);
    }

    public function test_lepas_aset_rugi(): void
    {
        $aset = Asset::create(['tenant_id' => $this->tenant->id, 'kode' => 'AST-2', 'nama' => 'Motor',
            'tanggal_perolehan' => now()->subYear(), 'harga_perolehan' => 10_000_000, 'umur_ekonomis_bulan' => 48,
            'akumulasi_susut' => 2_000_000, 'nilai_buku' => 8_000_000,
            'coa_aset_id' => Coa::where('kode', '1.2.1.01')->first()->id,
            'coa_akumulasi_id' => Coa::where('kode', '1.2.9.01')->first()->id,
            'status' => 'aktif']);

        AssetService::lepas($aset, 5_000_000, $this->kas->id, 'dijual');

        $j = Jurnal::where('referensi_type', Asset::class)->first();
        $this->assertTrue($j->isBalanced());
    }

    public function test_lepas_aset_nonaktif_ditolak(): void
    {
        $aset = Asset::create(['tenant_id' => $this->tenant->id, 'kode' => 'AST-3', 'nama' => 'X',
            'tanggal_perolehan' => now()->subYear(), 'harga_perolehan' => 1, 'umur_ekonomis_bulan' => 12, 'nilai_buku' => 1,
            'coa_aset_id' => Coa::where('kode', '1.2.1.01')->first()->id, 'status' => 'dijual']);

        $this->expectException(\InvalidArgumentException::class);
        AssetService::lepas($aset, 1, $this->kas->id);
    }

    public function test_anggaran_workflow_dan_laporan(): void
    {
        $coa = Coa::create(['tenant_id' => $this->tenant->id, 'kode' => '5.1.1.01', 'nama' => 'Beban Gaji', 'tipe' => 'beban', 'saldo_normal' => 'debit', 'is_postable' => true, 'is_aktif' => true]);
        $a = Anggaran::create(['tenant_id' => $this->tenant->id, 'tahun' => 2026, 'coa_id' => $coa->id, 'jan' => 1_000_000, 'status' => 'draft']);

        // Draft tidak masuk laporan.
        $def = ReportRegistry::find('anggaran-realisasi');
        $r = $def->run(['tahun' => 2026]);
        $this->assertCount(0, $r->rows);

        $a->update(['status' => 'approved']);
        $r = $def->run(['tahun' => 2026]);
        $this->assertCount(1, $r->rows);
        $this->assertEquals(1_000_000, $r->rows[0]['rencana']);
    }
}
