<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitProdusenSetoranResource\Pages;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\UnitProdusenKomoditi;
use App\Models\UnitProdusenSetoran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitProdusenSetoranResource extends Resource
{
    protected static ?string $model = UnitProdusenSetoran::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Setoran Produsen';
    protected static ?string $modelLabel = 'Setoran Produsen';
    protected static ?string $pluralModelLabel = 'Setoran Produsen';
    protected static ?int $navigationSort = 38;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Setoran')
                ->description('Setoran hasil produksi dari anggota produsen (susu, hasil tani, kerajinan).')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                        Forms\Components\Select::make('anggota_id')->label('Anggota Produsen')
                            ->options(fn () => Anggota::where('status', 'aktif')->limit(50)->pluck('nama', 'id'))
                            ->getSearchResultsUsing(fn (string $s) => Anggota::where('nama', 'like', "%{$s}%")->orWhere('nomor_anggota', 'like', "%{$s}%")->limit(30)->pluck('nama', 'id'))
                            ->searchable()->required(),
                        Forms\Components\Select::make('komoditi_id')->label('Komoditi')
                            ->options(UnitProdusenKomoditi::where('aktif', true)->pluck('nama', 'id'))
                            ->searchable()->required()->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($k = UnitProdusenKomoditi::find($state)) {
                                    $set('harga_satuan', (int) $k->harga_beli_default);
                                    $jumlah = (float) ($get('jumlah') ?? 0);
                                    $set('total', (int) round($jumlah * (int) $k->harga_beli_default));
                                }
                            }),
                    ]),
                ]),

            Forms\Components\Section::make('Volume & Harga')->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\TextInput::make('jumlah')->label('Jumlah / Volume')
                        ->numeric()->step(0.001)->required()->minValue(0)->live(onBlur: true)
                        ->helperText('Sesuai satuan komoditi (kg / liter / unit)')
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) round(((float) $g('jumlah')) * ((int) $g('harga_satuan'))))),
                    Forms\Components\TextInput::make('harga_satuan')->prefix('Rp')->numeric()->required()->minValue(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) round(((float) $g('jumlah')) * ((int) $g('harga_satuan'))))),
                    Forms\Components\TextInput::make('total')->prefix('Rp')->numeric()->required()->minValue(0)
                        ->disabled()->dehydrated(),
                    Forms\Components\Select::make('kualitas')->options([
                        'A' => 'Grade A (Premium)', 'B' => 'Grade B (Standar)', 'C' => 'Grade C (Reject)',
                    ])->placeholder('Tidak dinilai'),
                ]),
            ]),

            Forms\Components\Section::make('Pembayaran ke Produsen')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('terbayar')->label('Sudah Dibayar')
                        ->prefix('Rp')->numeric()->default(0)->minValue(0)
                        ->helperText('0 = belum dibayar, < total = pembayaran sebagian'),
                    Forms\Components\Select::make('kas_id')->label('Kas Pengeluaran')
                        ->options(Kas::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable(),
                ]),
                Forms\Components\Textarea::make('catatan')->rows(2)->placeholder('Misal: kualitas, asal kebun, dll.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Produsen')->searchable()->limit(25),
                Tables\Columns\TextColumn::make('komoditi.nama')->label('Komoditi')->badge()->color('success'),
                Tables\Columns\TextColumn::make('jumlah')->numeric(decimalPlaces: 3)->sortable()
                    ->suffix(fn ($record) => $record->komoditi ? ' ' . $record->komoditi->satuan : ''),
                Tables\Columns\TextColumn::make('harga_satuan')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('total')->money('IDR')->weight('bold')->color('success')->sortable(),
                Tables\Columns\TextColumn::make('terbayar')->money('IDR')->color('info')->toggleable(),
                Tables\Columns\TextColumn::make('kualitas')->badge()->placeholder('—')
                    ->color(fn ($state) => match ($state) { 'A' => 'success', 'B' => 'warning', 'C' => 'danger', default => 'gray' }),
                Tables\Columns\IconColumn::make('lunas')->label('Lunas')
                    ->getStateUsing(fn ($record) => $record->terbayar >= $record->total)
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('komoditi_id')->label('Komoditi')->relationship('komoditi', 'nama'),
                Tables\Filters\SelectFilter::make('kualitas')->options(['A' => 'Grade A', 'B' => 'Grade B', 'C' => 'Grade C']),
                Tables\Filters\Filter::make('belum_lunas')->label('Belum Lunas')
                    ->query(fn ($q) => $q->whereColumn('terbayar', '<', 'total')),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnitProdusenSetorans::route('/'),
            'create' => Pages\CreateUnitProdusenSetoran::route('/create'),
            'edit'   => Pages\EditUnitProdusenSetoran::route('/{record}/edit'),
        ];
    }
}
