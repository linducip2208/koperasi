<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanMasterResource\Pages;
use App\Models\Coa;
use App\Models\TagihanMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TagihanMasterResource extends Resource
{
    protected static ?string $model = TagihanMaster::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Master Iuran/Tagihan';
    protected static ?string $modelLabel = 'Iuran';
    protected static ?string $pluralModelLabel = 'Master Iuran/Tagihan';
    protected static ?int $navigationSort = 93;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('kode')->required()->maxLength(20)->unique(ignoreRecord: true)
                    ->placeholder('IURAN-WAJIB'),
                Forms\Components\TextInput::make('nama')->required()->maxLength(255)
                    ->placeholder('Iuran Wajib Bulanan'),
                Forms\Components\TextInput::make('nominal')->prefix('Rp')->numeric()->required(),
                Forms\Components\Select::make('siklus')->required()->options([
                    'bulanan'      => 'Bulanan',
                    'tahunan'      => 'Tahunan',
                    'sekali_bayar' => 'Sekali Bayar',
                ])->default('bulanan'),
                Forms\Components\Select::make('coa_id')->label('COA Pendapatan')
                    ->options(Coa::where('tipe', 'pendapatan')->pluck('nama', 'id'))
                    ->searchable(),
                Forms\Components\Toggle::make('auto_potong_simpanan')->label('Auto Potong dari Simpanan')->default(false)
                    ->helperText('Jika true, sistem otomatis potong dari saldo simpanan tiap siklus'),
                Forms\Components\Toggle::make('aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->copyable()->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('nominal')->money('IDR'),
                Tables\Columns\TextColumn::make('siklus')->badge()->color(fn ($state) => match ($state) {
                    'bulanan' => 'success', 'tahunan' => 'warning', 'sekali_bayar' => 'gray', default => 'gray',
                }),
                Tables\Columns\IconColumn::make('auto_potong_simpanan')->label('Auto Potong')->boolean(),
                Tables\Columns\TextColumn::make('tagihan_count')->label('Tagihan Aktif')->counts('tagihan')->badge(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('siklus')->options(['bulanan' => 'Bulanan', 'tahunan' => 'Tahunan', 'sekali_bayar' => 'Sekali']),
                Tables\Filters\TernaryFilter::make('aktif'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTagihanMasters::route('/'),
            'create' => Pages\CreateTagihanMaster::route('/create'),
            'edit'   => Pages\EditTagihanMaster::route('/{record}/edit'),
        ];
    }
}
