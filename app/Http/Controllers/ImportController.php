<?php

namespace App\Http\Controllers;

use App\Imports\ImportDefinition;
use App\Imports\ImportEngine;
use App\Jobs\ProcessImport;
use App\Models\ImportBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    /** Template CSV per tipe (header + contoh baris). */
    public function template(string $tipe)
    {
        abort_unless(array_key_exists($tipe, ImportDefinition::types()), 404);
        $headers = ImportDefinition::templateHeaders($tipe);
        $example = array_map(fn ($h) => "contoh-{$h}", $headers);

        return response()->streamDownload(function () use ($headers, $example) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers, ';');
            fputcsv($out, $example, ';');
            fclose($out);
        }, "template-{$tipe}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Upload → parse → validasi → simpan batch status preview. */
    public function upload(Request $request, string $tipe)
    {
        abort_unless(array_key_exists($tipe, ImportDefinition::types()), 404);
        $request->validate(['file' => ['required', 'file', 'max:10240', 'mimes:csv,txt,xlsx,xls']]);

        $stored = $request->file('file')->store('imports', 'local');
        $rows = ImportEngine::parse(Storage::disk('local')->path($stored));
        $result = ImportEngine::validate($tipe, $rows);

        $errorFile = null;
        if (! empty($result['invalid'])) {
            $errorFile = ImportEngine::writeErrorCsv($result['invalid']);
        }

        $batch = ImportBatch::create([
            'tipe' => $tipe,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $stored,
            'total_rows' => count($result['valid']) + count($result['invalid']),
            'valid_rows' => count($result['valid']),
            'invalid_rows' => count($result['invalid']) - $result['duplicate'],
            'duplicate_rows' => $result['duplicate'],
            'error_file' => $errorFile,
            'status' => 'preview',
            'user_id' => auth()->id(),
        ]);

        activity('reports')->causedBy(auth()->user())->withProperties([
            'batch_id' => $batch->id, 'tipe' => $tipe,
            'valid' => $batch->valid_rows, 'invalid' => $batch->invalid_rows, 'duplicate' => $batch->duplicate_rows,
        ])->log('import_started');

        return redirect()->route('filament.admin.pages.import-center', ['batch' => $batch->id]);
    }

    /** Konfirmasi import: sync (<1000) atau queue. */
    public function confirm(ImportBatch $batch)
    {
        abort_if($batch->status !== 'preview', 422, 'Batch sudah diproses.');
        $this->authorizeImport($batch);

        $rows = ImportEngine::parse(Storage::disk('local')->path($batch->file_path));
        $result = ImportEngine::validate($batch->tipe, $rows);

        if (count($result['valid']) > 1000) {
            $batch->update(['status' => 'importing']);
            ProcessImport::dispatch($batch->id);
            return back()->with('success', 'Import besar dijalankan via queue. Pantau di history.');
        }

        ImportEngine::import($batch, $result['valid']);
        return back()->with('success', "Import selesai: {$batch->imported_rows} baris masuk.");
    }

    public function errorFile(ImportBatch $batch)
    {
        abort_unless($batch->error_file && Storage::disk('local')->exists($batch->error_file), 404);
        return Storage::disk('local')->download($batch->error_file);
    }

    protected function authorizeImport(ImportBatch $batch): void
    {
        $financial = in_array($batch->tipe, ['simpanan_awal', 'pinjaman_awal'], true);
        $need = $financial ? 'reports.import_financial' : 'reports.import';
        if (! auth()->user()->can($need)) abort(403, 'Tidak berhak import tipe ini.');
    }
}
