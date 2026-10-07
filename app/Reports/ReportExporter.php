<?php

namespace App\Reports;

use App\Support\CooperativeContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Export generik semua report: CSV (streaming), Excel, PDF (template branded).
 */
class ReportExporter
{
    public static function make(ReportDefinition $def, ReportResult $result, string $format, array $params)
    {
        $filename = $def->key().'-'.now()->format('Ymd-His');

        return match ($format) {
            'csv' => self::csv($def, $result, $filename),
            'excel' => self::excel($def, $result, $filename),
            'pdf' => self::pdf($def, $result, $filename, $params),
            default => abort(422),
        };
    }

    public static function csv(ReportDefinition $def, ReportResult $result, string $filename): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => "attachment; filename=\"{$filename}.csv\""];

        return response()->stream(function () use ($result) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel baca UTF-8
            fputcsv($out, array_column($result->columns, 'label'), ';');
            $chunk = 0;
            foreach ($result->rows as $row) {
                fputcsv($out, array_map(fn ($c) => self::cell($row[$c['key']] ?? '', $c), $result->columns), ';');
                if (++$chunk % 1000 === 0) {
                    flush();
                }
            }
            fclose($out);
        }, 200, $headers);
    }

    public static function excel(ReportDefinition $def, ReportResult $result, string $filename)
    {
        $export = new class($def, $result) implements FromArray, ShouldAutoSize, WithHeadings, WithTitle
        {
            public function __construct(public ReportDefinition $def, public ReportResult $result) {}

            public function array(): array
            {
                return array_map(
                    fn ($row) => array_map(fn ($c) => ReportExporter::cell($row[$c['key']] ?? '', $c), $this->result->columns),
                    $this->result->rows
                );
            }

            public function headings(): array
            {
                return array_column($this->result->columns, 'label');
            }

            public function title(): string
            {
                return substr($this->def->key(), 0, 31);
            }
        };

        return Excel::download($export, "{$filename}.xlsx");
    }

    public static function pdf(ReportDefinition $def, ReportResult $result, string $filename, array $params)
    {
        $tenant = CooperativeContext::current();
        $pdf = Pdf::loadView('reports.pdf', [
            'def' => $def, 'result' => $result, 'params' => $params,
            'tenant' => $tenant, 'cabang' => null,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }

    public static function cell(mixed $value, array $col): mixed
    {
        return match ($col['format'] ?? 'text') {
            'money' => (int) $value,
            default => is_array($value) ? json_encode($value) : $value,
        };
    }
}
