<?php

namespace App\Reports;

/**
 * Eksekutor Custom Report — HANYA dari whitelist data source.
 * Tidak ada SQL mentah dari user. Operator/fungsi dibatasi allowlist.
 */
class CustomReportRunner
{
    public const SOURCES = [
        'members' => ['model' => \App\Models\Anggota::class, 'label' => 'Anggota',
            'fields' => ['id', 'nomor_anggota', 'nama', 'nik', 'telp', 'email', 'status', 'kategori', 'tanggal_masuk']],
        'savings' => ['model' => \App\Models\Simpanan::class, 'label' => 'Rekening Simpanan',
            'fields' => ['id', 'nomor_rekening', 'anggota_id', 'produk_id', 'saldo', 'status', 'tanggal_buka']],
        'savings_transactions' => ['model' => \App\Models\SimpananTransaksi::class, 'label' => 'Transaksi Simpanan',
            'fields' => ['id', 'nomor', 'simpanan_id', 'tanggal', 'jenis', 'jumlah', 'metode_bayar']],
        'loans' => ['model' => \App\Models\Pinjaman::class, 'label' => 'Pinjaman',
            'fields' => ['id', 'nomor_akad', 'anggota_id', 'produk_id', 'plafon', 'tenor', 'status', 'kolektabilitas', 'saldo_pokok', 'tanggal_pengajuan']],
        'installments' => ['model' => \App\Models\PinjamanJadwal::class, 'label' => 'Jadwal Angsuran',
            'fields' => ['id', 'pinjaman_id', 'angsuran_ke', 'tanggal_jatuh_tempo', 'total_angsuran', 'status']],
        'accounts' => ['model' => \App\Models\Coa::class, 'label' => 'Akun (COA)',
            'fields' => ['id', 'kode', 'nama', 'tipe', 'saldo_normal', 'is_aktif']],
        'journals' => ['model' => \App\Models\Jurnal::class, 'label' => 'Jurnal',
            'fields' => ['id', 'nomor', 'tanggal', 'tipe', 'keterangan', 'total_debit', 'is_posted']],
        'shu' => ['model' => \App\Models\ShuDistribusi::class, 'label' => 'Distribusi SHU',
            'fields' => ['id', 'anggota_id', 'jasa_modal', 'jasa_anggota', 'total_shu', 'status']],
        'voting' => ['model' => \App\Models\RatVotingSuara::class, 'label' => 'Suara Voting',
            'fields' => ['id', 'voting_id', 'anggota_id', 'opsi_index', 'created_at']],
    ];

    public const OPERATORS = ['=', '!=', '>', '>=', '<', '<=', 'contains', 'starts_with', 'between', 'in', 'is_null', 'is_not_null'];
    public const AGGREGATES = ['count', 'sum', 'avg', 'min', 'max'];

    public static function run(\App\Models\CustomReport $report, array $overrideParams = []): ReportResult
    {
        $src = self::SOURCES[$report->data_source] ?? null;
        if (! $src) throw new \InvalidArgumentException('Data source tidak dikenal.');

        $q = ($src['model'])::query();

        foreach ((array) ($report->filters ?? []) as $f) {
            $field = $f['field'] ?? null;
            $op = $f['operator'] ?? '=';
            if (! in_array($field, $src['fields'], true) || ! in_array($op, self::OPERATORS, true)) continue;
            $value = $overrideParams[$field] ?? ($f['value'] ?? null);
            $q = self::applyFilter($q, $field, $op, $value);
        }

        $columns = [];
        $selects = [];
        $groupField = null;
        foreach ((array) $report->columns as $c) {
            $field = $c['field'] ?? null;
            $agg = $c['aggregate'] ?? null;
            if (! in_array($field, $src['fields'], true)) continue;
            if ($agg && ! in_array($agg, self::AGGREGATES, true)) continue;
            if ($agg === 'count' && $field === '*') {
                $selects[] = \Illuminate\Support\Facades\DB::raw('COUNT(*) as _count');
                $columns[] = ['key' => '_count', 'label' => 'Count', 'align' => 'right'];
            } elseif ($agg) {
                $alias = "_{$agg}_{$field}";
                $selects[] = \Illuminate\Support\Facades\DB::raw(strtoupper($agg)."({$field}) as {$alias}");
                $columns[] = ['key' => $alias, 'label' => ucfirst($agg).' '.ucfirst(str_replace('_', ' ', $field)), 'align' => 'right'];
            } else {
                $groupField ??= $field;
                $selects[] = $field;
                $columns[] = ['key' => $field, 'label' => ucfirst(str_replace('_', ' ', $field))];
            }
        }
        if (empty($selects)) throw new \InvalidArgumentException('Pilih minimal satu kolom.');

        if ($groupField && count($selects) > 1) $q->groupBy($groupField);

        $sort = $report->sort ?? [];
        if (! empty($sort['field']) && in_array($sort['field'], array_column($columns, 'key'), true)) {
            $q->orderBy($sort['field'], ($sort['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc');
        }

        $rows = $q->limit(min(2000, max(1, (int) ($report->limit ?: 500))))->get($selects)->map->toArray()->all();

        return new ReportResult($columns, $rows, [], [
            ['label' => 'Sumber', 'value' => $src['label'], 'format' => 'text'],
            ['label' => 'Baris', 'value' => count($rows).' (maks 2000)', 'format' => 'text'],
        ]);
    }

    protected static function applyFilter($q, string $field, string $op, mixed $value)
    {
        return match ($op) {
            '=' => $q->where($field, $value),
            '!=' => $q->where($field, '!=', $value),
            '>', '>=', '<', '<=' => $q->where($field, $op, $value),
            'contains' => $q->where($field, 'like', '%'.addcslashes((string) $value, '%_\\').'%'),
            'starts_with' => $q->where($field, 'like', addcslashes((string) $value, '%_\\').'%'),
            'between' => is_array($value) && count($value) === 2 ? $q->whereBetween($field, $value) : $q,
            'in' => is_array($value) ? $q->whereIn($field, array_slice($value, 0, 100)) : $q,
            'is_null' => $q->whereNull($field),
            'is_not_null' => $q->whereNotNull($field),
            default => $q,
        };
    }
}
