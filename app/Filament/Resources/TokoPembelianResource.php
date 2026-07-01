<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokoPembelianResource\Pages;
use App\Models\Cabang;
use App\Models\TokoPembelian;
use App\Models\TokoSupplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TokoPembelianResource extends Resource
{
    protected static ?string $model = TokoPembelian::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'Pembelian Barang';
    protected static ?string $modelLabel = 'Pembelian';
    protected static ?string $pluralModelLabel = 'Pembelian';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pembelian')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nomor')->placeholder('Auto-generate kalau kosong')->maxLength(30)->unique(ignoreRecord: true),
                    Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                    Forms\Components\DatePicker::make('tanggal_jatuh_tempo')->default(now()->addDays(30)),
                    Forms\Components\Select::make('supplier_id')->label('Supplier')
                        ->options(TokoSupplier::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable()->required(),
                    Forms\Components\Select::make('cabang_id')->options(Cabang::where('aktif', true)->pluck('nama', 'id'))->searchable(),
                    Forms\Components\Select::make('status')->options([
                        'draft' => 'Draft', 'diterima' => 'Barang Diterima', 'lunas' => 'Lunas', 'belum_lunas' => 'Belum Lunas',
                    ])->default('draft'),
                ]),
            ]),
            Forms\Components\Section::make('Nominal')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('subtotal')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) $g('subtotal') - (int) $g('diskon') + (int) $g('pajak') + (int) $g('biaya_lain'))),
                    Forms\Components\TextInput::make('diskon')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) $g('subtotal') - (int) $g('diskon') + (int) $g('pajak') + (int) $g('biaya_lain'))),
                    Forms\Components\TextInput::make('pajak')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) $g('subtotal') - (int) $g('diskon') + (int) $g('pajak') + (int) $g('biaya_lain'))),
                    Forms\Components\TextInput::make('biaya_lain')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('total', (int) $g('subtotal') - (int) $g('diskon') + (int) $g('pajak') + (int) $g('biaya_lain'))),
                    Forms\Components\TextInput::make('total')->prefix('Rp')->numeric()->required()->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('terbayar')->prefix('Rp')->numeric()->default(0),
                ]),
                Forms\Components\Textarea::make('keterangan')->rows(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->copyable()->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('supplier.nama')->searchable()->limit(20),
                Tables\Columns\TextColumn::make('subtotal')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('total')->money('IDR')->weight('bold')->color('danger')->sortable(),
                Tables\Columns\TextColumn::make('terbayar')->money('IDR')->color('success')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'draft' => 'gray', 'diterima' => 'info', 'lunas' => 'success', 'belum_lunas' => 'warning', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('supplier_id')->relationship('supplier', 'nama'),
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'diterima' => 'Diterima', 'lunas' => 'Lunas', 'belum_lunas' => 'Belum Lunas']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokoPembelians::route('/'),
            'create' => Pages\CreateTokoPembelian::route('/create'),
            'edit'   => Pages\EditTokoPembelian::route('/{record}/edit'),
        ];
    }
}
