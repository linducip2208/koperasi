<?php

namespace App\Reports;

use App\Models\User;

/**
 * Eksekusi report: otorisasi → run → audit log.
 */
class ReportRunner
{
    public static function run(string $key, array $params, ?User $user = null): ReportResult
    {
        $def = ReportRegistry::find($key);
        if (! $def) {
            throw new \InvalidArgumentException("Report '{$key}' tidak dikenal.");
        }

        $user ??= auth()->user();
        if ($user && ! $user->can($def->permission())) {
            abort(403, 'Tidak berhak menjalankan report ini.');
        }

        $clean = self::cleanParams($def, $params);
        $result = $def->run($clean);

        activity('reports')
            ->causedBy($user)
            ->withProperties(['report' => $key, 'params' => $clean, 'rows' => count($result->rows)])
            ->log('report_generated');

        return $result;
    }

    public static function export(string $key, string $format, array $params, ?User $user = null)
    {
        $def = ReportRegistry::find($key);
        if (! $def) {
            throw new \InvalidArgumentException("Report '{$key}' tidak dikenal.");
        }
        if (! in_array($format, $def->exports(), true)) {
            abort(422, 'Format export tidak didukung report ini.');
        }

        $user ??= auth()->user();
        $need = match ($format) {
            'pdf' => 'reports.pdf', 'excel' => 'reports.excel', 'csv' => 'reports.csv', default => 'reports.export',
        };
        if ($user && ! ($user->can($need) || $user->can('reports.export'))) {
            abort(403, 'Tidak berhak mengekspor report.');
        }

        $result = self::run($key, $params, $user);

        activity('reports')
            ->causedBy($user)
            ->withProperties(['report' => $key, 'format' => $format])
            ->log('report_exported');

        return ReportExporter::make($def, $result, $format, $params);
    }

    /** Ambil hanya param yang didefinisikan + default; buang sisanya (anti mass-input). */
    public static function cleanParams(ReportDefinition $def, array $params): array
    {
        $clean = [];
        foreach ($def->filters() as $f) {
            $name = $f['name'];
            $clean[$name] = $params[$name] ?? $f['default'] ?? null;
        }

        return $clean;
    }
}
