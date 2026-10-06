<?php

namespace App\Reports;

/**
 * Spesifikasi filter report. Tipe: date | select | text | number | member | account | product.
 * Untuk select: ['options' => [value => label]] atau ['model' => FQCN, 'label' => 'nama', 'scope' => [...]].
 */
class ReportFilter
{
    public static function dateRange(string $from = 'dari', string $to = 'sampai'): array
    {
        return [
            ['name' => $from, 'type' => 'date', 'label' => 'Dari', 'default' => now()->startOfYear()->toDateString(), 'required' => true],
            ['name' => $to, 'type' => 'date', 'label' => 'Sampai', 'default' => now()->toDateString(), 'required' => true],
        ];
    }

    public static function asOf(string $name = 'sampai'): array
    {
        return [
            ['name' => $name, 'type' => 'date', 'label' => 'Per Tanggal', 'default' => now()->toDateString(), 'required' => true],
        ];
    }

    public static function cabang(): array
    {
        return [['name' => 'cabang_id', 'type' => 'select', 'label' => 'Cabang',
            'model' => \App\Models\Cabang::class, 'option_label' => 'nama', 'placeholder' => 'Semua Cabang']];
    }

    public static function account(string $name = 'coa_id', string $label = 'Akun'): array
    {
        return [['name' => $name, 'type' => 'account', 'label' => $label, 'placeholder' => 'Pilih akun…']];
    }
}
