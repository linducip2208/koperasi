<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * Seed semua tabel master/operasional ke minimal 100 records.
 * Tabel anggota/users/simpanan/pinjaman yang sudah ribuan records di-skip.
 */
class SeedToHundred extends Command
{
    protected $signature = 'koperasi:seed-100 {--target=100 : Minimum records per tabel}';
    protected $description = 'Seed semua tabel agar minimal 100 records';

    private int $target = 100;
    private int $tenantId = 1;

    public function handle(): int
    {
        $this->target = (int) $this->option('target');
        $this->info("🌱 Seed semua tabel ke minimum {$this->target} records...");

        $this->seedCabang();
        $this->seedKas();
        $this->seedProdukSimpanan();
        $this->seedProdukPinjaman();
        $this->seedKaryawan();
        $this->seedAsset();
        $this->seedTokoKategori();
        $this->seedTokoSupplier();
        $this->seedTokoBarang();
        $this->seedUnitJasaLayanan();
        $this->seedUnitProdusenKomoditi();
        $this->seedTagihanMaster();
        $this->seedAhliWaris();
        $this->seedAnggotaStatusLog();
        $this->seedPinjamanJaminan();
        $this->seedPinjamanApproval();
        $this->seedRat();
        $this->seedShuPerhitungan();
        $this->seedShuDistribusi();
        $this->seedTagihan();
        $this->seedTokoPenjualan();
        $this->seedTokoPembelian();
        $this->seedUnitJasaOrder();
        $this->seedUnitProdusenSetoran();
        $this->seedPaymentProviders();
        $this->seedActivityLog();
        $this->seedCoa();
        $this->seedAsuransiProduk();
        $this->seedAsuransiPolis();
        $this->seedAsuransiKlaim();
        $this->seedGaji();
        $this->seedAnggaran();
        $this->seedRekonsiliasiBank();
        $this->seedKasOpname();
        $this->seedPinjamanRestrukturisasi();
        $this->seedPeriodeAkuntansi();
        $this->seedNotifikasiTemplate();
        $this->seedNumberingSetting();
        $this->seedSimpananBlokir();

        $this->info('✅ Selesai. Cek hasil di /admin atau via mysql.');
        return self::SUCCESS;
    }

