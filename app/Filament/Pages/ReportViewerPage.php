<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Reports\ReportRegistry;
use App\Reports\ReportRunner;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ReportViewerPage extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Lihat Report';
    protected static ?string $title = 'Report Viewer';
    protected static ?int $navigationSort = 51;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.report-viewer';

    public ?string $reportKey = null;
    public ?array $data = [];
    public array $result = [];
    public string $error = '';

    public function mount(): void
    {
        $this->reportKey = request()->query('report');
        $def = $this->definition();
        $defaults = [];
        foreach ($def?->filters() ?? [] as $f) {
            $defaults[$f['name']] = request()->query($f['name'], $f['default'] ?? null);
        }
        $this->form->fill($defaults);
        $this->runReport();
    }

    public function definition(): ?\App\Reports\ReportDefinition
    {
        return $this->reportKey ? ReportRegistry::find($this->reportKey) : null;
    }

    public function form(Form $form): Form
    {
        $schema = [];
        foreach ($this->definition()?->filters() ?? [] as $f) {
            $schema[] = match ($f['type']) {
                'date' => DatePicker::make($f['name'])->label($f['label'])->required(! empty($f['required'])),
                'select' => Select::make($f['name'])->label($f['label'])
                    ->options($this->selectOptions($f))->searchable()
                    ->placeholder($f['placeholder'] ?? 'Semua'),
                'account' => Select::make($f['name'])->label($f['label'])
                    ->options(\App\Models\Coa::where('is_postable', true)->where('is_aktif', true)->orderBy('kode')->pluck('nama', 'id'))
                    ->searchable()->placeholder($f['placeholder'] ?? 'Pilih…'),
                default => TextInput::make($f['name'])->label($f['label']),
            };
        }
        return $form->schema($schema)->columns(4)->statePath('data');
    }

    protected function selectOptions(array $f): array
    {
        if (isset($f['options'])) return $f['options'];
        if (isset($f['model'])) {
            $model = $f['model'];
            $label = $f['option_label'] ?? 'nama';
            return $model::orderBy($label)->limit(500)->pluck($label, 'id')->all();
        }
        return [];
    }

    public function runReport(): void
    {
        $this->error = '';
        try {
            $this->result = ReportRunner::run($this->reportKey, $this->data ?? [])->toArray();
            \App\Models\SavedReport::updateOrCreate(
                ['user_id' => auth()->id(), 'report_key' => $this->reportKey],
                ['name' => $this->definition()->name(), 'params' => $this->data ?? [], 'last_run_at' => now()]
            );
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->result = [];
        }
    }

    public function exportUrl(string $format): string
    {
        return route('reports.export', ['key' => $this->reportKey, 'format' => $format] + ($this->data ?? []));
    }

    public function getViewData(): array
    {
        return ['def' => $this->definition()];
    }
}
