<?php

namespace App\Reports;

use App\Models\ReportArchive;

/**
 * Arsip official report: snapshot JSON immutable + checksum + file opsional.
 * Arsip tidak berubah walau data live berubah (snapshot test mengunci ini).
 */
class ReportArchiver
{
    public static function archive(string $key, array $params, ?string $title = null, ?int $userId = null): ReportArchive
    {
        $def = ReportRegistry::find($key);
        if (! $def) {
            throw new \InvalidArgumentException("Report '{$key}' tidak dikenal.");
        }

        $result = ReportRunner::run($key, $params);
        $snapshot = json_encode($result->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $archive = ReportArchive::create([
            'report_key' => $key,
            'title' => $title ?: $def->name().' — '.now()->format('d M Y H:i'),
            'period' => ($params['dari'] ?? $params['tanggal'] ?? $params['tahun'] ?? null)
                ? (($params['dari'] ?? '').(isset($params['sampai']) ? ' s/d '.$params['sampai'] : ''))
                : null,
            'params' => $params,
            'snapshot' => $snapshot,
            'report_version' => config('product.version'),
            'checksum' => hash('sha256', $snapshot),
            'status' => 'final',
            'generated_by' => $userId ?? auth()->id(),
        ]);

        activity('reports')->causedBy(auth()->user())->withProperties(['archive_id' => $archive->id, 'report' => $key])->log('report_archived');

        return $archive;
    }

    public static function read(ReportArchive $archive): ReportResult
    {
        if (! $archive->verify()) {
            throw new \RuntimeException('Checksum arsip tidak valid — snapshot rusak.');
        }
        $data = json_decode($archive->snapshot, true);

        return new ReportResult($data['columns'], $data['rows'], $data['totals'], $data['metrics'], $data['charts'], $data['meta']);
    }
}
