<?php

namespace App\Filament\Pages;

use App\Domain\Calculation\CalculatorFactory;
use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LoanSimulatorPage extends Page implements HasForms
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'pinjaman';
    protected static ?string $navKey = 'Simulasi Pinjaman';
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'OPERASIONAL';
    protected static ?string $navigationLabel = 'Simulasi Pinjaman';
    protected static ?string $title = 'Simulasi Pinjaman';
    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.loan-simulator';

    public ?array $data = [];
    public array $hasil = [];

    public function mount(): void
    {
        $this->form->fill(['plafon' => 10_000_000, 'rate' => 12, 'tenor' => 12, 'metode' => 'flat', 'frekuensi' => 'bulanan']);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('plafon')->label('Plafon (Rp)')->numeric()->required()->minValue(100_000),
                TextInput::make('rate')->label('Bunga/Margin (%/thn)')->numeric()->required()->minValue(0)->maxValue(100),
                TextInput::make('tenor')->label('Tenor (bulan)')->numeric()->required()->minValue(1)->maxValue(360),
                Select::make('metode')->label('Metode')->options(CalculatorFactory::options())->required(),
                Select::make('frekuensi')->label('Frekuensi')->options(['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan'])->default('bulanan'),
            ]),
        ])->statePath('data');
    }

    public function hitung(): void
    {
        $d = $this->form->getState();
        $calc = CalculatorFactory::for($d['metode']);
        $this->hasil = $calc->generate((int) $d['plafon'], (float) $d['rate'], (int) $d['tenor'], ['frekuensi' => $d['frekuensi']]);
    }

    public function getViewData(): array
    {
        if (empty($this->hasil)) $this->hitung();
        return [];
    }
}
