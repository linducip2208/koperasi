<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsuransiProdukResource\Pages;
use App\Models\AsuransiProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AsuransiProdukResource extends Resource
{
    protected static ?string $model = AsuransiProduk::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Asuransi';
    protected static ?string $navigationLabel = 'Produk Asuransi';
    protected static ?string $modelLabel = 'Produk Asuransi';
    protected static ?string $pluralModelLabel = 'Produk Asuransi';
    protected static ?int $navigationSort = 71;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('nama')->required()->maxLength(255)->placeholder('mis. Asuransi Jiwa Anggota'),
                Forms\Components\TextInput::make('penanggung')->placeholder('Nama perusahaan asuransi'),
                Forms\Components\Select::make('jenis')->required()->options([
                    'jiwa'        => 'Asuransi Jiwa',
                    'kredit'      => 'Asuransi Kredit',
                    'kesehatan'   => 'Asuransi Kesehatan',
                    'kebakaran'   => 'Asuransi Kebakaran',
                    'kendaraan'   => 'Asuransi Kendaraan',
                    'tabungan'    => 'Asuransi Tabungan',
                    'mikro'       => 'Asuransi Mikro',
                ]),
                Forms\Components\TextInput::make('rate_premi')->label('Rate Premi (% dari pertanggungan)')
                    ->numeric()->step(0.0001)->suffix('%')->default(0.5)
                    ->helperText('Mis. 0.5 = 0.5% dari nilai pertanggungan'),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('penanggung')->placeholder('—'),
                Tables\Columns\TextColumn::make('jenis')->badge()->color('info'),
                Tables\Columns\TextColumn::make('rate_premi')->suffix('%')->numeric(4),
                Tables\Columns\TextColumn::make('polis_count')->label('Total Polis')->counts('polis')->badge(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsuransiProduks::route('/'),
            'create' => Pages\CreateAsuransiProduk::route('/create'),
            'edit'   => Pages\EditAsuransiProduk::route('/{record}/edit'),
        ];
    }
}
