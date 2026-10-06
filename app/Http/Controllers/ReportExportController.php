<?php

namespace App\Http\Controllers;

use App\Reports\ReportRunner;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function export(Request $request, string $key, string $format)
    {
        abort_unless(in_array($format, ['pdf', 'excel', 'csv'], true), 404);
        return ReportRunner::export($key, $format, $request->query());
    }
}
