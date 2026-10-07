<?php

namespace App\Filament\Pages;

use App\Domain\Pinjaman\CreditScoringService;
use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\Anggota;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class CreditScoringPage extends Page implements HasForms
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'pinjaman';
    protected static ?string $navKey = 'Credit Scoring';
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'OPERASIONAL';
    protected static ?string $navigationLabel = 'Credit Scoring';
    protected static ?string $title = 'Credit Scoring';
    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.credit-scoring';

    public ?array $data = [];
    public ?array $hasil = null;

    public function mount(): void
    {
        $this->form->fill(['plafon' => 10_000_000]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('anggota_id')->label('Anggota')
                ->options(Anggota::where('status', 'aktif')->orderBy('nama')->limit(1000)->pluck('nama', 'id'))
                ->searchable()->required(),
            TextInput::make('plafon')->label('Plafon Diminta (Rp)')->numeric()->required()->minValue(100_000),
        ])->columns(2)->statePath('data');
    }

    public function hitung(): void
    {
        $d = $this->form->getState();
        $a = Anggota::findOrFail($d['anggota_id']);
        $this->hasil = CreditScoringService::skor($a, (int) $d['plafon']) + ['nama' => $a->nama, 'nomor' => $a->nomor_anggota];
    }
}
