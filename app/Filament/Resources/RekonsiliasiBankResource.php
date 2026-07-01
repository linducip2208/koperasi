<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekonsiliasiBankResource\Pages;
use App\Models\Kas;
use App\Models\RekonsiliasiBank;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RekonsiliasiBankResource extends Resource
{
    protected static ?string $model = RekonsiliasiBank::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Rekonsiliasi Bank';
    protected static ?string $modelLabel = 'Rekonsiliasi Bank';
    protected static ?string $pluralModelLabel = 'Rekonsiliasi Bank';
    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Periode & Akun')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('kas_id')->label('Akun Bank')
                        ->options(Kas::where('tipe', 'bank')->where('aktif', true)->pluck('nama', 'id'))
                        ->searchable()->required(),
                    Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                    Forms\Components\DatePicker::make('periode_akhir')->required()->default(now()->endOfMonth()),
                ]),
            ]),
            Forms\Components\Section::make('Saldo')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('saldo_buku')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('selisih', (int) $g('saldo_bank') - (int) $g('saldo_buku')))
                        ->helperText('Saldo menurut catatan koperasi'),
                    Forms\Components\TextInput::make('saldo_bank')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => $s('selisih', (int) $g('saldo_bank') - (int) $g('saldo_buku')))
                        ->helperText('Saldo dari rekening koran bank'),
                    Forms\Components\TextInput::make('selisih')->prefix('Rp')->numeric()->disabled()->dehydrated(),
                    Forms\Components\Select::make('status')->options([
                        'draft' => 'Draft', 'matched' => 'Sudah Match', 'pending' => 'Pending', 'closed' => 'Closed',
                    ])->default('draft'),
                ]),
                Forms\Components\KeyValue::make('rincian')->label('Rincian Transaksi Tidak Cocok')->keyLabel('Deskripsi')->valueLabel('Nominal'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('kas.nama')->label('Bank')->badge()->color('info'),
                Tables\Columns\TextColumn::make('periode_akhir')->date('d M Y'),
                Tables\Columns\TextColumn::make('saldo_buku')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('saldo_bank')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('selisih')->money('IDR')
                    ->color(fn ($state) => $state == 0 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'draft' => 'gray', 'matched' => 'success', 'pending' => 'warning', 'closed' => 'info', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kas_id')->relationship('kas', 'nama')->label('Akun Bank'),
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'matched' => 'Match', 'pending' => 'Pending', 'closed' => 'Closed']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRekonsiliasiBanks::route('/'),
            'create' => Pages\CreateRekonsiliasiBank::route('/create'),
            'edit'   => Pages\EditRekonsiliasiBank::route('/{record}/edit'),
        ];
    }
}
