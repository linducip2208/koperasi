<?php

namespace App\Reports;

abstract class ReportDefinition
{
    abstract public function key(): string;
    abstract public function name(): string;
    public function description(): string { return ''; }
    abstract public function category(): string;

    /** Permission spatie yang dibutuhkan untuk menjalankan. */
    public function permission(): string { return 'reports.view'; }

    /** Spesifikasi filter — lihat ReportFilter. */
    public function filters(): array { return []; }

    abstract public function run(array $params): ReportResult;

    /** Format export yang didukung: pdf | excel | csv. */
    public function exports(): array { return ['pdf', 'excel', 'csv']; }

    public function supportsChart(): bool { return false; }
    public function supportsSchedule(): bool { return true; }

    protected function cabangId(array $p): ?int
    {
        return ! empty($p['cabang_id']) ? (int) $p['cabang_id'] : null;
    }

    protected function moneyCol(string $key, string $label): array
    {
        return ['key' => $key, 'label' => $label, 'align' => 'right', 'format' => 'money'];
    }
}
