<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpobTransaksiResource\Pages;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\PpobTransaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PpobTransaksiResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'pos';

    protected static ?string $model = PpobTransaksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Transaksi PPOB';
    protected static ?string $modelLabel = 'Transaksi PPOB';
    protected static ?int $navigationSort = 28;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('ppob_produk_id')->label('Produk')->relationship('produk', 'nama')->searchable()->preload()->required(),
            Forms\Components\Select::make('anggota_id')->label('Anggota')->relationship('anggota', 'nama')->searchable()->preload(),
            Forms\Components\TextInput::make('no_tujuan')->label('No. Tujuan')->required()->maxLength(30),
            Forms\Components\TextInput::make('harga')->label('Harga')->prefix('Rp')->numeric()->required(),
            Forms\Components\Select::make('status')->label('Status')->options(['pending' => 'Pending', 'sukses' => 'Sukses', 'gagal' => 'Gagal', 'refund' => 'Refund'])->default('pending'),
            Forms\Components\Textarea::make('keterangan')->label('Keterangan')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('No.')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->searchable(),
                Tables\Columns\TextColumn::make('no_tujuan')->label('Tujuan'),
                Tables\Columns\IconColumn::make('anggota_id')->label('Anggota')->boolean(),
                Tables\Columns\TextColumn::make('harga')->label('Harga')->money('IDR'),
                Tables\Columns\TextColumn::make('laba')->label('Laba')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()->color(fn ($s) => match ($s) {'sukses' => 'success', 'gagal' => 'danger', 'refund' => 'warning', default => 'gray'}),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPpobTransaksis::route('/'),
            'create' => Pages\CreatePpobTransaksi::route('/create'),
            'edit' => Pages\EditPpobTransaksi::route('/{record}/edit'),
        ];
    }
}
