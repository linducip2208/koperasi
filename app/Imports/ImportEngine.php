<?php

namespace App\Imports;

use App\Models\ImportBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Engine import: parse CSV/XLSX → validasi → preview stats → import atomic.
 * Finansial: satu DB::transaction per batch + jurnal penyeimbang + audit log.
 */
class ImportEngine
{
    public static function parse(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'csv' || $ext === 'txt') {
            return self::parseCsv($path);
        }
        if (in_array($ext, ['xlsx', 'xls'], true)) {
            return self::parseExcel($path);
        }
        throw new \InvalidArgumentException('Format file harus CSV atau XLSX.');
    }

    public static function parseCsv(string $path): array
    {
        $raw = file_get_contents($path);
        if ($raw === false) throw new \RuntimeException('File tidak terbaca.');
        // BOM strip + delimiter detect dari baris pertama.
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
        $first = strtok($raw, "\r\n");
        $counts = [';' => substr_count($first, ';'), ',' => substr_count($first, ','), "\t" => substr_count($first, "\t")];
        arsort($counts);
        $delim = array_key_first($counts) ?: ',';

        $rows = [];
        $fh = fopen('php://memory', 'r+');
        fwrite($fh, $raw);
        rewind($fh);
        while (($r = fgetcsv($fh, 0, $delim)) !== false) {
            if (count(array_filter($r, fn ($v) => trim((string) $v) !== '')) === 0) continue;
            $rows[] = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $r);
        }
        fclose($fh);
        return $rows;
    }

    public static function parseExcel(string $path): array
    {
        $rows = [];
        foreach (\Maatwebsite\Excel\Facades\Excel::toArray([], $path) as $sheet) {
            foreach ($sheet as $r) {
                $r = array_map(fn ($v) => is_string($v) ? trim($v) : ($v instanceof \DateTimeInterface ? $v->format('Y-m-d') : $v), array_values($r));
                if (count(array_filter($r, fn ($v) => $v !== null && trim((string) $v) !== '')) === 0) continue;
                $rows[] = $r;
            }
            break; // sheet pertama saja
        }
        return $rows;
    }

    /**
     * Validasi semua baris → ['valid' => [...], 'invalid' => [...], 'duplicate' => n].
     * Header dipetakan dari label kolom definisi (case-insensitive).
     */
    public static function validate(string $type, array $rows): array
    {
        $cols = ImportDefinition::columns($type);
        $byLabel = [];
        foreach ($cols as $c) $byLabel[strtolower($c['label'])] = $c;

        if (empty($rows)) return ['valid' => [], 'invalid' => [], 'duplicate' => 0, 'header' => []];

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $rows[0]);
        $map = []; // index kolom file → definisi
        foreach ($header as $i => $h) {
            if (isset($byLabel[$h])) $map[$i] = $byLabel[$h];
        }

        $valid = $invalid = [];
        $seen = [];
        $duplicates = 0;

        foreach (array_slice($rows, 1) as $n => $row) {
            $line = $n + 2;
            // Semua field definisi selalu ada (null bila kolom tidak dipetakan).
            $data = [];
            foreach ($cols as $c) $data[$c['field']] = null;
            foreach ($map as $i => $c) $data[$c['field']] = $row[$i] ?? null;

            $errors = self::validateRow($type, $data, $cols);
            if ($errors) {
                $invalid[] = ['line' => $line, 'data' => $data, 'errors' => $errors];
                continue;
            }

            // Duplicate: dalam file + di database (kolom unique).
            $dupKey = self::dupKey($type, $data);
            if ($dupKey && (isset($seen[$dupKey]) || self::existsInDb($type, $data))) {
                $duplicates++;
                $invalid[] = ['line' => $line, 'data' => $data, 'errors' => ['Duplikat: '.$dupKey]];
                continue;
            }
            if ($dupKey) $seen[$dupKey] = true;
            $valid[] = ['line' => $line, 'data' => $data];
        }

        return ['valid' => $valid, 'invalid' => $invalid, 'duplicate' => $duplicates, 'header' => $header];
    }

    protected static function dupKey(string $type, array $data): ?string
    {
        return match ($type) {
            'members' => ! empty($data['nik']) ? 'nik:'.$data['nik'] : (! empty($data['nomor_anggota']) ? 'no:'.$data['nomor_anggota'] : null),
            'coa' => ! empty($data['kode']) ? 'kode:'.$data['kode'] : null,
            'produk_simpanan', 'produk_pinjaman' => ! empty($data['kode']) ? 'kode:'.$data['kode'] : null,
            'simpanan_awal' => ! empty($data['nomor_rekening']) ? 'rek:'.$data['nomor_rekening'] : null,
            'pinjaman_awal' => ! empty($data['nomor_akad']) ? 'akad:'.$data['nomor_akad'] : null,
            default => null,
        };
    }

    protected static function existsInDb(string $type, array $data): bool
    {
        return match ($type) {
            'members' => (! empty($data['nik']) && \App\Models\Anggota::where('nik', $data['nik'])->exists())
                || (! empty($data['nomor_anggota']) && \App\Models\Anggota::where('nomor_anggota', $data['nomor_anggota'])->exists()),
            'coa' => ! empty($data['kode']) && \App\Models\Coa::where('kode', $data['kode'])->exists(),
            'produk_simpanan' => ! empty($data['kode']) && \App\Models\ProdukSimpanan::where('kode', $data['kode'])->exists(),
            'produk_pinjaman' => ! empty($data['kode']) && \App\Models\ProdukPinjaman::where('kode', $data['kode'])->exists(),
            'simpanan_awal' => ! empty($data['nomor_rekening']) && \App\Models\Simpanan::where('nomor_rekening', $data['nomor_rekening'])->exists(),
            'pinjaman_awal' => ! empty($data['nomor_akad']) && \App\Models\Pinjaman::where('nomor_akad', $data['nomor_akad'])->exists(),
            default => false,
        };
    }

    protected static function validateRow(string $type, array $data, array $cols): array
    {
        $errors = [];
        foreach ($cols as $c) {
            $v = $data[$c['field']] ?? null;
            $v = is_string($v) ? trim($v) : $v;
            $empty = $v === null || $v === '';
            if (! empty($c['required']) && $empty) { $errors[] = $c['label'].' wajib diisi'; continue; }
            if ($empty) continue;
            if (! empty($c['max']) && mb_strlen((string) $v) > $c['max']) $errors[] = $c['label'].' maksimal '.$c['max'].' karakter';
            if (! empty($c['email']) && ! filter_var($v, FILTER_VALIDATE_EMAIL)) $errors[] = $c['label'].' bukan email valid';
            if (! empty($c['enum']) && ! in_array($v, $c['enum'], true)) $errors[] = $c['label'].' harus salah satu: '.implode('/', $c['enum']);
            if (! empty($c['numeric']) && IndonesianNumber::toInt((string) $v) === null) $errors[] = $c['label'].' bukan angka valid';
            if (! empty($c['date']) && IndonesianNumber::toDate((string) $v) === null) $errors[] = $c['label'].' bukan tanggal valid';
            if (! empty($c['bool']) && ! in_array(strtolower((string) $v), ['ya', 'tidak', 'y', 't', '1', '0', 'true', 'false'], true)) $errors[] = $c['label'].' harus ya/tidak';
            if (! empty($c['unique'])) {
                [$table, $col] = explode('|', $c['unique']);
                $model = ['anggota' => \App\Models\Anggota::class, 'coa' => \App\Models\Coa::class, 'produk_simpanan' => \App\Models\ProdukSimpanan::class, 'produk_pinjaman' => \App\Models\ProdukPinjaman::class, 'simpanan' => \App\Models\Simpanan::class, 'pinjaman' => \App\Models\Pinjaman::class][$table] ?? null;
                if ($model && $model::where($col, $v)->exists()) $errors[] = $c['label'].' sudah ada di database';
            }
            if (! empty($c['exists'])) {
                [$table, $col] = explode('|', $c['exists']);
                $model = ['anggota' => \App\Models\Anggota::class, 'produk_simpanan' => \App\Models\ProdukSimpanan::class, 'produk_pinjaman' => \App\Models\ProdukPinjaman::class][$table] ?? null;
                if ($model && ! $model::where($col, $v)->exists()) $errors[] = $c['label'].' tidak ditemukan di database';
            }
        }
        return $errors;
    }

    /** Tulis error CSV untuk diunduh operator. */
    public static function writeErrorCsv(array $invalid): string
    {
        $name = 'import-errors-'.now()->format('Ymd-His').'.csv';
        $path = 'imports/'.$name;
        $fh = fopen(Storage::disk('local')->path($path), 'w');
        fwrite($fh, "\xEF\xBB\xBF");
        fputcsv($fh, ['Baris', 'Error', 'Data (JSON)'], ';');
        foreach ($invalid as $r) {
            fputcsv($fh, [$r['line'], implode(' | ', $r['errors']), json_encode($r['data'], JSON_UNESCAPED_UNICODE)], ';');
        }
        fclose($fh);
        return $path;
    }

    /** Import batch valid — atomic per 500 baris; finansial + jurnal penyeimbang. */
    public static function import(ImportBatch $batch, array $validRows): array
    {
        $imported = 0;
        $tenantId = \App\Support\CooperativeContext::id();

        foreach (array_chunk($validRows, 500) as $chunk) {
            DB::transaction(function () use ($batch, $chunk, $tenantId, &$imported) {
                foreach ($chunk as $r) {
                    self::importRow($batch->tipe, $r['data'], $tenantId);
                    $imported++;
                }
            });
        }

        $batch->update(['imported_rows' => $imported, 'status' => 'completed']);

        activity('reports')->causedBy(auth()->user())->withProperties([
            'batch_id' => $batch->id, 'tipe' => $batch->tipe, 'imported' => $imported,
        ])->log('import_completed');

        return ['imported' => $imported];
    }

    protected static function importRow(string $type, array $d, int $tenantId): void
    {
        $bool = fn ($v) => in_array(strtolower((string) ($v ?? '')), ['ya', 'y', '1', 'true'], true);
        switch ($type) {
            case 'members':
                \App\Models\Anggota::create([
                    'tenant_id' => $tenantId,
                    'nomor_anggota' => $d['nomor_anggota'] ?: \App\Domain\Numbering\NumberingService::next('anggota', 'AGT-', '{prefix}{ymd}{seq:5}'),
                    'nama' => $d['nama'], 'nik' => $d['nik'] ?: null,
                    'telp' => $d['telp'] ?: null, 'email' => $d['email'] ?: null,
                    'alamat' => $d['alamat'] ?: null,
                    'tanggal_masuk' => IndonesianNumber::toDate($d['tanggal_masuk'] ?? '') ?? now()->toDateString(),
                    'status' => $d['status'] ?: 'aktif',
                ]);
                break;
            case 'coa':
                \App\Models\Coa::create([
                    'tenant_id' => $tenantId, 'kode' => $d['kode'], 'nama' => $d['nama'],
                    'tipe' => $d['tipe'], 'saldo_normal' => $d['saldo_normal'],
                    'is_postable' => $bool($d['is_postable'] ?? 'ya'), 'is_aktif' => true,
                ]);
                break;
            case 'produk_simpanan':
                \App\Models\ProdukSimpanan::create([
                    'tenant_id' => $tenantId, 'kode' => $d['kode'], 'nama' => $d['nama'],
                    'jenis' => $d['jenis'], 'boleh_tarik' => $bool($d['boleh_tarik'] ?? 'ya'), 'aktif' => true,
                ]);
                break;
            case 'produk_pinjaman':
                \App\Models\ProdukPinjaman::create([
                    'tenant_id' => $tenantId, 'kode' => $d['kode'], 'nama' => $d['nama'],
                    'akad_type' => $d['akad_type'], 'metode_perhitungan' => $d['akad_type'],
                    'bunga_persen' => IndonesianNumber::toInt($d['bunga_persen'] ?? '0') ?? 0, 'aktif' => true,
                ]);
                break;
            case 'simpanan_awal':
                self::importSimpananAwal($d, $tenantId);
                break;
            case 'pinjaman_awal':
                self::importPinjamanAwal($d, $tenantId);
                break;
        }
    }

    protected static function importSimpananAwal(array $d, int $tenantId): void
    {
        $anggota = \App\Models\Anggota::where('nomor_anggota', $d['nomor_anggota'])->firstOrFail();
        $produk = \App\Models\ProdukSimpanan::where('kode', $d['kode_produk'])->firstOrFail();
        $saldo = IndonesianNumber::toInt($d['saldo']) ?? 0;

        $simpanan = \App\Models\Simpanan::create([
            'tenant_id' => $tenantId, 'anggota_id' => $anggota->id, 'produk_id' => $produk->id,
            'nomor_rekening' => $d['nomor_rekening'] ?: \App\Domain\Numbering\NumberingService::next('simpanan_rek', $produk->kode.'-', '{prefix}{ym}{seq:6}'),
            'saldo' => $saldo,
            'tanggal_buka' => IndonesianNumber::toDate($d['tanggal_buka'] ?? '') ?? now()->toDateString(),
            'status' => 'aktif',
        ]);

        if ($saldo > 0) {
            $modal = self::modalAwalCoa($tenantId);
            $coaSimp = $produk->coa_simpanan_id ? \App\Models\Coa::find($produk->coa_simpanan_id) : \App\Models\Coa::where('kode', '2.2.1.01')->first();
            $trx = \App\Models\SimpananTransaksi::create([
                'tenant_id' => $tenantId, 'simpanan_id' => $simpanan->id,
                'nomor' => \App\Domain\Numbering\NumberingService::next('simpanan_trx', 'STR-', '{prefix}{ymd}-{seq:5}'),
                'tanggal' => $simpanan->tanggal_buka, 'jenis' => 'setor', 'jumlah' => $saldo,
                'saldo_sebelum' => 0, 'saldo_sesudah' => $saldo,
                'metode_bayar' => 'internal', 'keterangan' => 'Saldo awal (import)', 'user_id' => auth()->id(),
            ]);
            $jurnal = \App\Domain\Akuntansi\JurnalService::create("Saldo awal {$simpanan->nomor_rekening}", [
                ['coa_id' => $modal->id, 'debit' => 0, 'kredit' => $saldo, 'keterangan' => 'Modal saldo awal'],
                ['coa_id' => $coaSimp->id, 'debit' => $saldo, 'kredit' => 0, 'keterangan' => 'Simpanan awal'],
            ], ['tanggal' => $simpanan->tanggal_buka->toDateString(), 'tipe' => 'penutup',
                'referensi_type' => \App\Models\SimpananTransaksi::class, 'referensi_id' => $trx->id]);
            $trx->update(['jurnal_id' => $jurnal->id]);
        }
    }

    protected static function importPinjamanAwal(array $d, int $tenantId): void
    {
        $anggota = \App\Models\Anggota::where('nomor_anggota', $d['nomor_anggota'])->firstOrFail();
        $produk = \App\Models\ProdukPinjaman::where('kode', $d['kode_produk'])->firstOrFail();
        $pokok = IndonesianNumber::toInt($d['pokok']) ?? 0;

        $pinjaman = \App\Models\Pinjaman::create([
            'tenant_id' => $tenantId, 'anggota_id' => $anggota->id, 'produk_id' => $produk->id,
            'nomor_akad' => $d['nomor_akad'] ?: \App\Domain\Numbering\NumberingService::next('pinjaman', 'PJM-', '{prefix}{ymd}-{seq:5}'),
            'tanggal_pengajuan' => IndonesianNumber::toDate($d['tanggal_pengajuan'] ?? '') ?? now()->toDateString(),
            'plafon' => $pokok, 'pokok' => $pokok, 'saldo_pokok' => $pokok,
            'tenor' => IndonesianNumber::toInt($d['tenor'] ?? '12') ?? 12,
            'status' => 'aktif', 'kolektabilitas' => 'lancar',
        ]);

        if ($pokok > 0) {
            $modal = self::modalAwalCoa($tenantId);
            $coaPokok = $produk->coa_pokok_id ? \App\Models\Coa::find($produk->coa_pokok_id) : \App\Models\Coa::where('tipe', 'aset')->where('is_postable', true)->first();
            \App\Domain\Akuntansi\JurnalService::create("Outstanding awal {$pinjaman->nomor_akad}", [
                ['coa_id' => $coaPokok->id, 'debit' => $pokok, 'kredit' => 0, 'keterangan' => 'Piutang awal'],
                ['coa_id' => $modal->id, 'debit' => 0, 'kredit' => $pokok, 'keterangan' => 'Modal saldo awal'],
            ], ['tanggal' => $pinjaman->tanggal_pengajuan, 'tipe' => 'penutup',
                'referensi_type' => \App\Models\Pinjaman::class, 'referensi_id' => $pinjaman->id]);
        }
    }

    protected static function modalAwalCoa(int $tenantId): \App\Models\Coa
    {
        return \App\Models\Coa::firstOrCreate(
            ['tenant_id' => $tenantId, 'kode' => '3.9.9.01'],
            ['nama' => 'Modal Saldo Awal', 'tipe' => 'ekuitas', 'saldo_normal' => 'kredit', 'is_postable' => true, 'is_aktif' => true]
        );
    }
}
