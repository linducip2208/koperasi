<?php

namespace App\Filament\Resources;

use App\Domain\Simpanan\SimpananService;
use App\Filament\Resources\SimpananResource\Pages;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\ProdukSimpanan;
use App\Models\Simpanan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Simpanan';
    protected static ?string $modelLabel = 'Simpanan';
    protected static ?string $pluralModelLabel = 'Simpanan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Buka Rekening Simpanan')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('anggota_id')->label('Anggota')
                        ->relationship('anggota', 'nama')
                        ->options(Anggota::where('status', 'aktif')->pluck('nama', 'id'))
                        ->searchable()->preload()->required(),
                    Forms\Components\Select::make('produk_id')->label('Produk Simpanan')
                        ->options(ProdukSimpanan::where('aktif', true)->pluck('nama', 'id'))
                        ->required(),
                    Forms\Components\TextInput::make('nomor_rekening')->label('Nomor Rekening')
                        ->placeholder('Otomatis jika kosong'),
                    Forms\Components\DatePicker::make('tanggal_buka')->label('Tanggal Buka')->default(now())->required(),
                    Forms\Components\Select::make('status')->options([
                        'aktif' => 'Aktif', 'tutup' => 'Tutup', 'blokir' => 'Blokir', 'jatuh_tempo' => 'Jatuh Tempo',
                    ])->default('aktif')->required(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_rekening')->label('No. Rek')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->badge(),
                Tables\Columns\TextColumn::make('saldo')->label('Saldo')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('saldo_blokir')->label('Diblokir')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'aktif' => 'success', 'tutup' => 'gray', 'blokir' => 'danger', 'jatuh_tempo' => 'warning',
                    default => 'info',
                }),
                Tables\Columns\TextColumn::make('tanggal_buka')->label('Buka')->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'aktif' => 'Aktif', 'tutup' => 'Tutup', 'blokir' => 'Blokir', 'jatuh_tempo' => 'Jatuh Tempo',
                ]),
                Tables\Filters\SelectFilter::make('produk_id')->label('Produk')
                    ->options(ProdukSimpanan::pluck('nama', 'id')),
            ])
            ->actions([
                Tables\Actions\Action::make('setor')->label('Setor')
                    ->icon('heroicon-o-arrow-down-tray')->color('success')
                    ->visible(fn (Simpanan $r) => $r->status === 'aktif')
                    ->form([
                        Forms\Components\TextInput::make('jumlah')->label('Jumlah')->numeric()->prefix('Rp')->required()->minValue(1),
                        Forms\Components\Select::make('kas_id')->label('Kas Penerimaan')
                            ->options(Kas::where('aktif', true)->pluck('nama', 'id'))->required(),
                        Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->default(now())->required(),
                        Forms\Components\Select::make('metode')->label('Metode')
                            ->options(['cash' => 'Tunai', 'transfer' => 'Transfer', 'potong_gaji' => 'Potong Gaji'])
                            ->default('cash'),
                        Forms\Components\Textarea::make('keterangan')->label('Keterangan'),
                    ])
                    ->action(function (Simpanan $r, array $data) {
                        SimpananService::setor($r, (int) $data['jumlah'], (int) $data['kas_id'], Carbon::parse($data['tanggal']), $data['metode'] ?? 'cash', $data['keterangan'] ?? null);
                        Notification::make()->title('Setoran berhasil')->success()->send();
                    }),
                Tables\Actions\Action::make('tarik')->label('Tarik')
                    ->icon('heroicon-o-arrow-up-tray')->color('warning')
                    ->visible(fn (Simpanan $r) => $r->status === 'aktif' && $r->produk?->boleh_tarik)
                    ->form([
                        Forms\Components\TextInput::make('jumlah')->label('Jumlah')->numeric()->prefix('Rp')->required()->minValue(1),
                        Forms\Components\Select::make('kas_id')->label('Kas Pengeluaran')
                            ->options(Kas::where('aktif', true)->pluck('nama', 'id'))->required(),
                        Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->default(now())->required(),
                        Forms\Components\Select::make('metode')->label('Metode')
                            ->options(['cash' => 'Tunai', 'transfer' => 'Transfer'])->default('cash'),
                        Forms\Components\Textarea::make('keterangan')->label('Keterangan'),
                    ])
                    ->action(function (Simpanan $r, array $data) {
                        SimpananService::tarik($r, (int) $data['jumlah'], (int) $data['kas_id'], Carbon::parse($data['tanggal']), $data['metode'] ?? 'cash', $data['keterangan'] ?? null);
                        Notification::make()->title('Penarikan berhasil')->success()->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\SimpananResource\RelationManagers\TransaksiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'edit'   => Pages\EditSimpanan::route('/{record}/edit'),
            'view'   => Pages\ViewSimpanan::route('/{record}'),
        ];
    }
}
