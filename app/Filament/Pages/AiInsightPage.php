<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Services\Ai\AiManager;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AiInsightPage extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = '📊 Laporan';
    protected static ?string $navigationLabel = 'AI Insights';
    protected static ?string $title = 'AI Insights';
    protected static ?int $navigationSort = 58;

    protected static string $view = 'filament.pages.ai-insight';

    public ?array $data = [];
    public ?string $answer = null;

    public function mount(): void
    {
        $this->form->fill([
            'topik' => 'tunggakan',
            'dari' => now()->startOfMonth()->toDateString(),
            'sampai' => now()->toDateString(),
            'pertanyaan' => 'Jelaskan mengapa tunggakan meningkat dan apa rekomendasinya.',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('topik')->label('Topik')->options([
                'tunggakan' => 'Tunggakan / Koleksi',
            ])->required(),
            DatePicker::make('dari')->label('Dari')->required(),
            DatePicker::make('sampai')->label('Sampai')->required(),
            Textarea::make('pertanyaan')->label('Pertanyaan')->rows(2)->required()->columnSpanFull(),
        ])->columns(3)->statePath('data');
    }

    public function ask(): void
    {
        $d = $this->form->getState();
        $ctx = AiManager::overdueContext($d['dari'], $d['sampai']);
        $this->answer = AiManager::explain($d['pertanyaan'], $ctx);
    }
}
