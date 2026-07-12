<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggaranResource\Pages;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\Anggaran;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnggaranResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'jurnal';

    protected static ?string $model = Anggaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Anggaran (Budget)';
    protected static ?string $modelLabel = 'Anggaran';
    protected static ?string $pluralModelLabel = 'Anggaran';
    protected static ?int $navigationSort = 33;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('tahun')->options(array_combine(range(now()->year - 2, now()->year + 2), range(now()->year - 2, now()->year + 2)))
                    ->default(now()->year)->required(),
                Forms\Components\Select::make('coa_id')->label('Akun (COA)')
                    ->options(Coa::whereIn('tipe', ['pendapatan', 'beban'])->pluck('nama', 'id'))
                    ->searchable()->required(),
            ]),
            Forms\Components\Section::make('Anggaran Bulanan (Rp)')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('jan')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('feb')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('mar')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('apr')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('mei')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('jun')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('jul')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('agu')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('sep')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('okt')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('nov')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('des')->prefix('Rp')->numeric()->default(0),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tahun', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tahun')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('coa.nama')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('coa.tipe')->badge()->color(fn ($state) => $state === 'pendapatan' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('total')->label('Total Setahun')->money('IDR')
                    ->getStateUsing(fn ($record) => $record->jan + $record->feb + $record->mar + $record->apr + $record->mei + $record->jun + $record->jul + $record->agu + $record->sep + $record->okt + $record->nov + $record->des),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun')->options(array_combine(range(2020, now()->year + 1), range(2020, now()->year + 1))),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnggarans::route('/'),
            'create' => Pages\CreateAnggaran::route('/create'),
            'edit'   => Pages\EditAnggaran::route('/{record}/edit'),
        ];
    }
}
