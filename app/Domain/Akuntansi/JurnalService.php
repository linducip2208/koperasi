<?php

namespace App\Domain\Akuntansi;

use App\Domain\Numbering\NumberingService;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class JurnalService
{
    /**
     * Buat jurnal otomatis dari transaksi.
     *
     * @param string $keterangan
     * @param array<int, array{coa_id:int, debit?:int, kredit?:int, keterangan?:string}> $details
     * @param array{
     *   tanggal?: string,
     *   tipe?: string,
     *   referensi_type?: string,
     *   referensi_id?: int,
     *   cabang_id?: int,
     *   auto_post?: bool
     * } $opts
     */
    public static function create(string $keterangan, array $details, array $opts = []): Jurnal
    {
        if (count($details) < 2) {
            throw new InvalidArgumentException('Jurnal minimal 2 baris (debit & kredit).');
        }

        $totalDebit  = (int) array_sum(array_column($details, 'debit'));
        $totalKredit = (int) array_sum(array_column($details, 'kredit'));

        if ($totalDebit !== $totalKredit) {
            throw new InvalidArgumentException("Jurnal tidak balance: debit {$totalDebit} vs kredit {$totalKredit}.");
        }

        $tanggal = $opts['tanggal'] ?? now()->toDateString();
        self::assertPeriodOpen($tanggal);
        return DB::transaction(function () use ($keterangan, $details, $opts, $totalDebit, $totalKredit) {
            $jurnal = Jurnal::create([
                'tenant_id'      => CurrentTenant::id(),
                'cabang_id'      => $opts['cabang_id'] ?? auth()->user()?->cabang_id,
                'nomor'          => NumberingService::next('jurnal', 'JU-', '{prefix}{ymd}-{seq:5}'),
                'tanggal'        => $opts['tanggal'] ?? now()->toDateString(),
                'tipe'           => $opts['tipe'] ?? 'otomatis',
                'referensi_type' => $opts['referensi_type'] ?? null,
                'referensi_id'   => $opts['referensi_id'] ?? null,
                'keterangan'     => $keterangan,
                'total_debit'    => $totalDebit,
                'total_kredit'   => $totalKredit,
                'is_posted'      => $opts['auto_post'] ?? true,
                'posted_at'      => ($opts['auto_post'] ?? true) ? now() : null,
                'posted_by'      => ($opts['auto_post'] ?? true) ? auth()->id() : null,
                'created_by'     => auth()->id(),
            ]);

            foreach ($details as $d) {
                JurnalDetail::create([
                    'tenant_id'  => CurrentTenant::id(),
                    'jurnal_id'  => $jurnal->id,
                    'coa_id'     => $d['coa_id'],
                    'debit'      => $d['debit']  ?? 0,
                    'kredit'     => $d['kredit'] ?? 0,
                    'keterangan' => $d['keterangan'] ?? null,
                ]);
            }

            LaporanKeuanganService::flushLedgerCache();

            return $jurnal;
        });
    }

    public static function post(Jurnal $jurnal): void
    {
        if ($jurnal->is_posted) {
            return;
        }

        self::assertPeriodOpen($jurnal->tanggal->toDateString());

        $jurnal->update([
            'is_posted' => true,
            'posted_at' => now(),
            'posted_by' => auth()->id(),
        ]);

        LaporanKeuanganService::flushLedgerCache();
    }

    public static function unpost(Jurnal $jurnal): void
    {
        self::assertPeriodOpen($jurnal->tanggal->toDateString());

        $jurnal->update([
            'is_posted' => false,
            'posted_at' => null,
            'posted_by' => null,
        ]);

        LaporanKeuanganService::flushLedgerCache();
    }

    /**
     * Jurnal pembalik (reversal): membalik debit↔kredit per baris,
     * tertaut ke jurnal asal. Jurnal asal TIDAK diubah (immutable).
     */
    public static function reverse(Jurnal $asal, ?string $tanggal = null, ?string $alasan = null): Jurnal
    {
        if (! $asal->is_posted) {
            throw new InvalidArgumentException('Hanya jurnal posted yang bisa di-reverse.');
        }

        $details = $asal->details()->get()->map(fn ($d) => [
            'coa_id' => $d->coa_id,
            'debit' => (int) $d->kredit,
            'kredit' => (int) $d->debit,
            'keterangan' => 'Reversal: '.($d->keterangan ?? $asal->keterangan),
        ])->all();

        return self::create(
            'Reversal '.$asal->nomor.($alasan ? ' — '.$alasan : ''),
            $details,
            [
                'tanggal' => $tanggal ?? now()->toDateString(),
                'tipe' => 'balik',
                'referensi_type' => Jurnal::class,
                'referensi_id' => $asal->id,
                'cabang_id' => $asal->cabang_id,
            ]
        );
    }

    /** Periode akuntansi closed → tolak tulis/posting. */
    public static function isPeriodClosed(string $tanggal): bool
    {
        return \App\Models\PeriodeAkuntansi::where('status', 'closed')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_akhir', '>=', $tanggal)
            ->exists();
    }

    public static function assertPeriodOpen(string $tanggal): void
    {
        if (self::isPeriodClosed($tanggal)) {
            throw new InvalidArgumentException("Periode akuntansi {$tanggal} sudah di-close (locked).");
        }
    }
}
