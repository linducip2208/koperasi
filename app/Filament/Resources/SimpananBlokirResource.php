<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpananBlokirResource\Pages;
use App\Models\Simpanan;
use App\Models\SimpananBlokir;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SimpananBlokirResource extends Resource
{
    protected static ?string $model = SimpananBlokir::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Blokir Simpanan';
    protected static ?string $modelLabel = 'Blokir';
    protected static ?string $pluralModelLabel = 'Blokir Simpanan';
    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('simpanan_id')->label('Rekening Simpanan')
                    ->options(fn () => Simpanan::with('anggota')->where('status', 'aktif')->limit(100)->get()
                        ->mapWithKeys(fn ($s) => [$s->id => $s->nomor_rekening . ' — ' . ($s->anggota->nama ?? '')]))
                    ->searchable()->required(),
                Forms\Components\TextInput::make('jumlah')->prefix('Rp')->numeric()->required()->minValue(1),
                Forms\Components\TextInput::make('alasan')->required()->maxLength(255)
                    ->placeholder('mis. Jaminan Pinjaman, Klaim Asuransi, Sengketa'),
                Forms\Components\DatePicker::make('tanggal_blokir')->required()->default(now()),
                Forms\Components\DatePicker::make('tanggal_lepas')->placeholder('Diisi saat blokir dilepas'),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_blokir', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('simpanan.nomor_rekening')->label('No. Rek')->copyable(),
                Tables\Columns\TextColumn::make('simpanan.anggota.nama')->label('Anggota')->limit(20),
                Tables\Columns\TextColumn::make('jumlah')->money('IDR')->color('warning')->sortable(),
                Tables\Columns\TextColumn::make('alasan')->limit(30),
                Tables\Columns\TextColumn::make('tanggal_blokir')->date('d M Y'),
                Tables\Columns\TextColumn::make('tanggal_lepas')->date('d M Y')->placeholder('—'),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSimpananBlokirs::route('/'),
            'create' => Pages\CreateSimpananBlokir::route('/create'),
            'edit'   => Pages\EditSimpananBlokir::route('/{record}/edit'),
        ];
    }
}
