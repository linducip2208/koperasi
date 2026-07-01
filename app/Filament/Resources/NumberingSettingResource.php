<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NumberingSettingResource\Pages;
use App\Models\NumberingSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NumberingSettingResource extends Resource
{
    protected static ?string $model = NumberingSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Format Penomoran';
    protected static ?string $modelLabel = 'Format Nomor';
    protected static ?string $pluralModelLabel = 'Format Penomoran';
    protected static ?int $navigationSort = 92;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->required()->maxLength(30)->placeholder('mis. SETORAN, PINJAMAN, INVOICE')->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('prefix')->placeholder('TJ, INV, PNJ — kosong = tanpa prefix'),
                Forms\Components\TextInput::make('format')->required()->default('{prefix}{ymd}{seq:5}')
                    ->helperText('Variabel: {prefix} {y} {ym} {ymd} {seq:N}'),
                Forms\Components\TextInput::make('panjang_seq')->numeric()->default(5)->minValue(1)->maxValue(10),
                Forms\Components\Select::make('reset_period')->required()->options([
                    0 => 'Tidak Reset', 1 => 'Reset Harian', 2 => 'Reset Bulanan', 3 => 'Reset Tahunan',
                ])->default(2),
                Forms\Components\TextInput::make('next_number')->numeric()->default(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->copyable()->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('prefix')->placeholder('—'),
                Tables\Columns\TextColumn::make('format')->fontFamily('mono')->size('sm'),
                Tables\Columns\TextColumn::make('next_number')->label('Next #')->badge()->color('info'),
                Tables\Columns\TextColumn::make('reset_period')->label('Reset')->formatStateUsing(fn ($state) => [
                    0 => 'Tidak', 1 => 'Harian', 2 => 'Bulanan', 3 => 'Tahunan',
                ][$state] ?? $state)->badge(),
                Tables\Columns\TextColumn::make('last_reset_at')->date('d M Y')->placeholder('—'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNumberingSettings::route('/'),
            'create' => Pages\CreateNumberingSetting::route('/create'),
            'edit'   => Pages\EditNumberingSetting::route('/{record}/edit'),
        ];
    }
}
