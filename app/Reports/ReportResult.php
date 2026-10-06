<?php

namespace App\Reports;

/**
 * Hasil eksekusi report terstandar.
 *
 * columns: [['key' => 'nama', 'label' => 'Nama', 'align' => 'left|right|center', 'format' => 'text|money|date|percent|badge']]
 * rows: list array sesuai key kolom. Nilai khusus:
 *   ['__link' => url] per baris untuk drill-down.
 * metrics: [['label', 'value', 'format']] — kartu ringkasan.
 * charts: [['type' => 'line|bar|doughnut', 'title', 'labels' => [], 'datasets' => [['label', 'data' => [], 'color' => '#hex']]]]
 */
class ReportResult
{
    public function __construct(
        public array $columns = [],
        public array $rows = [],
        public array $totals = [],
        public array $metrics = [],
        public array $charts = [],
        public array $meta = [],
    ) {}

    public function toArray(): array
    {
        return [
            'columns' => $this->columns,
            'rows' => $this->rows,
            'totals' => $this->totals,
            'metrics' => $this->metrics,
            'charts' => $this->charts,
            'meta' => $this->meta,
        ];
    }
}
