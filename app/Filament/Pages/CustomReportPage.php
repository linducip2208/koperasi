<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\CustomReport;
use App\Reports\CustomReportRunner;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class CustomReportPage extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = '📊 Laporan';
    protected static ?string $navigationLabel = 'Custom Builder';
    protected static ?string $title = 'Custom Report Builder';
    protected static ?int $navigationSort = 56;

    protected static string $view = 'filament.pages.custom-report';

    public ?array $data = [];
    public ?int $runId = null;
    public array $result = [];

    public function mount(): void
    {
        $this->runId = request()->query('run') ? (int) request()->query('run') : null;
        $this->form->fill(['limit' => 500, 'sort_dir' => 'asc']);
        if ($this->runId) $this->runCustom();
    }

    public function form(Form $form): Form
    {
        $sources = collect(CustomReportRunner::SOURCES)->mapWithKeys(fn ($s, $k) => [$k => $s['label']])->all();
        return $form->schema([
            Select::make('data_source')->label('Sumber Data (whitelist)')->options($sources)->required()->live(),
            TextInput::make('name')->label('Nama Report')->required()->maxLength(100),
            Textarea::make('description')->label('Deskripsi')->rows(1)->columnSpanFull(),
            Repeater::make('columns')->label('Kolom')->schema([
                Select::make('field')->label('Field')->options($this->fieldsFor($this->data['data_source'] ?? null))->required(),
                Select::make('aggregate')->label('Agregat')->options(['' => '-', 'count' => 'Count', 'sum' => 'Sum', 'avg' => 'Avg', 'min' => 'Min', 'max' => 'Max']),
            ])->minItems(1)->columnSpanFull()->columns(2),
            Repeater::make('filters')->label('Filter')->schema([
                Select::make('field')->label('Field')->options($this->fieldsFor($this->data['data_source'] ?? null))->required(),
                Select::make('operator')->label('Operator')->options(array_combine(CustomReportRunner::OPERATORS, CustomReportRunner::OPERATORS))->required(),
                TextInput::make('value')->label('Nilai'),
            ])->columnSpanFull()->columns(3)->collapsible(),
            TextInput::make('limit')->label('Limit')->numeric()->default(500)->minValue(1)->maxValue(2000),
        ])->columns(2)->statePath('data');
    }

    protected function fieldsFor(?string $source): array
    {
        if (! $source || ! isset(CustomReportRunner::SOURCES[$source])) return [];
        $fields = CustomReportRunner::SOURCES[$source]['fields'];
        return array_combine($fields, array_map(fn ($f) => ucfirst(str_replace('_', ' ', $f)), $fields));
    }

    public function saveCustom(): void
    {
        $d = $this->form->getState();
        $rec = CustomReport::create([
            'name' => $d['name'], 'description' => $d['description'] ?? null,
            'data_source' => $d['data_source'], 'columns' => $d['columns'] ?? [],
            'filters' => $d['filters'] ?? [], 'sort' => null,
            'limit' => min(2000, max(1, (int) ($d['limit'] ?? 500))),
            'created_by' => auth()->id(),
        ]);
        activity('reports')->causedBy(auth()->user())->withProperties(['id' => $rec->id])->log('custom_report_created');
        $this->runId = $rec->id;
        $this->runCustom();
    }

    public function runCustom(): void
    {
        $rec = CustomReport::findOrFail($this->runId);
        $runner = new CustomReportRunner();
        $this->result = $runner::run($rec)->toArray();
        $this->result['report_name'] = $rec->name;
    }

    public function getViewData(): array
    {
        return ['saved' => CustomReport::orderByDesc('id')->limit(20)->get()];
    }
}
