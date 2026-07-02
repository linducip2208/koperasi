<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SewaAsetResource\Pages;
use App\Models\SewaAset;
use App\Models\Asset;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SewaAsetResource extends Resource
{
    protected static ?string $model = SewaAset::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = '👥 HR & Asset';
    protected static ?string $navigationLabel = 'Sewa Aset';
    protected static ?string $modelLabel = 'Sewa Aset';
    protected static ?string $pluralModelLabel = 'Sewa Aset';
    protected static ?int $navigationSort = 54;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('anggota_id')->label('Anggota')
                    ->options(Anggota::where('status', 'aktif')->pluck('nama', 'id'))
                    ->searchable()->preload()->required(),
                Forms\Components\Select::make('asset_id')->label('Aset')
                    ->options(Asset::pluck('nama', 'id'))
                    ->searchable()->preload()->required(),
                Forms\Components\Select::make('tenant_id')->label('Tenant')
                    ->relationship('tenant', 'nama')->required(),
                Forms\Components\DatePicker::make('tanggal_mulai')->label('Tgl Mulai Sewa')->required(),
                Forms\Components\DatePicker::make('tanggal_selesai')->label('Tgl Selesai Sewa')->required(),
                Forms\Components\TextInput::make('tarif_per_hari')->label('Tarif/Hari')->numeric()->prefix('Rp'),
                Forms\Components\TextInput::make('dp')->label('DP')->numeric()->prefix('Rp'),
                Forms\Components\Select::make('status')->label('Status')
                    ->options(['disewa' => 'Disewa', 'dikembalikan' => 'Dikembalikan', 'terlambat' => 'Terlambat'])
                    ->default('disewa')->required(),
                Forms\Components\Textarea::make('catatan')->label('Catatan')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('asset.nama')->label('Aset')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('tarif_per_hari')->money('IDR'),
                Tables\Columns\TextColumn::make('dp')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'disewa' => 'warning', 'dikembalikan' => 'success', 'terlambat' => 'danger', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['disewa' => 'Disewa', 'dikembalikan' => 'Dikembalikan', 'terlambat' => 'Terlambat']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('tanggal_mulai', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSewaAsets::route('/'),
            'create' => Pages\CreateSewaAset::route('/create'),
            'edit'   => Pages\EditSewaAset::route('/{record}/edit'),
        ];
    }
}
