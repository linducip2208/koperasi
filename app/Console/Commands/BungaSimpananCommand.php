<?php

namespace App\Console\Commands;

use App\Models\ProdukSimpanan;
use App\Models\Simpanan;
use App\Models\SimpananTransaksi;
use App\Domain\Akuntansi\JurnalService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BungaSimpananCommand extends Command
{
    protected $signature = 'koperasi:bunga-simpanan {--periode= : bulanan|harian (default: bulanan)}';
    protected $description = 'Hitung & posting bunga simpanan (harian atau bulanan)';

    public function handle(): int
    {
        $periode = $this->option('periode') ?? 'bulanan';

        if (!in_array($periode, ['harian', 'bulanan'])) {
            $this->error('Periode tidak valid. Gunakan: harian atau bulanan');
            return self::FAILURE;
        }

        $produkBunga = ProdukSimpanan::where('aktif', true)
            ->where('bunga_persen_tahun', '>', 0)
            ->get();

        if ($produkBunga->isEmpty()) {
            $this->info('Tidak ada produk simpanan dengan bunga > 0.');
            return self::SUCCESS;
        }

        $posted = 0;
        $total = 0;

        foreach ($produkBunga as $produk) {
            $simpananList = Simpanan::where('produk_id', $produk->id)
                ->where('status', 'aktif')
                ->where('saldo', '>', 0)
                ->get();

            foreach ($simpananList as $simpanan) {
                $bungaTahunan = (float) $produk->bunga_persen_tahun;

                if ($periode === 'harian') {
                    $bunga = (int) round($simpanan->saldo * ($bungaTahunan / 100) / 365);
                } else {
                    $bunga = (int) round($simpanan->saldo * ($bungaTahunan / 100) / 12);
                }

                if ($bunga <= 0) continue;

                $existingToday = SimpananTransaksi::where('simpanan_id', $simpanan->id)
                    ->where('jenis', 'bunga')
                    ->whereDate('tanggal', now()->toDateString())
                    ->exists();

                if ($existingToday) continue;

                try {
                    $saldoSebelum = $simpanan->saldo;

                    SimpananTransaksi::create([
                        'tenant_id'     => $simpanan->tenant_id,
                        'simpanan_id'   => $simpanan->id,
                        'kas_id'        => null,
                        'tanggal'       => now()->toDateString(),
                        'jenis'         => 'bunga',
                        'jumlah'        => $bunga,
                        'saldo_sebelum' => $saldoSebelum,
                        'saldo_sesudah' => $saldoSebelum + $bunga,
                        'keterangan'    => "Bunga {$periode} - {$produk->nama} ({$bungaTahunan}% p.a.)",
                    ]);

                    $simpanan->saldo += $bunga;
                    $simpanan->save();

                    $posted++;
                    $total += $bunga;
                } catch (\Throwable $e) {
                    Log::error("Bunga simpanan gagal: simpanan_id={$simpanan->id} - {$e->getMessage()}");
                }
            }
        }

        $this->info("Bunga {$periode} berhasil diposting: {$posted} simpanan, total Rp " . number_format($total, 0, ',', '.'));
        Log::info("koperasi:bunga-simpanan selesai — {$posted} simpanan, total Rp {$total}");

        return self::SUCCESS;
    }
}
