<?php

namespace App\Console\Commands;

use App\Models\Anggota;
use App\Models\Kas;
use App\Models\ProdukPinjaman;
use App\Models\ProdukSimpanan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeedDemoData extends Command
{
    protected $signature = 'koperasi:seed-demo
        {--members=5000 : Jumlah anggota yang dibuat}
        {--tx-per-member=10 : Rata-rata transaksi simpanan per anggota}
        {--loans-ratio=1.6 : Rata-rata pinjaman per anggota (mis. 1.6 = 8000 pinjaman untuk 5000 anggota)}
        {--chunk=1000 : Batch size untuk insert (turunkan jika OOM)}
        {--fresh : Hapus semua data demo sebelum seed (truncate tabel terkait)}';

    protected $description = 'Seed ~100.000 record data demo realistic untuk testing & demo aplikasi';

    private array $namaDepan = [
        'Budi','Siti','Ahmad','Andi','Dewi','Rini','Susi','Joko','Bambang','Agus',
        'Hendra','Eko','Indra','Yanti','Sri','Nur','Lina','Iwan','Dedi','Tono',
        'Wati','Anita','Maman','Asep','Rudi','Dian','Yuni','Hadi','Tini','Heru',
        'Slamet','Marni','Imam','Kurniawan','Fajar','Lukman','Yudi','Tina','Reni','Hamzah',
        'Sutrisno','Mulyadi','Wahyu','Surya','Sintia','Lestari','Aminah','Maryati','Mardiana','Ridwan',
    ];

    private array $namaBelakang = [
        'Santoso','Wijaya','Pratama','Saputra','Kurniawan','Rahmawati','Hidayat','Gunawan','Setiawan','Putra',
        'Permana','Hartono','Susanto','Maulana','Nugroho','Hakim','Iskandar','Suryadi','Mahendra','Kusuma',
        'Sari','Lestari','Anggraeni','Wibowo','Pradana','Firdaus','Sutanto','Rahman','Hartono','Cahyono',
    ];

    private array $kotaList = [
        ['Jakarta','DKI Jakarta'],['Bandung','Jawa Barat'],['Surabaya','Jawa Timur'],
        ['Medan','Sumatera Utara'],['Semarang','Jawa Tengah'],['Yogyakarta','DI Yogyakarta'],
        ['Bogor','Jawa Barat'],['Malang','Jawa Timur'],['Depok','Jawa Barat'],['Bekasi','Jawa Barat'],
        ['Tangerang','Banten'],['Padang','Sumatera Barat'],['Denpasar','Bali'],['Makassar','Sulawesi Selatan'],
        ['Palembang','Sumatera Selatan'],['Solo','Jawa Tengah'],['Pekanbaru','Riau'],['Banjarmasin','Kalsel'],
    ];

    public function handle(): int
    {
        $tenantId = Tenant::query()->value('id') ?? 1;
        $members = (int) $this->option('members');
        $txPerMember = (int) $this->option('tx-per-member');
        $loansRatio = (float) $this->option('loans-ratio');
        $chunk = (int) $this->option('chunk');

        if ($this->option('fresh')) {
            $this->warn('🗑  Menghapus data demo lama (tabel transaksi)...');
            $driver = DB::connection()->getDriverName();
            if ($driver === 'mysql') DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            elseif ($driver === 'sqlite') DB::statement('PRAGMA foreign_keys = OFF;');

            DB::table('pinjaman_pembayaran')->delete();
            DB::table('pinjaman_jadwal')->delete();
            DB::table('pinjaman')->delete();
            DB::table('simpanan_transaksi')->delete();
            DB::table('simpanan')->delete();
            DB::table('kas_transaksi')->delete();
            DB::table('jurnal_detail')->delete();
            DB::table('jurnal')->delete();
            DB::table('anggota')->where('email', 'like', '%@demo.local')->delete();
            DB::table('users')->where('email', 'like', '%@demo.local')->delete();

            if ($driver === 'mysql') DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            elseif ($driver === 'sqlite') DB::statement('PRAGMA foreign_keys = ON;');
        }

        $produkSimpanan = ProdukSimpanan::query()->where('tenant_id', $tenantId)->take(4)->pluck('id')->toArray();
        $produkPinjaman = ProdukPinjaman::query()->where('tenant_id', $tenantId)->pluck('id')->toArray();
        $kasIds = Kas::query()->where('tenant_id', $tenantId)->pluck('id')->toArray();

        if (empty($produkSimpanan) || empty($produkPinjaman) || empty($kasIds)) {
            $this->error('❌ Master data belum lengkap. Jalankan dulu: php artisan db:seed');
            $this->line('   Pastikan ChartOfAccountsSeeder, KasDefaultSeeder, ProdukSimpananSeeder, ProdukPinjamanSeeder sudah dirun.');
            return self::FAILURE;
        }

        $this->info("🎲 Mulai seed demo data — target ~100.000 records");
        $this->line("   Members      : {$members}");
        $this->line("   Tx/member    : {$txPerMember}");
        $this->line("   Loans ratio  : {$loansRatio}x");
        $this->line("   Chunk size   : {$chunk}");
        $this->newLine();

        $start = microtime(true);
        $stats = [];

        // 1. Anggota + Users
        $stats['anggota'] = $this->seedAnggota($tenantId, $members, $chunk);

        // 2. Simpanan (3 per anggota: pokok, wajib, sukarela)
        $stats['simpanan'] = $this->seedSimpanan($tenantId, $produkSimpanan, $chunk);

        // 3. Simpanan Transaksi (~tx-per-member per anggota)
        $stats['simpanan_transaksi'] = $this->seedSimpananTransaksi($tenantId, $kasIds, $txPerMember, $chunk);

        // 4. Pinjaman
        $loansCount = (int) round($members * $loansRatio);
        $stats['pinjaman'] = $this->seedPinjaman($tenantId, $produkPinjaman, $loansCount, $chunk);

        // 5. Pinjaman Jadwal (12 bulan avg per pinjaman)
        $stats['pinjaman_jadwal'] = $this->seedPinjamanJadwal($tenantId, $chunk);

        // 6. Pinjaman Pembayaran (random terbayar)
        $stats['pinjaman_pembayaran'] = $this->seedPinjamanPembayaran($tenantId, $kasIds, $chunk);

        // 7. Jurnal
        $stats['jurnal'] = $this->seedJurnal($tenantId, $chunk);

        // 8. Kas Transaksi
        $stats['kas_transaksi'] = $this->seedKasTransaksi($tenantId, $kasIds, $chunk);

        $elapsed = round(microtime(true) - $start, 1);
        $total = array_sum($stats);

        $this->newLine();
        $this->info("✅ Selesai dalam {$elapsed} detik");
        $this->table(['Tabel', 'Jumlah Records'], collect($stats)->map(fn ($v, $k) => [$k, number_format($v, 0, ',', '.')])->values()->all());
        $this->line("   <fg=yellow;options=bold>Total: " . number_format($total, 0, ',', '.') . " records</>");
        $this->newLine();
        $this->line('💡 Login sebagai anggota demo:');
        $this->line('   URL:      /portal/login');
        $this->line('   Email:    anggota1@demo.local (s/d anggotaN@demo.local)');
        $this->line('   Password: anggota123');

        return self::SUCCESS;
    }

    private function seedAnggota(int $tenantId, int $count, int $chunk): int
    {
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Anggota');
        $bar->start();

        $startIndex = (int) (Anggota::query()->where('tenant_id', $tenantId)->max('id') ?? 0);
        $userStartIndex = (int) User::query()->where('email', 'like', '%@demo.local')->count();
        $now = now();
        // Pre-compute password hash sekali — bcrypt 12 rounds = ~250ms/call,
        // tanpa caching ini, 5000 anggota = 21 menit hanya untuk hashing.
        $hashedPassword = Hash::make('anggota123');

        for ($offset = 0; $offset < $count; $offset += $chunk) {
            $rowsAnggota = [];
            $rowsUser = [];
            $size = min($chunk, $count - $offset);

            for ($i = 0; $i < $size; $i++) {
                $idx = $startIndex + $offset + $i + 1;
                $userIdx = $userStartIndex + $offset + $i + 1;
                $depan = $this->pick($this->namaDepan);
                $belakang = $this->pick($this->namaBelakang);
                $nama = "{$depan} {$belakang}";
                [$kota, $provinsi] = $this->pick($this->kotaList);
                $tglLahir = Carbon::createFromTimestamp(rand(strtotime('1965-01-01'), strtotime('2002-12-31')))->toDateString();
                $tglMasuk = Carbon::createFromTimestamp(rand(strtotime('-3 years'), strtotime('-1 month')))->toDateString();

                $email = "anggota{$userIdx}@demo.local";
                $rowsUser[] = [
                    'tenant_id' => $tenantId,
                    'name'      => $nama,
                    'email'     => $email,
                    'nip'       => 'A' . str_pad((string) $userIdx, 6, '0', STR_PAD_LEFT),
                    'password'  => $hashedPassword,
                    'aktif'     => true,
                    'created_at'=> $now,
                    'updated_at'=> $now,
                ];

                $rowsAnggota[] = [
                    'tenant_id'         => $tenantId,
                    'cabang_id'         => null,
                    'nomor_anggota'     => 'AGT-' . str_pad((string) $idx, 6, '0', STR_PAD_LEFT),
                    'nik'               => $this->fakeNik(),
                    'nama'              => $nama,
                    'tempat_lahir'      => $kota,
                    'tanggal_lahir'     => $tglLahir,
                    'jenis_kelamin'     => rand(0, 1) ? 'L' : 'P',
                    'agama'             => $this->pick(['Islam','Kristen','Katolik','Hindu','Budha']),
                    'status_perkawinan' => $this->pick(['belum_kawin','kawin','janda','duda']),
                    'alamat'            => 'Jl. ' . $this->pick(['Merdeka','Sudirman','Pahlawan','Diponegoro','Veteran','Mawar','Kenanga','Anggrek']) . ' No. ' . rand(1, 250),
                    'rt'                => str_pad((string) rand(1, 20), 3, '0', STR_PAD_LEFT),
                    'rw'                => str_pad((string) rand(1, 15), 3, '0', STR_PAD_LEFT),
                    'kelurahan'         => 'Kel. ' . $this->pick(['Sukamaju','Bahagia','Sentosa','Indah','Makmur']),
                    'kecamatan'         => 'Kec. ' . $this->pick(['Tengah','Utara','Selatan','Timur','Barat']),
                    'kota'              => $kota,
                    'provinsi'          => $provinsi,
                    'kode_pos'          => (string) rand(10000, 99999),
                    'telp'              => '08' . rand(11, 99) . rand(1000000, 9999999),
                    'email'             => $email,
                    'pekerjaan'         => $this->pick(['Karyawan Swasta','PNS','Wiraswasta','Petani','Guru','Dokter','Pedagang','Driver','Tukang','Mahasiswa']),
                    'penghasilan_bulanan' => rand(2_000_000, 15_000_000),
                    'sumber_dana'       => 'Gaji',
                    'kategori'          => $this->pick(['anggota_biasa','anggota_luar_biasa']),
                    'status'            => 'aktif',
                    'tanggal_masuk'     => $tglMasuk,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }

            DB::table('users')->insert($rowsUser);
            DB::table('anggota')->insert($rowsAnggota);
            $bar->advance($size);
        }

        // Assign 'anggota' role to all demo users
        $demoUserIds = DB::table('users')->where('email', 'like', '%@demo.local')->pluck('id');
        $anggotaRoleId = DB::table('roles')->where('name', 'anggota')->value('id');
        if ($anggotaRoleId) {
            $roleRecords = $demoUserIds->map(fn ($uid) => [
                'role_id' => $anggotaRoleId,
                'model_type' => User::class,
                'model_id' => $uid,
            ])->all();
            DB::table('model_has_roles')->insertOrIgnore($roleRecords);
        }

        $bar->finish();
        $this->newLine();

        // Link user_id ke anggota berdasarkan email (DB-agnostic, works on MySQL & SQLite)
        DB::table('anggota')
            ->where('email', 'like', '%@demo.local')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $r) {
                    $userId = DB::table('users')->where('email', $r->email)->value('id');
                    if ($userId) {
                        DB::table('anggota')->where('id', $r->id)->update(['user_id' => $userId]);
                    }
                }
            });

        return $count;
    }

    private function seedSimpanan(int $tenantId, array $produkIds, int $chunk): int
    {
        $anggotaIds = DB::table('anggota')->where('tenant_id', $tenantId)->pluck('id')->toArray();
        $total = count($anggotaIds) * 3; // 3 simpanan per anggota
        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Simpanan');
        $bar->start();

        $now = now();
        $rows = [];
        $count = 0;
        $rekIdx = (int) (DB::table('simpanan')->where('tenant_id', $tenantId)->count() + 1);

        foreach ($anggotaIds as $anggotaId) {
            for ($i = 0; $i < 3; $i++) {
                $produkId = $produkIds[$i % count($produkIds)];
                $rows[] = [
                    'tenant_id'      => $tenantId,
                    'anggota_id'     => $anggotaId,
                    'produk_id'      => $produkId,
                    'nomor_rekening' => 'SMP-' . str_pad((string) $rekIdx++, 8, '0', STR_PAD_LEFT),
                    'saldo'          => rand(100_000, 5_000_000),
                    'saldo_blokir'   => 0,
                    'setoran_pokok'  => rand(50_000, 200_000),
                    'tanggal_buka'   => Carbon::createFromTimestamp(rand(strtotime('-2 years'), strtotime('-1 month')))->toDateString(),
                    'status'         => 'aktif',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
                $count++;

                if (count($rows) >= $chunk) {
                    DB::table('simpanan')->insert($rows);
                    $bar->advance(count($rows));
                    $rows = [];
                }
            }
        }
        if ($rows) {
            DB::table('simpanan')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedSimpananTransaksi(int $tenantId, array $kasIds, int $txPerMember, int $chunk): int
    {
        $simpananIds = DB::table('simpanan')->where('tenant_id', $tenantId)->pluck('id')->toArray();
        $totalTx = count($simpananIds) * intdiv($txPerMember, 3); // ~tx-per-member per anggota dibagi rata 3 simpanan
        $totalTx = max($totalTx, count($simpananIds));

        $bar = $this->output->createProgressBar($totalTx);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Simpanan Transaksi');
        $bar->start();

        $now = now();
        $rows = [];
        $count = 0;
        $nomorIdx = (int) (DB::table('simpanan_transaksi')->where('tenant_id', $tenantId)->count() + 1);

        foreach ($simpananIds as $simpananId) {
            $txCount = max(1, intdiv($txPerMember, 3));
            $saldo = rand(100_000, 1_000_000);
            for ($i = 0; $i < $txCount; $i++) {
                $jenis = $this->pick(['setor','setor','setor','tarik']); // 75% setor, 25% tarik
                $jumlah = rand(50_000, 500_000);
                $saldoSebelum = $saldo;
                $saldo = $jenis === 'setor' ? $saldo + $jumlah : max(0, $saldo - $jumlah);

                $rows[] = [
                    'tenant_id'     => $tenantId,
                    'simpanan_id'   => $simpananId,
                    'nomor'         => 'TRX-' . str_pad((string) $nomorIdx++, 8, '0', STR_PAD_LEFT),
                    'tanggal'       => Carbon::createFromTimestamp(rand(strtotime('-1 year'), time()))->toDateString(),
                    'jenis'         => $jenis,
                    'jumlah'        => $jumlah,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_sesudah' => $saldo,
                    'kas_id'        => $this->pick($kasIds),
                    'metode_bayar'  => $this->pick(['tunai','transfer']),
                    'keterangan'    => $jenis === 'setor' ? 'Setoran rutin anggota' : 'Penarikan tunai',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
                $count++;

                if (count($rows) >= $chunk) {
                    DB::table('simpanan_transaksi')->insert($rows);
                    $bar->advance(count($rows));
                    $rows = [];
                }
            }
        }
        if ($rows) {
            DB::table('simpanan_transaksi')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedPinjaman(int $tenantId, array $produkIds, int $count, int $chunk): int
    {
        $anggotaIds = DB::table('anggota')->where('tenant_id', $tenantId)->pluck('id')->toArray();
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Pinjaman');
        $bar->start();

        $now = now();
        $rows = [];
        $nomorIdx = (int) (DB::table('pinjaman')->where('tenant_id', $tenantId)->count() + 1);

        for ($i = 0; $i < $count; $i++) {
            $plafon = rand(1, 50) * 1_000_000;
            $tenor = $this->pick([6, 12, 18, 24, 36]);
            $bunga = rand(8, 18);
            $marginTotal = (int) ($plafon * ($bunga / 100) * ($tenor / 12));
            $totalBayar = $plafon + $marginTotal;
            $tglPengajuan = Carbon::createFromTimestamp(rand(strtotime('-2 years'), strtotime('-1 month')));

            $rows[] = [
                'tenant_id'           => $tenantId,
                'anggota_id'          => $this->pick($anggotaIds),
                'produk_id'           => $this->pick($produkIds),
                'nomor_akad'          => 'PJM-' . str_pad((string) $nomorIdx++, 8, '0', STR_PAD_LEFT),
                'tanggal_pengajuan'   => $tglPengajuan->toDateString(),
                'tanggal_akad'        => $tglPengajuan->copy()->addDays(rand(2, 7))->toDateString(),
                'tanggal_pencairan'   => $tglPengajuan->copy()->addDays(rand(7, 14))->toDateString(),
                'tanggal_jatuh_tempo' => $tglPengajuan->copy()->addMonths($tenor)->toDateString(),
                'plafon'              => $plafon,
                'pokok'               => $plafon,
                'margin_total'        => $marginTotal,
                'total_bayar'         => $totalBayar,
                'tenor'               => $tenor,
                'bunga_persen'        => $bunga,
                'biaya_admin'         => 50_000,
                'biaya_provisi'       => (int) ($plafon * 0.01),
                'total_biaya'         => 50_000 + (int) ($plafon * 0.01),
                'pencairan_bersih'    => $plafon - 50_000 - (int) ($plafon * 0.01),
                'saldo_pokok'         => $plafon,
                'saldo_margin'        => $marginTotal,
                'tujuan'              => $this->pick(['Modal usaha','Renovasi rumah','Biaya pendidikan anak','Pembelian kendaraan','Kebutuhan konsumtif','Modal kerja']),
                'status'              => $this->pick(['cair','cair','cair','lunas','aktif']),
                'kolektabilitas'      => $this->pick(['lancar','lancar','lancar','dpk','kurang_lancar','macet']),
                'created_at'          => $now,
                'updated_at'          => $now,
            ];

            if (count($rows) >= $chunk) {
                DB::table('pinjaman')->insert($rows);
                $bar->advance(count($rows));
                $rows = [];
            }
        }
        if ($rows) {
            DB::table('pinjaman')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedPinjamanJadwal(int $tenantId, int $chunk): int
    {
        $pinjamanList = DB::table('pinjaman')
            ->where('tenant_id', $tenantId)
            ->select('id', 'pokok', 'margin_total', 'tenor', 'tanggal_pencairan')
            ->get();

        $totalEst = $pinjamanList->sum('tenor');
        $bar = $this->output->createProgressBar($totalEst);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Jadwal Angsuran');
        $bar->start();

        $now = now();
        $rows = [];
        $count = 0;

        foreach ($pinjamanList as $p) {
            $tenor = $p->tenor;
            $pokokPerBulan = (int) ($p->pokok / $tenor);
            $marginPerBulan = (int) ($p->margin_total / $tenor);
            $cairDate = Carbon::parse($p->tanggal_pencairan);
            $saldoPokok = $p->pokok;

            for ($n = 1; $n <= $tenor; $n++) {
                $rows[] = [
                    'tenant_id'           => $tenantId,
                    'pinjaman_id'         => $p->id,
                    'angsuran_ke'         => $n,
                    'tanggal_jatuh_tempo' => $cairDate->copy()->addMonths($n)->toDateString(),
                    'pokok'               => $pokokPerBulan,
                    'margin'              => $marginPerBulan,
                    'total_angsuran'      => $pokokPerBulan + $marginPerBulan,
                    'saldo_pokok'         => $saldoPokok,
                    'terbayar_pokok'      => 0,
                    'terbayar_margin'     => 0,
                    'denda'               => 0,
                    'terbayar_denda'      => 0,
                    'status'              => 'belum_jatuh_tempo',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
                $saldoPokok -= $pokokPerBulan;
                $count++;

                if (count($rows) >= $chunk) {
                    DB::table('pinjaman_jadwal')->insert($rows);
                    $bar->advance(count($rows));
                    $rows = [];
                }
            }
        }
        if ($rows) {
            DB::table('pinjaman_jadwal')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedPinjamanPembayaran(int $tenantId, array $kasIds, int $chunk): int
    {
        $pinjamanIds = DB::table('pinjaman')->where('tenant_id', $tenantId)->pluck('id')->toArray();
        $count = (int) (count($pinjamanIds) * 0.6); // 60% pinjaman pernah ada pembayaran
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Pembayaran Cicilan');
        $bar->start();

        $now = now();
        $rows = [];
        $nomorIdx = (int) (DB::table('pinjaman_pembayaran')->where('tenant_id', $tenantId)->count() + 1);

        for ($i = 0; $i < $count; $i++) {
            $totalBayar = rand(500_000, 3_000_000);
            $alokasiPokok = (int) ($totalBayar * 0.7);
            $alokasiMargin = $totalBayar - $alokasiPokok;
            $rows[] = [
                'tenant_id'      => $tenantId,
                'pinjaman_id'    => $this->pick($pinjamanIds),
                'nomor'          => 'BYR-' . str_pad((string) $nomorIdx++, 8, '0', STR_PAD_LEFT),
                'tanggal'        => Carbon::createFromTimestamp(rand(strtotime('-1 year'), time()))->toDateString(),
                'jenis'          => 'angsuran',
                'total_bayar'    => $totalBayar,
                'alokasi_pokok'  => $alokasiPokok,
                'alokasi_margin' => $alokasiMargin,
                'alokasi_denda'  => 0,
                'alokasi_admin'  => 0,
                'alokasi_titipan'=> 0,
                'kas_id'         => $this->pick($kasIds),
                'metode_bayar'   => $this->pick(['tunai','transfer']),
                'keterangan'     => 'Pembayaran cicilan',
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            if (count($rows) >= $chunk) {
                DB::table('pinjaman_pembayaran')->insert($rows);
                $bar->advance(count($rows));
                $rows = [];
            }
        }
        if ($rows) {
            DB::table('pinjaman_pembayaran')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedJurnal(int $tenantId, int $chunk): int
    {
        $count = 800;
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Jurnal');
        $bar->start();

        $now = now();
        $rows = [];
        $nomorIdx = (int) (DB::table('jurnal')->where('tenant_id', $tenantId)->count() + 1);

        for ($i = 0; $i < $count; $i++) {
            $total = rand(500_000, 50_000_000);
            $rows[] = [
                'tenant_id'    => $tenantId,
                'nomor'        => 'JU-' . str_pad((string) $nomorIdx++, 8, '0', STR_PAD_LEFT),
                'tanggal'      => Carbon::createFromTimestamp(rand(strtotime('-1 year'), time()))->toDateString(),
                'tipe'         => $this->pick(['umum','simpanan','pinjaman','kas','penyesuaian']),
                'keterangan'   => $this->pick(['Setoran simpanan anggota','Pencairan pinjaman','Pembayaran cicilan','Jurnal penyesuaian','Transfer kas']),
                'total_debit'  => $total,
                'total_kredit' => $total,
                'is_posted'    => true,
                'posted_at'    => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            if (count($rows) >= $chunk) {
                DB::table('jurnal')->insert($rows);
                $bar->advance(count($rows));
                $rows = [];
            }
        }
        if ($rows) {
            DB::table('jurnal')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function seedKasTransaksi(int $tenantId, array $kasIds, int $chunk): int
    {
        $count = 200;
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% Kas Transaksi');
        $bar->start();

        $now = now();
        $rows = [];
        $nomorIdx = (int) (DB::table('kas_transaksi')->where('tenant_id', $tenantId)->count() + 1);

        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                'tenant_id'   => $tenantId,
                'kas_id'      => $this->pick($kasIds),
                'nomor'       => 'KAS-' . str_pad((string) $nomorIdx++, 8, '0', STR_PAD_LEFT),
                'tanggal'     => Carbon::createFromTimestamp(rand(strtotime('-6 months'), time()))->toDateString(),
                'jenis'       => $this->pick(['masuk','keluar']),
                'jumlah'      => rand(100_000, 10_000_000),
                'keterangan'  => $this->pick(['Setoran ke bank','Pembayaran biaya operasional','Top-up kas kecil','Pembayaran gaji','Refund anggota']),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];

            if (count($rows) >= $chunk) {
                DB::table('kas_transaksi')->insert($rows);
                $bar->advance(count($rows));
                $rows = [];
            }
        }
        if ($rows) {
            DB::table('kas_transaksi')->insert($rows);
            $bar->advance(count($rows));
        }
        $bar->finish();
        $this->newLine();
        return $count;
    }

    private function pick(array $arr): mixed
    {
        return $arr[array_rand($arr)];
    }

    private function fakeNik(): string
    {
        // 16 digit NIK realistic
        return (string) random_int(1_000_000_000_000_000, 9_999_999_999_999_999);
    }
}
