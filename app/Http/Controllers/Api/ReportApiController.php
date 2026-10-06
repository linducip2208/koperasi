<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Reports\ReportRegistry;
use App\Reports\ReportRunner;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $list = [];
        foreach (ReportRegistry::all() as $def) {
            if (! $user->can($def->permission())) continue;
            $list[] = [
                'key' => $def->key(), 'name' => $def->name(),
                'description' => $def->description(), 'category' => $def->category(),
                'filters' => $def->filters(), 'exports' => $def->exports(),
            ];
        }
        return response()->json(['data' => $list]);
    }

    public function show(Request $request, string $key)
    {
        $def = ReportRegistry::find($key);
        if (! $def) return response()->json(['message' => 'Report tidak dikenal.'], 404);
        if (! $request->user()->can($def->permission())) return response()->json(['message' => 'Forbidden.'], 403);
        return response()->json(['data' => [
            'key' => $def->key(), 'name' => $def->name(), 'description' => $def->description(),
            'category' => $def->category(), 'filters' => $def->filters(), 'exports' => $def->exports(),
        ]]);
    }

    public function run(Request $request, string $key)
    {
        try {
            $result = ReportRunner::run($key, (array) $request->input('params', []), $request->user());
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
        return response()->json(['data' => $result->toArray()]);
    }

    public function export(Request $request, string $key, string $format)
    {
        abort_unless(in_array($format, ['csv', 'excel'], true), 404); // PDF via web (butuh stream browser)
        try {
            return ReportRunner::export($key, $format, (array) $request->input('params', []), $request->user());
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
