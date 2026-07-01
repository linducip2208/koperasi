<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoPenjualanResource\Pages;
use App\Models\Anggota;
use App\Models\Cabang;
use App\Models\Kas;
use App\Models\Simpanan;
use App\Models\TokoBarang;
use App\Models\TokoPenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoPenjualanResource extends Resource
{
    protected static ?string $model = TokoPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Penjualan (POS)';
    protected static ?string $modelLabel = 'Transaksi Penjualan';
    protected static ?string $pluralModelLabel = 'Transaksi Penjualan';
    protected static ?int $navigationSort = 31;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Transaksi')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nomor')->label('No. Transaksi')
                        ->placeholder('Otomatis kalau kosong')
                        ->maxLength(30)->unique(ignoreRecord: true),
                    Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                    Forms\Components\Select::make('cabang_id')->label('Cabang')
                        ->options(Cabang::where('aktif', true)->pluck('nama', 'id'))->searchable(),
                    Forms\Components\Select::make('anggota_id')->label('Anggota (opsional)')
                        ->options(fn () => Anggota::where('status', 'aktif')->limit(50)->pluck('nama', 'id'))
                        ->getSearchResultsUsing(fn (string $s) => Anggota::where('nama', 'like', "%{$s}%")->orWhere('nomor_anggota', 'like', "%{$s}%")->limit(50)->pluck('nama', 'id'))
                        ->searchable()->placeholder('Umum (non-anggota)'),
                    Forms\Components\Select::make('metode_bayar')->required()
                        ->options([
                            'cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS',
                            'debit' => 'Kartu Debit', 'simpanan' => 'Potong Simpanan', 'utang' => 'Utang Anggota',
                        ])->default('cash')->live(),
                    Forms\Components\Select::make('status')->options([
                        'lunas' => 'Lunas', 'pending' => 'Pending', 'batal' => 'Batal',
                    ])->default('lunas'),
                ]),
            ]),

            Forms\Components\Section::make('Item Belanja')->schema([
                Forms\Components\Repeater::make('detail')->relationship('detail')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('barang_id')->label('Barang')
                            ->options(fn () => TokoBarang::where('aktif', true)->limit(50)->pluck('nama', 'id'))
                            ->getSearchResultsUsing(fn (string $s) => TokoBarang::where('nama', 'like', "%{$s}%")->orWhere('barcode', 'like', "%{$s}%")->limit(30)->pluck('nama', 'id'))
                            ->searchable()->required()->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($b = TokoBarang::find($state)) {
                                    $set('harga_satuan', (int) ($b->harga_jual_umum ?? 0));
                                    $set('hpp', (int) ($b->harga_beli ?? 0));
                                }
                            }),
                        Forms\Components\TextInput::make('jumlah')->numeric()->default(1)->minValue(1)->required()->reactive()
                            ->afterStateUpdated(fn (Get $g, Set $s) => $s('subtotal', ((int) $g('jumlah')) * ((int) $g('harga_satuan')) - ((int) $g('diskon')))),
                        Forms\Components\TextInput::make('harga_satuan')->prefix('Rp')->numeric()->required()->reactive()
                            ->afterStateUpdated(fn (Get $g, Set $s) => $s('subtotal', ((int) $g('jumlah')) * ((int) $g('harga_satuan')) - ((int) $g('diskon')))),
                        Forms\Components\TextInput::make('diskon')->prefix('Rp')->numeric()->default(0)->reactive()
                            ->afterStateUpdated(fn (Get $g, Set $s) => $s('subtotal', ((int) $g('jumlah')) * ((int) $g('harga_satuan')) - ((int) $g('diskon')))),
                        Forms\Components\TextInput::make('subtotal')->prefix('Rp')->numeric()->disabled()->dehydrated(),
                        Forms\Components\Hidden::make('hpp')->default(0),
                    ])
                    ->columns(5)
                    ->defaultItems(1)->required()
                    ->addActionLabel('+ Tambah Barang')
                    ->reorderable(false),
            ]),

            Forms\Components\Section::make('Pembayaran')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('subtotal')->prefix('Rp')->numeric()->required()->minValue(0),
                    Forms\Components\TextInput::make('diskon')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('pajak')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('total')->prefix('Rp')->numeric()->required()->minValue(0),
                    Forms\Components\TextInput::make('bayar')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\TextInput::make('kembali')->prefix('Rp')->numeric()->default(0),
                    Forms\Components\Select::make('kas_id')->label('Kas Penerimaan')
                        ->options(Kas::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable()
                        ->visible(fn (Get $get) => in_array($get('metode_bayar'), ['cash', 'transfer', 'qris', 'debit'])),
                    Forms\Components\Select::make('simpanan_id')->label('Rekening Simpanan')
                        ->options(fn (Get $get) => $get('anggota_id')
                            ? Simpanan::where('anggota_id', $get('anggota_id'))->where('status', 'aktif')->pluck('nomor_rekening', 'id')
                            : [])
                        ->searchable()->visible(fn (Get $get) => $get('metode_bayar') === 'simpanan'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('No.')->copyable()->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Pembeli')->placeholder('Umum')->limit(20)->searchable(),
                Tables\Columns\TextColumn::make('cabang.nama')->label('Cabang')->toggleable(),
                Tables\Columns\TextColumn::make('subtotal')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('total')->money('IDR')->weight('bold')->color('success')->sortable(),
                Tables\Columns\TextColumn::make('metode_bayar')->badge()->color(fn ($state) => match ($state) {
                    'cash' => 'success', 'transfer' => 'info', 'qris' => 'warning', 'simpanan' => 'primary', 'utang' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'lunas' => 'success', 'pending' => 'warning', 'batal' => 'danger', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['lunas' => 'Lunas', 'pending' => 'Pending', 'batal' => 'Batal']),
                Tables\Filters\SelectFilter::make('metode_bayar')->options(['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS']),
                Tables\Filters\Filter::make('hari_ini')->label('Hari Ini')->query(fn ($q) => $q->whereDate('tanggal', today())),
            ])
            ->actions([
                Tables\Actions\Action::make('invoice')->label('Invoice PDF')
                    ->icon('heroicon-o-document-text')->color('info')
                    ->url(fn (TokoPenjualan $r) => route('dokumen.invoice', $r->id), shouldOpenInNewTab: true),
                Tables\Actions\Action::make('struk58')->label('Struk 58mm')
                    ->icon('heroicon-o-printer')->color('success')
                    ->url(fn (TokoPenjualan $r) => route('struk.penjualan', ['id' => $r->id, 'size' => '58']), shouldOpenInNewTab: true),
                Tables\Actions\Action::make('struk80')->label('Struk 80mm')
                    ->icon('heroicon-o-printer')->color('warning')
                    ->url(fn (TokoPenjualan $r) => route('struk.penjualan', ['id' => $r->id, 'size' => '80']), shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTokoPenjualans::route('/'),
            'create' => Pages\CreateTokoPenjualan::route('/create'),
            'edit' => Pages\EditTokoPenjualan::route('/{record}/edit'),
        ];
    }
}
