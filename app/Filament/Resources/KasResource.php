<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasResource\Pages;
use App\Models\Coa;
use App\Models\Kas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class KasResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'kas';
    protected static ?string $model = Kas::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Kas & Bank';
    protected static ?string $modelLabel = 'Kas';
    protected static ?string $pluralModelLabel = 'Kas & Bank';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Kas/Bank')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('kode')->label('Kode')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('nama')->label('Nama')->required(),
                    Forms\Components\Select::make('tipe')->label('Tipe')->options([
                        'kas'  => 'Kas (Tunai)',
                        'bank' => 'Rekening Bank',
                    ])->required()->live(),
                    Forms\Components\Select::make('coa_id')->label('Akun COA')
                        ->options(Coa::whereIn('tipe', ['aset'])->where('is_postable', true)->pluck('nama', 'id'))
                        ->searchable()->required(),
                    Forms\Components\Select::make('cabang_id')->label('Cabang')
                        ->relationship('cabang', 'nama')->searchable(),
                    Forms\Components\Select::make('user_id')->label('Penanggung Jawab')
                        ->relationship('user', 'name')->searchable(),
                ]),
            ]),
            Forms\Components\Section::make('Detail Bank')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nomor_rekening')->label('Nomor Rekening'),
                    Forms\Components\TextInput::make('nama_bank')->label('Nama Bank'),
                    Forms\Components\TextInput::make('atas_nama')->label('Atas Nama'),
                ]),
            ])->visible(fn ($get) => $get('tipe') === 'bank'),
            Forms\Components\Section::make('Saldo & Limit')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('saldo_awal')->label('Saldo Awal')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\TextInput::make('saldo')->label('Saldo Saat Ini')->numeric()->prefix('Rp')->default(0)->disabled(),
                    Forms\Components\TextInput::make('limit_minimum')->label('Limit Minimum (Warning)')->numeric()->prefix('Rp')->default(0),
                    Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('tipe')->badge()->color(fn ($state) => $state === 'bank' ? 'info' : 'success'),
                Tables\Columns\TextColumn::make('nomor_rekening')->label('No. Rek')->toggleable(),
                Tables\Columns\TextColumn::make('nama_bank')->label('Bank')->toggleable(),
                Tables\Columns\TextColumn::make('saldo')->label('Saldo')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')->label('PJ')->toggleable(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')->options(['kas' => 'Kas', 'bank' => 'Bank']),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKas::route('/'),
            'create' => Pages\CreateKas::route('/create'),
            'edit'   => Pages\EditKas::route('/{record}/edit'),
        ];
    }
}
