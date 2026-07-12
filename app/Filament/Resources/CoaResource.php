<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class CoaResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'coa';
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Chart of Accounts';
    protected static ?string $modelLabel = 'COA';
    protected static ?string $pluralModelLabel = 'Chart of Accounts';
    protected static ?int $navigationSort = 38;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->label('Kode Akun')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')->label('Nama Akun')->required(),
                Forms\Components\Select::make('tipe')->label('Tipe')->options([
                    'aset'       => 'Aset',
                    'kewajiban'  => 'Kewajiban',
                    'ekuitas'    => 'Ekuitas',
                    'pendapatan' => 'Pendapatan',
                    'beban'      => 'Beban',
                    'kontra'     => 'Kontra',
                ])->required()->live(),
                Forms\Components\Select::make('saldo_normal')->label('Saldo Normal')->options([
                    'debit'  => 'Debit',
                    'kredit' => 'Kredit',
                ])->required(),
                Forms\Components\TextInput::make('kelompok')->label('Kelompok')
                    ->placeholder('aset_lancar / aset_tetap / kewajiban_jangka_pendek / dll'),
                Forms\Components\Select::make('parent_id')->label('Parent Akun')
                    ->options(Coa::pluck('nama', 'id'))->searchable(),
                Forms\Components\TextInput::make('saldo_awal')->label('Saldo Awal')->numeric()->prefix('Rp')->default(0),
                Forms\Components\Toggle::make('is_kas')->label('Akun Kas'),
                Forms\Components\Toggle::make('is_bank')->label('Akun Bank'),
                Forms\Components\Toggle::make('is_postable')->label('Bisa di-posting jurnal')->default(true),
                Forms\Components\Toggle::make('is_aktif')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable()->sortable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('tipe')->badge()->color(fn ($state) => match ($state) {
                    'aset'       => 'success',
                    'kewajiban'  => 'danger',
                    'ekuitas'    => 'info',
                    'pendapatan' => 'primary',
                    'beban'      => 'warning',
                    default      => 'gray',
                }),
                Tables\Columns\TextColumn::make('saldo_normal')->label('Saldo Normal')->badge(),
                Tables\Columns\TextColumn::make('parent.nama')->label('Parent')->toggleable(),
                Tables\Columns\IconColumn::make('is_postable')->label('Posting')->boolean(),
                Tables\Columns\IconColumn::make('is_aktif')->label('Aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')->options([
                    'aset' => 'Aset', 'kewajiban' => 'Kewajiban', 'ekuitas' => 'Ekuitas',
                    'pendapatan' => 'Pendapatan', 'beban' => 'Beban',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->defaultSort('kode');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit'   => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}
