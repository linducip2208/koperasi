<?php

namespace Tests\Feature;

use App\Domain\Akuntansi\JurnalService;
use App\Domain\Syariah\ProfitSharingCalculator;
use App\Imports\ImportEngine;
use App\Imports\IndonesianNumber;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\CustomReport;
use App\Models\Tenant;
use App\Reports\CustomReportRunner;
use App\Reports\ReportArchiver;
use App\Reports\ReportRegistry;
use App\Services\Ai\AiManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Tenant::create(['nama' => 'Test Koperasi', 'email' => 't@k.local', 'status' => 'aktif']);
    }

    public function test_registry_memuat_30_report(): void
    {
        $this->assertGreaterThanOrEqual(30, count(ReportRegistry::all()));
    }

    public function test_neraca_balance_dari_jurnal_nyata(): void
    {
        $coaKas = Coa::create(['tenant_id' => 1, 'kode' => '1.1.1.01', 'nama' => 'Kas', 'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_kas' => true, 'is_postable' => true, 'is_aktif' => true]);
        $coaModal = Coa::create(['tenant_id' => 1, 'kode' => '3.1.1.01', 'nama' => 'Modal', 'tipe' => 'ekuitas', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);

        JurnalService::create('Setor modal', [
            ['coa_id' => $coaKas->id, 'debit' => 10_000_000, 'kredit' => 0],
            ['coa_id' => $coaModal->id, 'debit' => 0, 'kredit' => 10_000_000],
        ]);

        $def = ReportRegistry::find('neraca');
        $r = $def->run(['sampai' => now()->toDateString(), 'cabang_id' => null]);
        $this->assertEquals(10_000_000, $r->meta['total_aset']);
        $this->assertEquals(10_000_000, $r->meta['total_ekuitas']);
    }

    public function test_profit_sharing_deterministik_tanpa_float(): void
    {
        $pool = 10_000_000;
        $peserta = [
            ['id' => 1, 'saldo' => 5_000_000, 'nisbah_bp' => 6000, 'days' => 30, 'period_days' => 30],
            ['id' => 2, 'saldo' => 5_000_000, 'nisbah_bp' => 6000, 'days' => 30, 'period_days' => 30],
        ];
        $h1 = ProfitSharingCalculator::distribute($pool, $peserta);
        $h2 = ProfitSharingCalculator::distribute($pool, $peserta);
        $this->assertEquals($h1, $h2);
        $this->assertEquals($pool, $h1['total_anggota'] + $h1['total_koperasi']);
        $this->assertEquals(3_000_000, $h1['baris'][0]['bagi_hasil']);
        $this->assertEquals(3_000_000, $h1['baris'][1]['bagi_hasil']);
    }

    public function test_profit_sharing_nol_dan_partial_period(): void
    {
        $h = ProfitSharingCalculator::distribute(0, [['id' => 1, 'saldo' => 100, 'nisbah_bp' => 5000, 'days' => 1, 'period_days' => 30]]);
        $this->assertEquals(0, $h['total_anggota']);

        // Setengah periode → setengah bobot relatif.
        $h = ProfitSharingCalculator::distribute(1_000_000, [
            ['id' => 1, 'saldo' => 10_000_000, 'nisbah_bp' => 10000, 'days' => 30, 'period_days' => 30],
            ['id' => 2, 'saldo' => 10_000_000, 'nisbah_bp' => 10000, 'days' => 15, 'period_days' => 30],
        ]);
        $this->assertGreaterThan($h['baris'][1]['bagi_hasil'], $h['baris'][0]['bagi_hasil']);
        $this->assertEquals(1_000_000, $h['total_anggota'] + $h['total_koperasi']);
    }

    public function test_angka_indonesia_dipahami(): void
    {
        $this->assertEquals(1250000, IndonesianNumber::toInt('Rp 1.250.000'));
        $this->assertEquals(1250001, IndonesianNumber::toInt('1.250.000,50'));
        $this->assertEquals(1250000, IndonesianNumber::toInt('1.250.000'));
        $this->assertEquals('2026-03-15', IndonesianNumber::toDate('15/03/2026'));
        $this->assertNull(IndonesianNumber::toInt('bukan-angka'));
    }

    public function test_import_csv_valid_invalid_duplikat(): void
    {
        $rows = [
            ['Nama Lengkap', 'NIK', 'Telepon'],
            ['Budi', '111', '0811'],
            ['Ani', 'zzz', '0822'], // valid (nik bebas, telp bebas)
            ['', '222', '0833'], // invalid: nama wajib
            ['Budi 2', '111', '0844'], // duplikat NIK dalam file
        ];
        $r = ImportEngine::validate('members', $rows);
        $this->assertCount(2, $r['valid']);
        $this->assertCount(2, $r['invalid']);
        $this->assertEquals(1, $r['duplicate']);
    }

    public function test_import_gagal_rollback_total(): void
    {
        $this->expectException(\Throwable::class);
        DB::transaction(function () {
            Anggota::create(['tenant_id' => 1, 'nomor_anggota' => 'AGT-RB', 'nama' => 'Rollback', 'status' => 'aktif', 'tanggal_masuk' => now()]);
            throw new \RuntimeException('simulasi gagal');
        });
        $this->assertDatabaseMissing('anggota', ['nomor_anggota' => 'AGT-RB']);
    }

    public function test_archive_snapshot_immutable(): void
    {
        $coaKas = Coa::create(['tenant_id' => 1, 'kode' => '1.1.1.01', 'nama' => 'Kas', 'tipe' => 'aset', 'saldo_normal' => 'debit', 'is_kas' => true, 'is_postable' => true, 'is_aktif' => true]);
        $coaModal = Coa::create(['tenant_id' => 1, 'kode' => '3.1.1.01', 'nama' => 'Modal', 'tipe' => 'ekuitas', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]);
        JurnalService::create('M1', [
            ['coa_id' => $coaKas->id, 'debit' => 5_000_000, 'kredit' => 0],
            ['coa_id' => $coaModal->id, 'debit' => 0, 'kredit' => 5_000_000],
        ]);

        $params = ['sampai' => now()->toDateString(), 'cabang_id' => null];
        $arsip = ReportArchiver::archive('neraca', $params, 'Neraca Test');
        $this->assertTrue($arsip->verify());
        $sebelum = $arsip->snapshot;

        // Transaksi baru masuk SETELAH arsip.
        JurnalService::create('M2', [
            ['coa_id' => $coaKas->id, 'debit' => 1_000_000, 'kredit' => 0],
            ['coa_id' => $coaModal->id, 'debit' => 0, 'kredit' => 1_000_000],
        ]);

        $arsip->refresh();
        $this->assertEquals($sebelum, $arsip->snapshot);
        $this->assertTrue($arsip->verify());
        $baca = ReportArchiver::read($arsip);
        $this->assertEquals(5_000_000, $baca->meta['total_aset']);
    }

    public function test_custom_builder_tolak_source_asing(): void
    {
        $rec = CustomReport::create([
            'name' => 'X', 'data_source' => 'users', 'columns' => [['field' => 'password']],
            'filters' => [], 'limit' => 10,
        ]);
        $this->expectException(\InvalidArgumentException::class);
        CustomReportRunner::run($rec);
    }

    public function test_custom_builder_whitelist_berjalan(): void
    {
        Anggota::create(['tenant_id' => 1, 'nomor_anggota' => 'AGT-1', 'nama' => 'Coba', 'status' => 'aktif', 'tanggal_masuk' => now()]);
        $rec = CustomReport::create([
            'name' => 'Anggota aktif', 'data_source' => 'members',
            'columns' => [['field' => 'nama'], ['field' => 'status']],
            'filters' => [['field' => 'status', 'operator' => '=', 'value' => 'aktif']],
            'limit' => 100,
        ]);
        $r = CustomReportRunner::run($rec);
        $this->assertCount(1, $r->rows);
    }

    public function test_ai_tidak_bocorkan_secret(): void
    {
        $out = AiManager::explain('test', ['api_key' => 'SECRET-123', 'nik' => '1234567890123456', 'summary' => 'ok']);
        $this->assertStringNotContainsString('SECRET-123', $out);
        $this->assertStringNotContainsString('1234567890123456', $out);
        $this->assertStringContainsString('AI-generated insight', $out);
    }

    public function test_api_reports_butuh_auth(): void
    {
        $this->getJson('/api/v1/reports')->assertUnauthorized();
        $this->postJson('/api/v1/reports/neraca/run')->assertUnauthorized();
    }
}