    private function seedAsuransiProduk(): void
    {
        $jenis = ['jiwa', 'kredit', 'kesehatan', 'kebakaran', 'kendaraan', 'tabungan', 'mikro'];
        $this->topUp('asuransi_produk', function ($i) use ($jenis) {
            return [
                'tenant_id' => 1,
                'nama'      => 'Produk Asuransi ' . ucfirst($jenis[($i - 1) % count($jenis)]) . ' #' . $i,
                'penanggung' => ['Jasindo', 'Bumida', 'Askrida', 'Tugu Mandiri', 'BNI Life'][($i - 1) % 5],
                'jenis'     => $jenis[($i - 1) % count($jenis)],
                'rate_premi' => round(rand(10, 200) / 100, 4),
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAsuransiPolis(): void
    {
        $produkIds = \DB::table('asuransi_produk')->pluck('id')->toArray();
        $anggotaIds = \DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $this->topUp('asuransi_polis', function ($i) use ($produkIds, $anggotaIds) {
            $nilai = rand(5_000_000, 200_000_000);
            return [
                'tenant_id' => 1,
                'produk_id' => $produkIds[($i - 1) % count($produkIds)],
                'pinjaman_id' => null,
                'anggota_id' => $anggotaIds[($i - 1) % count($anggotaIds)],
                'nomor_polis' => 'POL-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nilai_pertanggungan' => $nilai,
                'premi'     => (int) ($nilai * 0.005),
                'tanggal_mulai' => now()->subDays(rand(0, 365))->toDateString(),
                'tanggal_akhir' => now()->addDays(rand(30, 365))->toDateString(),
                'status'    => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAsuransiKlaim(): void
    {
        $polisIds = \DB::table('asuransi_polis')->pluck('id')->toArray();
        if (empty($polisIds)) return;
        $this->topUp('asuransi_klaim', function ($i) use ($polisIds) {
            $nilai = rand(1_000_000, 50_000_000);
            $status = ['pengajuan', 'verifikasi', 'disetujui', 'dibayar', 'ditolak'][($i - 1) % 5];
            return [
                'tenant_id' => 1,
                'polis_id'  => $polisIds[($i - 1) % count($polisIds)],
                'tanggal_kejadian' => now()->subDays(rand(10, 365))->toDateString(),
                'tanggal_pengajuan' => now()->subDays(rand(0, 9))->toDateString(),
                'nilai_klaim' => $nilai,
                'uraian'    => 'Klaim demo seed #' . $i,
                'status'    => $status,
                'nilai_diterima' => in_array($status, ['disetujui', 'dibayar']) ? (int) ($nilai * 0.85) : 0,
                'tanggal_diterima' => $status === 'dibayar' ? now()->subDays(rand(0, 5))->toDateString() : null,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedGaji(): void
    {
        $karyawanIds = \DB::table('karyawan')->limit(50)->pluck('id')->toArray();
        $this->topUp('gaji', function ($i) use ($karyawanIds) {
            $pokok = rand(3_000_000, 12_000_000);
            $tunj = (int) ($pokok * 0.3);
            $bruto = $pokok + $tunj;
            $potongan = (int) ($bruto * 0.05);
            return [
                'tenant_id' => 1,
                'karyawan_id' => $karyawanIds[($i - 1) % count($karyawanIds)],
                'tahun'     => 2025 - intdiv($i - 1, 12),
                'bulan'     => (($i - 1) % 12) + 1,
                'gaji_pokok' => $pokok,
                'total_tunjangan' => $tunj,
                'lembur'    => 0,
                'total_potongan' => 0,
                'pph21'     => (int) ($bruto * 0.025),
                'bpjs_potongan' => (int) ($bruto * 0.025),
                'total_bruto' => $bruto,
                'total_netto' => $bruto - $potongan,
                'tanggal_bayar' => now()->subDays(rand(0, 365))->toDateString(),
                'status'    => 'paid',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAnggaran(): void
    {
        $coaIds = \DB::table('coa')->whereIn('tipe', ['pendapatan', 'beban'])->pluck('id')->toArray();
        $this->topUp('anggaran', function ($i) use ($coaIds) {
            $base = rand(5_000_000, 50_000_000);
            return [
                'tenant_id' => 1,
                'tahun'     => 2024 + (($i - 1) % 3),
                'coa_id'    => $coaIds[($i - 1) % count($coaIds)],
                'jan' => $base, 'feb' => $base, 'mar' => $base, 'apr' => $base,
                'mei' => $base, 'jun' => $base, 'jul' => $base, 'agu' => $base,
                'sep' => $base, 'okt' => $base, 'nov' => $base, 'des' => $base,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedRekonsiliasiBank(): void
    {
        $kasIds = \DB::table('kas')->where('tipe', 'bank')->pluck('id')->toArray();
        if (empty($kasIds)) $kasIds = \DB::table('kas')->limit(10)->pluck('id')->toArray();
        $this->topUp('rekonsiliasi_bank', function ($i) use ($kasIds) {
            $buku = rand(50_000_000, 500_000_000);
            $bank = $buku + rand(-500000, 500000);
            return [
                'tenant_id' => 1,
                'kas_id'    => $kasIds[($i - 1) % count($kasIds)],
                'tanggal'   => now()->subDays(rand(0, 365))->toDateString(),
                'periode_akhir' => now()->subDays(rand(0, 30))->endOfMonth()->toDateString(),
                'saldo_buku' => $buku,
                'saldo_bank' => $bank,
                'selisih'   => $bank - $buku,
                'status'    => $bank === $buku ? 'matched' : 'pending',
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedKasOpname(): void
    {
        $kasIds = \DB::table('kas')->where('tipe', 'kas')->pluck('id')->toArray();
        if (empty($kasIds)) $kasIds = \DB::table('kas')->limit(10)->pluck('id')->toArray();
        $this->topUp('kas_opname', function ($i) use ($kasIds) {
            $sistem = rand(5_000_000, 50_000_000);
            $fisik = $sistem + rand(-50000, 50000);
            return [
                'tenant_id' => 1,
                'kas_id'    => $kasIds[($i - 1) % count($kasIds)],
                'tanggal'   => now()->subDays(rand(0, 365))->toDateString(),
                'saldo_sistem' => $sistem,
                'saldo_fisik' => $fisik,
                'selisih'   => $fisik - $sistem,
                'catatan'   => $fisik !== $sistem ? 'Selisih kecil, sudah dicocokkan ke kas kecil' : 'OK',
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedPinjamanRestrukturisasi(): void
    {
        $pinjamanIds = \DB::table('pinjaman')->limit(200)->pluck('id')->toArray();
        $jenis = ['rescheduling', 'restructuring', 'reconditioning', 'top_up', 'haircut'];
        $this->topUp('pinjaman_restrukturisasi', function ($i) use ($pinjamanIds, $jenis) {
            return [
                'tenant_id' => 1,
                'pinjaman_id' => $pinjamanIds[($i - 1) % count($pinjamanIds)],
                'tanggal'   => now()->subDays(rand(0, 365))->toDateString(),
                'jenis'     => $jenis[($i - 1) % count($jenis)],
                'sebelum'   => json_encode(['plafon' => 10000000, 'tenor' => 12, 'margin' => 12]),
                'sesudah'   => json_encode(['plafon' => 10000000, 'tenor' => 24, 'margin' => 9]),
                'alasan'    => 'Anggota mengalami kesulitan pembayaran karena force majeure / penurunan pendapatan',
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedPeriodeAkuntansi(): void
    {
        $this->topUp('periode_akuntansi', function ($i) {
            $tahun = 2018 + intdiv($i - 1, 12);
            $bulan = (($i - 1) % 12) + 1;
            $start = sprintf('%d-%02d-01', $tahun, $bulan);
            $end = date('Y-m-t', strtotime($start));
            return [
                'tenant_id' => 1,
                'tahun'     => $tahun,
                'bulan'     => $bulan,
                'tanggal_mulai' => $start,
                'tanggal_akhir' => $end,
                'status'    => $tahun < 2025 ? 'closed' : 'open',
                'closed_at' => $tahun < 2025 ? now()->subDays(rand(30, 365)) : null,
                'closed_by' => $tahun < 2025 ? 1 : null,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedNotifikasiTemplate(): void
    {
        $events = ['setoran_simpanan', 'penarikan_simpanan', 'pencairan_pinjaman', 'cicilan_diterima', 'cicilan_jatuh_tempo', 'undangan_rat', 'pengumuman', 'shu_distribusi', 'ulang_tahun'];
        $channels = ['whatsapp', 'email', 'sms', 'push'];
        $this->topUp('notifikasi_template', function ($i) use ($events, $channels) {
            return [
                'tenant_id' => 1,
                'kode'      => 'tpl_' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => 'Template ' . ($events[($i - 1) % count($events)]) . ' #' . $i,
                'event'     => $events[($i - 1) % count($events)],
                'channel'   => $channels[($i - 1) % count($channels)],
                'subject'   => 'Subject untuk template #' . $i,
                'body'      => "Halo {nama},\n\nIni adalah template notifikasi default. Edit sesuai kebutuhan.\n\nSalam,\nKoperasi",
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedNumberingSetting(): void
    {
        $kodeList = ['SETORAN', 'PENARIKAN', 'PINJAMAN', 'PEMBAYARAN', 'JURNAL', 'INVOICE', 'PEMBELIAN', 'PENJUALAN', 'TAGIHAN', 'GAJI', 'POLIS', 'KLAIM'];
        $this->topUp('numbering_setting', function ($i) use ($kodeList) {
            return [
                'tenant_id'   => 1,
                'kode'        => $kodeList[($i - 1) % count($kodeList)] . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'prefix'      => substr($kodeList[($i - 1) % count($kodeList)], 0, 3),
                'format'      => '{prefix}-{ymd}-{seq:5}',
                'panjang_seq' => 5,
                'reset_period' => 2,
                'next_number' => rand(1, 1000),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        });
    }

    private function seedSimpananBlokir(): void
    {
        $simpananIds = \DB::table('simpanan')->limit(200)->pluck('id')->toArray();
        $alasan = ['Jaminan Pinjaman', 'Klaim Asuransi', 'Sengketa', 'Audit Pajak', 'Tahan Sementara'];
        $this->topUp('simpanan_blokir', function ($i) use ($simpananIds, $alasan) {
            return [
                'tenant_id' => 1,
                'simpanan_id' => $simpananIds[($i - 1) % count($simpananIds)],
                'jumlah'    => rand(500_000, 10_000_000),
                'alasan'    => $alasan[($i - 1) % count($alasan)] . ' #' . $i,
                'tanggal_blokir' => now()->subDays(rand(0, 365))->toDateString(),
                'tanggal_lepas' => $i % 3 === 0 ? now()->subDays(rand(0, 30))->toDateString() : null,
                'aktif'     => $i % 3 !== 0,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedCoa(): void
    {
        $items = [
            ['8-1100', 'Kas Tambahan', 'aset', 'debit'],
            ['8-1200', 'Bank Tambahan', 'aset', 'debit'],
            ['8-1300', 'Piutang Lain-lain', 'aset', 'debit'],
            ['8-1400', 'Persediaan Tambahan', 'aset', 'debit'],
            ['8-2100', 'Hutang Lain-lain', 'kewajiban', 'kredit'],
            ['8-2200', 'Pendapatan Diterima Dimuka', 'kewajiban', 'kredit'],
            ['8-3100', 'Modal Tambahan', 'ekuitas', 'kredit'],
            ['8-4100', 'Pendapatan Lain-lain', 'pendapatan', 'kredit'],
            ['8-4200', 'Pendapatan Bunga Bank', 'pendapatan', 'kredit'],
            ['8-5100', 'Beban Lain-lain', 'beban', 'debit'],
            ['8-5200', 'Beban Penyusutan', 'beban', 'debit'],
        ];
        $this->topUp('coa', function ($i) use ($items) {
            $idx = ($i - 89 - 1);
            if ($idx < 0 || $idx >= count($items)) {
                $idx = $idx % count($items);
            }
            $it = $items[$idx];
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => $it[0] . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama'      => $it[1] . ' #' . $i,
                'tipe'      => $it[2],
                'saldo_normal' => $it[3],
                'is_postable' => 1,
                'is_kas'    => 0,
                'is_bank'   => 0,
                'is_aktif'  => 1,
                'saldo_awal' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function topUp(string $table, callable $rowGenerator): void
    {
        $existing = DB::table($table)->count();
        $needed = max(0, $this->target - $existing);
        if ($needed === 0) {
            $this->line("  ⏭  {$table}: sudah {$existing} records");
            return;
        }

        $rows = [];
        for ($i = 0; $i < $needed; $i++) {
            $rows[] = $rowGenerator($i + $existing + 1);
            if (count($rows) >= 500) {
                DB::table($table)->insert($rows);
                $rows = [];
            }
        }
        if ($rows) DB::table($table)->insert($rows);
        $this->info("  ✓ {$table}: +{$needed} → " . DB::table($table)->count());
    }

    private function seedCabang(): void
    {
        $kotaList = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi', 'Yogyakarta', 'Malang', 'Denpasar', 'Padang', 'Pekanbaru', 'Banjarmasin', 'Samarinda', 'Pontianak', 'Manado', 'Bandar Lampung', 'Bogor', 'Solo', 'Cirebon', 'Jambi', 'Mataram'];
        $tipe = ['kantor_pusat', 'cabang', 'cabang_pembantu', 'kantor_kas'];

        $this->topUp('cabang', function ($i) use ($kotaList, $tipe) {
            $kota = $kotaList[($i - 1) % count($kotaList)];
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'CAB-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => 'Cabang ' . $kota . ' ' . $i,
                'alamat'    => "Jl. Merdeka No. {$i}, {$kota}",
                'telp'      => '021-' . rand(1000000, 9999999),
                'tipe'      => $tipe[($i - 1) % count($tipe)],
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedKas(): void
    {
        $coaKas = DB::table('coa')->where('is_kas', true)->orWhere('is_bank', true)->pluck('id')->toArray();
        if (empty($coaKas)) $coaKas = DB::table('coa')->where('tipe', 'aset')->limit(5)->pluck('id')->toArray();

        $this->topUp('kas', function ($i) use ($coaKas) {
            $isBank = $i % 3 === 0;
            return [
                'tenant_id' => $this->tenantId,
                'cabang_id' => 1,
                'kode'      => ($isBank ? 'BNK' : 'KAS') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => $isBank ? "Bank {$i}" : "Kas Operasional {$i}",
                'tipe'      => $isBank ? 'bank' : 'kas',
                'nomor_rekening' => $isBank ? '1234' . str_pad($i, 8, '0', STR_PAD_LEFT) : null,
                'nama_bank' => $isBank ? ['BCA', 'Mandiri', 'BNI', 'BRI'][$i % 4] : null,
                'atas_nama' => $isBank ? 'Koperasi Demo' : null,
                'coa_id'    => $coaKas[($i - 1) % count($coaKas)] ?? 1,
                'saldo_awal' => rand(5_000_000, 100_000_000),
                'saldo'     => rand(1_000_000, 80_000_000),
                'limit_minimum' => 1_000_000,
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedProdukSimpanan(): void
    {
        $jenis = ['pokok', 'wajib', 'sukarela', 'berjangka'];
        $this->topUp('produk_simpanan', function ($i) use ($jenis) {
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'SIM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => 'Simpanan ' . ['Pokok', 'Wajib', 'Sukarela', 'Berjangka', 'Pendidikan', 'Lebaran', 'Qurban', 'Haji', 'Umroh', 'Pensiun'][($i - 1) % 10] . ' ' . $i,
                'jenis'     => $jenis[($i - 1) % count($jenis)],
                'akad_type' => $i % 2 === 0 ? 'wadiah' : 'mudharabah',
                'setoran_minimum'  => 50000 * (($i % 5) + 1),
                'setoran_wajib'    => $i % 3 === 0 ? 50000 : 0,
                'saldo_minimum'    => 0,
                'bunga_persen_tahun' => round(rand(100, 600) / 100, 4),
                'metode_bunga'     => 'harian',
                'nisbah_anggota'   => $i % 2 === 0 ? 0 : 60,
                'nisbah_koperasi'  => $i % 2 === 0 ? 0 : 40,
                'boleh_tarik'      => 1,
                'berjangka'        => $jenis[($i - 1) % count($jenis)] === 'berjangka' ? 1 : 0,
                'tenor_bulan'      => $jenis[($i - 1) % count($jenis)] === 'berjangka' ? [3, 6, 12, 24][($i - 1) % 4] : null,
                'auto_potong_shu'  => 0,
                'aktif'            => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedProdukPinjaman(): void
    {
        $akad = ['konvensional', 'murabahah', 'mudharabah', 'musyarakah', 'ijarah', 'ijarah_mb', 'qardh', 'rahn', 'salam', 'istishna', 'wakalah', 'kafalah', 'hawalah'];
        $metode = ['flat', 'efektif', 'anuitas', 'murabahah', 'mudharabah', 'musyarakah', 'ijarah', 'ijarah_mb', 'qardh', 'rahn'];

        $this->topUp('produk_pinjaman', function ($i) use ($akad, $metode) {
            $a = $akad[($i - 1) % count($akad)];
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'PNJ-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => 'Pembiayaan ' . ucfirst($a) . ' ' . $i,
                'jenis'     => ['produktif', 'konsumtif', 'multiguna'][($i - 1) % 3],
                'akad_type' => $a,
                'metode_perhitungan' => $a === 'konvensional' ? $metode[($i - 1) % 3] : $a,
                'plafon_minimum'  => 500_000,
                'plafon_maksimum' => 100_000_000,
                'tenor_minimum'   => 1,
                'tenor_maksimum'  => 60,
                'bunga_persen'    => $a === 'konvensional' ? round(rand(100, 200) / 100, 4) : 0,
                'margin_persen'   => $a !== 'konvensional' ? round(rand(800, 1800) / 100, 4) : 0,
                'biaya_admin_persen'   => 0.5,
                'biaya_admin_flat'     => 50000,
                'biaya_provisi_persen' => 1.0,
                'biaya_asuransi_persen'=> 0.25,
                'biaya_materai'        => 10000,
                'denda_persen_per_hari'=> 0.05,
                'butuh_jaminan'        => 1,
                'butuh_simpanan_blokir'=> $i % 4 === 0,
                'aktif'                => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedKaryawan(): void
    {
        $jabatan = ['Manager Cabang', 'Teller', 'AO Pinjaman', 'Customer Service', 'Akuntan', 'Auditor Internal', 'IT Support', 'Security', 'Driver', 'Office Boy'];
        $departemen = ['Operasional', 'Pinjaman', 'Akuntansi', 'IT', 'HRD', 'GA'];
        $this->topUp('karyawan', function ($i) use ($jabatan, $departemen) {
            return [
                'tenant_id' => $this->tenantId,
                'cabang_id' => (($i - 1) % 5) + 1,
                'nip'       => 'KRY-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'nama'      => 'Karyawan Demo ' . $i,
                'jabatan'   => $jabatan[($i - 1) % count($jabatan)],
                'departemen' => $departemen[($i - 1) % count($departemen)],
                'tanggal_masuk' => now()->subDays(rand(30, 3650))->toDateString(),
                'gaji_pokok' => rand(3_000_000, 12_000_000),
                'tunjangan' => json_encode(['transport' => 500000, 'makan' => 500000]),
                'status'    => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAsset(): void
    {
        $kategori = ['Bangunan', 'Kendaraan', 'Komputer', 'Furniture', 'Mesin Kantor', 'Tanah'];
        $coaIds = DB::table('coa')->where('tipe', 'aset')->pluck('id')->toArray();
        $this->topUp('asset', function ($i) use ($kategori, $coaIds) {
            $harga = rand(5_000_000, 200_000_000);
            return [
                'tenant_id' => $this->tenantId,
                'cabang_id' => (($i - 1) % 5) + 1,
                'kode'      => 'AST-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'nama'      => $kategori[($i - 1) % count($kategori)] . ' ' . $i,
                'kategori'  => strtolower($kategori[($i - 1) % count($kategori)]),
                'tanggal_perolehan' => now()->subYears(rand(0, 5))->toDateString(),
                'harga_perolehan'   => $harga,
                'nilai_residu'      => (int) ($harga * 0.1),
                'umur_ekonomis_bulan' => rand(48, 240),
                'metode_susut'      => 'garis_lurus',
                'akumulasi_susut'   => (int) ($harga * (rand(0, 60) / 100)),
                'nilai_buku'        => $harga,
                'coa_aset_id'       => $coaIds[0] ?? 1,
                'status'            => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTokoKategori(): void
    {
        $namaList = ['Sembako', 'Minuman', 'Makanan Ringan', 'Alat Tulis', 'Pertanian', 'Peternakan', 'Elektronik', 'Pakaian', 'Kesehatan', 'Bayi & Anak', 'Rumah Tangga', 'Otomotif', 'Olahraga', 'Buku', 'Kosmetik'];
        $this->topUp('toko_kategori', function ($i) use ($namaList) {
            return [
                'tenant_id' => $this->tenantId,
                'parent_id' => null,
                'nama'      => $namaList[($i - 1) % count($namaList)] . ' #' . $i,
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTokoSupplier(): void
    {
        $this->topUp('toko_supplier', function ($i) {
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'SUP-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => 'Supplier Demo ' . $i . ' (CV/PT)',
                'telp'      => '021-' . rand(1000000, 9999999),
                'email'     => "supplier{$i}@demo.local",
                'alamat'    => "Jl. Industri No. {$i}",
                'npwp'      => '01.' . rand(100, 999) . '.' . rand(100, 999) . '.0-' . rand(100, 999) . '.000',
                'saldo_hutang' => rand(0, 10_000_000),
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTokoBarang(): void
    {
        $kategoriIds = DB::table('toko_kategori')->pluck('id')->toArray();
        $satuanIds = DB::table('toko_satuan')->pluck('id')->toArray();
        if (empty($satuanIds)) {
            DB::table('toko_satuan')->insert([
                ['tenant_id' => 1, 'kode' => 'PCS', 'nama' => 'Pieces', 'created_at' => now(), 'updated_at' => now()],
                ['tenant_id' => 1, 'kode' => 'KG', 'nama' => 'Kilogram', 'created_at' => now(), 'updated_at' => now()],
                ['tenant_id' => 1, 'kode' => 'BOX', 'nama' => 'Box', 'created_at' => now(), 'updated_at' => now()],
                ['tenant_id' => 1, 'kode' => 'PACK', 'nama' => 'Pack', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $satuanIds = DB::table('toko_satuan')->pluck('id')->toArray();
        }
        $brand = ['Indomie', 'Aqua', 'Teh Botol', 'Kopiko', 'Tropicana', 'Marie Regal', 'Khong Guan', 'SariWangi', 'Pepsodent', 'Lifebuoy', 'Tessa', 'Mama Lemon', 'So Klin', 'Rinso', 'Generic'];
        $this->topUp('toko_barang', function ($i) use ($kategoriIds, $satuanIds, $brand) {
            $beli = rand(2000, 50000);
            return [
                'tenant_id' => $this->tenantId,
                'sku'       => 'SKU-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'barcode'   => '8990' . str_pad($i, 9, '0', STR_PAD_LEFT) . rand(0, 9),
                'nama'      => $brand[($i - 1) % count($brand)] . ' Produk ' . $i,
                'kategori_id' => $kategoriIds[($i - 1) % count($kategoriIds)] ?? null,
                'satuan_id' => $satuanIds[($i - 1) % count($satuanIds)] ?? null,
                'brand'     => $brand[($i - 1) % count($brand)],
                'harga_beli' => $beli,
                'harga_jual_umum'    => (int) ($beli * 1.2),
                'harga_jual_anggota' => (int) ($beli * 1.1),
                'harga_jual_grosir'  => (int) ($beli * 1.05),
                'stok'        => rand(10, 500),
                'stok_minimum' => 10,
                'stok_maksimum' => 1000,
                'metode_hpp'  => 'average',
                'is_jasa'     => 0,
                'aktif'       => 1,
                'created_at'  => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedUnitJasaLayanan(): void
    {
        $jasa = ['Cuci Motor', 'Cuci Mobil', 'Service AC', 'Setrika Pakaian', 'Cuci Karpet', 'Salon Rambut', 'Cuci Sepatu', 'Antar Jemput Sekolah', 'Sewa Tenda', 'Sewa Kursi', 'Foto Wedding', 'Foto Studio', 'Service HP', 'Reparasi Elektronik', 'Catering', 'Laundry Kiloan'];
        $this->topUp('unit_jasa_layanan', function ($i) use ($jasa) {
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'JSA-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => $jasa[($i - 1) % count($jasa)] . ' Paket ' . (($i - 1) % 4 + 1),
                'tarif'     => rand(15000, 250000),
                'satuan_tarif' => ['/unit', '/jam', '/paket', '/orang'][($i - 1) % 4],
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedUnitProdusenKomoditi(): void
    {
        $komoditi = [
            ['Susu Sapi', 'peternakan', 'liter'], ['Susu Kambing', 'peternakan', 'liter'],
            ['Telur Ayam', 'peternakan', 'kg'], ['Daging Sapi', 'peternakan', 'kg'],
            ['Beras', 'pertanian', 'kg'], ['Jagung', 'pertanian', 'kg'],
            ['Kedelai', 'pertanian', 'kg'], ['Cabai', 'pertanian', 'kg'],
            ['Tomat', 'pertanian', 'kg'], ['Bawang Merah', 'pertanian', 'kg'],
            ['Kopi Arabika', 'perkebunan', 'kg'], ['Kakao', 'perkebunan', 'kg'],
            ['Karet Lateks', 'perkebunan', 'kg'], ['Kelapa Sawit', 'perkebunan', 'kg'],
            ['Ikan Lele', 'perikanan', 'kg'], ['Ikan Nila', 'perikanan', 'kg'],
            ['Udang', 'perikanan', 'kg'], ['Anyaman Bambu', 'kerajinan', 'unit'],
            ['Batik Tulis', 'kerajinan', 'meter'], ['Tas Rotan', 'kerajinan', 'unit'],
        ];
        $this->topUp('unit_produsen_komoditi', function ($i) use ($komoditi) {
            $k = $komoditi[($i - 1) % count($komoditi)];
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'KOM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => $k[0] . ' Grade ' . ['A', 'B', 'C'][($i - 1) % 3],
                'jenis'     => $k[1],
                'satuan'    => $k[2],
                'harga_beli_default' => rand(5000, 80000),
                'harga_jual_default' => rand(7000, 120000),
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTagihanMaster(): void
    {
        $items = ['Iuran Wajib', 'Iuran Cadangan', 'Iuran Pendidikan', 'Iuran Sosial', 'Iuran Lebaran', 'Dana Kesehatan', 'Dana Pemakaman', 'Dana Qurban', 'Dana Pelatihan'];
        $this->topUp('tagihan_master', function ($i) use ($items) {
            return [
                'tenant_id' => $this->tenantId,
                'kode'      => 'TAG-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama'      => $items[($i - 1) % count($items)] . ' ' . date('Y') . ' #' . $i,
                'nominal'   => [25000, 50000, 100000, 150000, 200000][($i - 1) % 5],
                'siklus'    => ['bulanan', 'tahunan', 'sekali_bayar'][($i - 1) % 3],
                'auto_potong_simpanan' => $i % 3 === 0,
                'aktif'     => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAhliWaris(): void
    {
        $hubungan = ['Suami', 'Istri', 'Anak Kandung', 'Orang Tua', 'Saudara', 'Wali'];
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $this->topUp('ahli_waris', function ($i) use ($hubungan, $anggotaIds) {
            return [
                'tenant_id' => $this->tenantId,
                'anggota_id' => $anggotaIds[($i - 1) % count($anggotaIds)],
                'nama'      => 'Ahli Waris ' . $i,
                'hubungan'  => $hubungan[($i - 1) % count($hubungan)],
                'nik'       => str_pad((string) (3170010101 . $i), 16, '0', STR_PAD_LEFT),
                'tanggal_lahir' => now()->subYears(rand(20, 60))->toDateString(),
                'telp'      => '08' . rand(100000000, 999999999),
                'persentase' => 100,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedAnggotaStatusLog(): void
    {
        $statuses = [['calon', 'aktif'], ['aktif', 'cuti'], ['cuti', 'aktif'], ['aktif', 'tidak_aktif']];
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $this->topUp('anggota_status_log', function ($i) use ($statuses, $anggotaIds) {
            $s = $statuses[($i - 1) % count($statuses)];
            return [
                'tenant_id' => $this->tenantId,
                'anggota_id' => $anggotaIds[($i - 1) % count($anggotaIds)],
                'dari_status' => $s[0],
                'ke_status' => $s[1],
                'tanggal'   => now()->subDays(rand(1, 365))->toDateString(),
                'catatan'   => 'Log status #' . $i,
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedPinjamanJaminan(): void
    {
        $jenis = ['BPKB Motor', 'BPKB Mobil', 'Sertifikat Tanah', 'Emas', 'Deposito', 'Saham', 'Surat Kepemilikan Toko'];
        $pinjamanIds = DB::table('pinjaman')->limit(200)->pluck('id')->toArray();
        $this->topUp('pinjaman_jaminan', function ($i) use ($jenis, $pinjamanIds) {
            return [
                'tenant_id' => $this->tenantId,
                'pinjaman_id' => $pinjamanIds[($i - 1) % count($pinjamanIds)],
                'jenis'     => $jenis[($i - 1) % count($jenis)],
                'nama'      => $jenis[($i - 1) % count($jenis)] . ' #' . $i,
                'nomor_dokumen' => 'DOC-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'atas_nama' => 'Anggota ' . $i,
                'nilai_taksiran' => rand(5_000_000, 200_000_000),
                'nilai_pasar'    => rand(7_000_000, 250_000_000),
                'ltv'            => 70,
                'status'         => 'aktif',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedPinjamanApproval(): void
    {
        $jabatan = ['AO', 'Komite Kredit', 'Manager Cabang', 'Direktur'];
        $keputusan = ['disetujui', 'disetujui', 'disetujui', 'pending', 'ditolak'];
        $pinjamanIds = DB::table('pinjaman')->limit(200)->pluck('id')->toArray();
        $this->topUp('pinjaman_approval', function ($i) use ($jabatan, $keputusan, $pinjamanIds) {
            return [
                'tenant_id' => $this->tenantId,
                'pinjaman_id' => $pinjamanIds[($i - 1) % count($pinjamanIds)],
                'level'     => (($i - 1) % 4) + 1,
                'jabatan'   => $jabatan[($i - 1) % count($jabatan)],
                'user_id'   => 1,
                'keputusan' => $keputusan[($i - 1) % count($keputusan)],
                'catatan'   => 'Approval level ke-' . ((($i - 1) % 4) + 1),
                'decided_at' => now()->subDays(rand(1, 365)),
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedRat(): void
    {
        $this->topUp('rat', function ($i) {
            $hadir = rand(800, 4500);
            $tahun = 1926 + ($i - 1);
            return [
                'tenant_id' => $this->tenantId,
                'tahun_buku' => $tahun,
                'tanggal'   => "{$tahun}-03-" . str_pad((($i - 1) % 28) + 1, 2, '0', STR_PAD_LEFT),
                'lokasi'    => ['Aula Koperasi', 'Hotel Demo', 'Gedung Serbaguna', 'Pendopo Cabang'][($i - 1) % 4],
                'agenda'    => json_encode(['Laporan Pengurus', 'Pengesahan SHU', 'Pemilihan Pengurus']),
                'jumlah_anggota_terdaftar' => 5000,
                'jumlah_hadir' => $hadir,
                'quorum_persen' => 50,
                'quorum_tercapai' => $hadir >= 2500 ? 1 : 0,
                'status'    => $i <= 80 ? 'selesai' : 'rencana',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedShuPerhitungan(): void
    {
        $this->topUp('shu_perhitungan', function ($i) {
            $shu = rand(50_000_000, 500_000_000);
            return [
                'tenant_id' => $this->tenantId,
                'tahun'     => 1926 + ($i - 1),
                'shu_total' => $shu,
                'persen_jasa_modal'      => 20,
                'persen_jasa_anggota'    => 25,
                'persen_dana_cadangan'   => 25,
                'persen_dana_pendidikan' => 10,
                'persen_dana_sosial'     => 5,
                'persen_dana_pengurus'   => 10,
                'persen_dana_karyawan'   => 5,
                'jumlah_jasa_modal'      => (int) ($shu * 0.20),
                'jumlah_jasa_anggota'    => (int) ($shu * 0.25),
                'jumlah_dana_cadangan'   => (int) ($shu * 0.25),
                'jumlah_dana_pendidikan' => (int) ($shu * 0.10),
                'jumlah_dana_sosial'     => (int) ($shu * 0.05),
                'jumlah_dana_pengurus'   => (int) ($shu * 0.10),
                'jumlah_dana_karyawan'   => (int) ($shu * 0.05),
                'status'    => $i <= 80 ? 'distribusi' : 'draft',
                'meta'      => json_encode(['catatan' => 'Auto-seed periode #' . $i]),
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedShuDistribusi(): void
    {
        $shuIds = DB::table('shu_perhitungan')->limit(5)->pluck('id')->toArray();
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $this->topUp('shu_distribusi', function ($i) use ($shuIds, $anggotaIds) {
            $jm = rand(10_000, 500_000);
            $ja = rand(10_000, 800_000);
            return [
                'tenant_id'       => $this->tenantId,
                'shu_perhitungan_id' => $shuIds[($i - 1) % count($shuIds)] ?? 1,
                'anggota_id'      => $anggotaIds[($i - 1) % count($anggotaIds)],
                'total_simpanan'  => rand(500_000, 20_000_000),
                'total_transaksi' => rand(100_000, 10_000_000),
                'jasa_modal'      => $jm,
                'jasa_anggota'    => $ja,
                'total_shu'       => $jm + $ja,
                'metode_distribusi' => 'simpanan_sukarela',
                'status'          => 'belum_dibagikan',
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTagihan(): void
    {
        $masterIds = DB::table('tagihan_master')->pluck('id')->toArray();
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $this->topUp('tagihan', function ($i) use ($masterIds, $anggotaIds) {
            return [
                'tenant_id' => $this->tenantId,
                'master_id' => $masterIds[($i - 1) % count($masterIds)] ?? 1,
                'anggota_id' => $anggotaIds[($i - 1) % count($anggotaIds)],
                'nomor'     => 'TG-' . date('Ym') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'periode'   => now()->startOfMonth()->subMonths(($i - 1) % 12)->toDateString(),
                'jatuh_tempo' => now()->endOfMonth()->subMonths(($i - 1) % 12)->toDateString(),
                'nominal'   => 50000,
                'terbayar'  => $i % 3 === 0 ? 50000 : 0,
                'status'    => $i % 3 === 0 ? 'lunas' : 'belum_bayar',
                'tanggal_bayar' => $i % 3 === 0 ? now()->subDays(rand(1, 30))->toDateString() : null,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTokoPenjualan(): void
    {
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $kasIds = DB::table('kas')->where('aktif', true)->pluck('id')->toArray();
        $metode = ['cash', 'transfer', 'qris', 'debit', 'simpanan', 'utang'];
        $this->topUp('toko_penjualan', function ($i) use ($anggotaIds, $kasIds, $metode) {
            $sub = rand(50000, 1500000);
            $disk = (int) ($sub * (rand(0, 10) / 100));
            $total = $sub - $disk;
            return [
                'tenant_id' => $this->tenantId,
                'cabang_id' => 1,
                'anggota_id' => $i % 3 === 0 ? null : $anggotaIds[($i - 1) % count($anggotaIds)],
                'nomor'     => 'TJ-' . date('Ymd') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal'   => now()->subDays(rand(0, 90))->toDateString(),
                'subtotal'  => $sub,
                'diskon'    => $disk,
                'pajak'     => 0,
                'total'     => $total,
                'bayar'     => $total,
                'kembali'   => 0,
                'metode_bayar' => $metode[($i - 1) % count($metode)],
                'kas_id'    => $kasIds[($i - 1) % count($kasIds)] ?? null,
                'status'    => 'lunas',
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedTokoPembelian(): void
    {
        $supplierIds = DB::table('toko_supplier')->limit(20)->pluck('id')->toArray();
        $this->topUp('toko_pembelian', function ($i) use ($supplierIds) {
            $sub = rand(500000, 20000000);
            return [
                'tenant_id' => $this->tenantId,
                'cabang_id' => 1,
                'supplier_id' => $supplierIds[($i - 1) % count($supplierIds)] ?? 1,
                'nomor'     => 'TB-' . date('Ymd') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal'   => now()->subDays(rand(0, 90))->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(rand(15, 60))->toDateString(),
                'subtotal'  => $sub,
                'diskon'    => 0,
                'pajak'     => (int) ($sub * 0.11),
                'biaya_lain' => 0,
                'total'     => (int) ($sub * 1.11),
                'terbayar'  => $i % 2 === 0 ? (int) ($sub * 1.11) : 0,
                'status'    => $i % 2 === 0 ? 'lunas' : 'belum_lunas',
                'keterangan' => 'Pembelian rutin #' . $i,
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedUnitJasaOrder(): void
    {
        $layananIds = DB::table('unit_jasa_layanan')->limit(20)->pluck('id')->toArray();
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $kasIds = DB::table('kas')->where('aktif', true)->pluck('id')->toArray();
        $this->topUp('unit_jasa_order', function ($i) use ($layananIds, $anggotaIds, $kasIds) {
            $total = rand(15000, 500000);
            return [
                'tenant_id' => $this->tenantId,
                'layanan_id' => $layananIds[($i - 1) % count($layananIds)] ?? 1,
                'anggota_id' => $i % 3 === 0 ? null : $anggotaIds[($i - 1) % count($anggotaIds)],
                'nomor'     => 'OJ-' . date('Ymd') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal'   => now()->subDays(rand(0, 90))->toDateString(),
                'nama_pelanggan' => 'Pelanggan #' . $i,
                'total'     => $total,
                'komisi_anggota' => (int) ($total * 0.1),
                'status'    => ['booking', 'proses', 'selesai'][($i - 1) % 3],
                'bayar'     => $i % 2 === 0 ? $total : 0,
                'kas_id'    => $kasIds[($i - 1) % count($kasIds)] ?? null,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedUnitProdusenSetoran(): void
    {
        $komoditiIds = DB::table('unit_produsen_komoditi')->limit(20)->pluck('id')->toArray();
        $anggotaIds = DB::table('anggota')->limit(200)->pluck('id')->toArray();
        $kasIds = DB::table('kas')->where('aktif', true)->pluck('id')->toArray();
        $this->topUp('unit_produsen_setoran', function ($i) use ($komoditiIds, $anggotaIds, $kasIds) {
            $jumlah = round(rand(100, 10000) / 10, 3);
            $harga = rand(5000, 50000);
            $total = (int) round($jumlah * $harga);
            return [
                'tenant_id' => $this->tenantId,
                'anggota_id' => $anggotaIds[($i - 1) % count($anggotaIds)],
                'komoditi_id' => $komoditiIds[($i - 1) % count($komoditiIds)] ?? 1,
                'tanggal'   => now()->subDays(rand(0, 90))->toDateString(),
                'jumlah'    => $jumlah,
                'harga_satuan' => $harga,
                'total'     => $total,
                'kualitas'  => ['A', 'B', 'C'][($i - 1) % 3],
                'catatan'   => 'Setoran rutin #' . $i,
                'terbayar'  => $i % 2 === 0 ? $total : 0,
                'kas_id'    => $kasIds[($i - 1) % count($kasIds)] ?? null,
                'user_id'   => 1,
                'created_at' => now(), 'updated_at' => now(),
            ];
        });
    }

    private function seedPaymentProviders(): void
    {
        $providers = [
            ['Midtrans Sandbox', 'redirect',        'https://app.sandbox.midtrans.com/snap/v1', 'transactions'],
            ['Midtrans Production', 'redirect',     'https://app.midtrans.com/snap/v1',         'transactions'],
            ['Xendit Test', 'redirect',             'https://api.xendit.co',                    'invoices'],
            ['Xendit Live', 'redirect',             'https://api.xendit.co',                    'invoices'],
            ['DOKU Sandbox', 'redirect',            'https://api-sandbox.doku.com',             'orders/v1'],
            ['Tripay', 'redirect',                  'https://tripay.co.id/api',                 'transaction/create'],
            ['iPaymu', 'redirect',                  'https://my.ipaymu.com/api',                'payment'],
            ['Faspay', 'redirect',                  'https://debit.faspay.co.id',               'cvr/100/v3'],
            ['LinkAja QRIS', 'qris',                'https://api.linkaja.id',                   'qris/generate'],
            ['Dana QRIS', 'qris',                   'https://api.dana.id',                      'qris/generate'],
            ['NobuPay QRIS', 'qris',                'https://api.nobupay.id',                   'qris/generate'],
            ['BCA VA', 'virtual_account',           'https://api.klikbca.com',                  'va/inquiry'],
            ['Mandiri VA', 'virtual_account',       'https://api.bankmandiri.co.id',            'va'],
            ['BNI VA', 'virtual_account',           'https://api.bni.co.id',                    'va'],
            ['BRI VA', 'virtual_account',           'https://api.bri.co.id',                    'va'],
            ['Permata VA', 'virtual_account',       'https://api.permatabank.com',              'va'],
        ];
        $this->topUp('payment_providers', function ($i) use ($providers) {
            $p = $providers[($i - 1) % count($providers)];
            return [
                'nama'              => $p[0] . ' #' . $i,
                'api_format'        => $p[1],
                'base_url'          => $p[2],
                'session_endpoint'  => $p[3],
                'api_key_encrypted' => Crypt::encryptString('DEMO-KEY-' . $i),
                'merchant_id'       => $p[1] === 'qris' ? 'MID-' . $i : null,
                'extra_headers'     => null,
                'is_sandbox'        => $i % 2 === 0,
                'aktif'             => 1,
                'catatan'           => 'Demo seed entry #' . $i,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        });
    }

    private function seedActivityLog(): void
    {
        $events = ['created', 'updated', 'deleted'];
        $logs = ['anggota', 'pinjaman', 'simpanan'];
        $this->topUp('activity_log', function ($i) use ($events, $logs) {
            return [
                'log_name'      => $logs[($i - 1) % count($logs)],
                'description'   => $events[($i - 1) % count($events)],
                'subject_type'  => 'App\\Models\\' . ucfirst($logs[($i - 1) % count($logs)]),
                'subject_id'    => rand(1, 100),
                'event'         => $events[($i - 1) % count($events)],
                'causer_type'   => 'App\\Models\\User',
                'causer_id'     => 1,
                'properties'    => json_encode(['attributes' => ['status' => 'aktif']]),
                'batch_uuid'    => null,
                'created_at'    => now()->subDays(rand(0, 30)),
                'updated_at'    => now()->subDays(rand(0, 30)),
            ];
        });
    }
}
