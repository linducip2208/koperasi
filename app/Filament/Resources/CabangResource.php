<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CabangResource\Pages;
use App\Models\Cabang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CabangResource extends Resource
{
    protected static ?string $model = Cabang::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = '⚙️ Pengaturan';
    protected static ?string $navigationLabel = 'Cabang';
    protected static ?string $modelLabel = 'Cabang';
    protected static ?string $pluralModelLabel = 'Cabang';
    protected static ?int $navigationSort = 92;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->label('Kode Cabang')->required()->maxLength(10)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')->label('Nama Cabang')->required()->maxLength(255),
                Forms\Components\TextInput::make('telp')->label('Telepon')->maxLength(255),
                Forms\Components\Select::make('tipe')->label('Tipe')
                    ->options(['kantor_pusat' => 'Kantor Pusat', 'cabang' => 'Cabang', 'kantor_kas' => 'Kantor Kas'])
                    ->default('cabang')->required(),
                Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull(),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('tipe')->badge()->color(fn (string $state) => match ($state) {
                    'kantor_pusat' => 'success', 'cabang' => 'info', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('telp')->toggleable(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCabangs::route('/'),
            'create' => Pages\CreateCabang::route('/create'),
            'edit'   => Pages\EditCabang::route('/{record}/edit'),
        ];
    }
}
