<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Reports\ReportRegistry;
use Filament\Pages\Page;

class ReportCenterPage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = '📊 Laporan';
    protected static ?string $navigationLabel = 'Report Center';
    protected static ?string $title = 'Report Center';
    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.report-center';

    public string $q = '';
    public string $cat = '';

    public function getViewData(): array
    {
        $defs = ReportRegistry::all();
        $q = strtolower(trim($this->q));
        $filtered = array_filter($defs, function ($d) use ($q) {
            if ($this->cat && $d->category() !== $this->cat) return false;
            if (! $q) return true;
            return str_contains(strtolower($d->name().' '.$d->description().' '.$d->key()), $q);
        });
        $favorites = \App\Models\SavedReport::where('user_id', auth()->id())->where('is_favorite', true)->pluck('report_key')->all();
        $recent = \App\Models\SavedReport::where('user_id', auth()->id())->orderByDesc('last_run_at')->limit(5)->get();

        return [
            'categories' => ReportRegistry::categories(),
            'defs' => $filtered,
            'favorites' => $favorites,
            'recent' => $recent,
            'counts' => array_count_values(array_map(fn ($d) => $d->category(), $defs)),
        ];
    }

    public function toggleFavorite(string $key): void
    {
        $rec = \App\Models\SavedReport::firstOrCreate(
            ['user_id' => auth()->id(), 'report_key' => $key],
            ['name' => ReportRegistry::find($key)?->name() ?? $key, 'params' => []]
        );
        $rec->update(['is_favorite' => ! $rec->is_favorite, 'last_run_at' => now()]);
    }
}
