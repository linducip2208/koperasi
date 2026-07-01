<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitJasaOrderResource\Pages;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\UnitJasaLayanan;
use App\Models\UnitJasaOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitJasaOrderResource extends Resource
{
    protected static ?string $model = UnitJasaOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Order Jasa';
    protected static ?string $modelLabel = 'Order Jasa';
    protected static ?string $pluralModelLabel = 'Order Jasa';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nomor')->label('No. Order')
                        ->placeholder('Otomatis kalau kosong')->maxLength(30)->unique(ignoreRecord: true),
                    Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                    Forms\Components\Select::make('status')->options([
                        'booking'   => 'Booking',
                        'proses'    => 'Sedang Diproses',
                        'selesai'   => 'Selesai',
                        'batal'     => 'Batal',
                    ])->default('booking')->required(),
                    Forms\Components\Select::make('layanan_id')->label('Layanan')
                        ->options(UnitJasaLayanan::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable()->required()->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($l = UnitJasaLayanan::find($state)) {
                                $set('total', (int) $l->tarif);
                            }
                        }),
                    Forms\Components\Select::make('anggota_id')->label('Anggota Referral / Penanggung Jawab')
                        ->options(fn () => Anggota::where('status', 'aktif')->limit(50)->pluck('nama', 'id'))
                        ->getSearchResultsUsing(fn (string $s) => Anggota::where('nama', 'like', "%{$s}%")->limit(30)->pluck('nama', 'id'))
                        ->searchable()->placeholder('Tidak ada (jasa langsung)'),
                    Forms\Components\TextInput::make('nama_pelanggan')->label('Nama Pelanggan')
                        ->placeholder('Kosongkan kalau anggota sendiri'),
                ]),
            ]),

            Forms\Components\Section::make('Pembayaran')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('total')->label('Total Tagihan')
                        ->prefix('Rp')->numeric()->required()->minValue(0),
                    Forms\Components\TextInput::make('komisi_anggota')->label('Komisi untuk Anggota')
                        ->prefix('Rp')->numeric()->default(0)->minValue(0)
                        ->helperText('Bagian yang masuk ke anggota referral (jika ada)'),
                    Forms\Components\TextInput::make('bayar')->label('Sudah Dibayar')
                        ->prefix('Rp')->numeric()->default(0)->minValue(0),
                    Forms\Components\Select::make('kas_id')->label('Kas Penerimaan')
                        ->options(Kas::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->copyable()->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('layanan.nama')->label('Layanan')->badge()->color('info'),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Referral')->placeholder('—')->limit(20),
                Tables\Columns\TextColumn::make('nama_pelanggan')->label('Pelanggan')->placeholder('—')->limit(20)->toggleable(),
                Tables\Columns\TextColumn::make('total')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('komisi_anggota')->label('Komisi')->money('IDR')->color('warning')->toggleable(),
                Tables\Columns\TextColumn::make('bayar')->money('IDR')->color('success')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'booking' => 'info', 'proses' => 'warning', 'selesai' => 'success', 'batal' => 'danger', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'booking' => 'Booking', 'proses' => 'Proses', 'selesai' => 'Selesai', 'batal' => 'Batal',
                ]),
                Tables\Filters\SelectFilter::make('layanan_id')->label('Layanan')
                    ->relationship('layanan', 'nama'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnitJasaOrders::route('/'),
            'create' => Pages\CreateUnitJasaOrder::route('/create'),
            'edit'   => Pages\EditUnitJasaOrder::route('/{record}/edit'),
        ];
    }
}
