<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?string $navigationLabel = 'Referral';
    protected static ?string $modelLabel = 'Referral';
    protected static ?string $pluralModelLabel = 'Referral';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('pengajak_id')
                ->label('Pengajak')
                ->relationship('pengajak', 'nama')
                ->searchable()->preload()->required(),
            Forms\Components\Select::make('diajak_id')
                ->label('Diajak')
                ->relationship('diajak', 'nama')
                ->searchable()->preload()->required(),
            Forms\Components\TextInput::make('kode_referral')
                ->label('Kode Referral')
                ->required()->maxLength(20),
            Forms\Components\TextInput::make('komisi')
                ->label('Komisi')
                ->prefix('Rp')->numeric()->default(0),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'terdaftar' => 'Terdaftar',
                    'aktif' => 'Aktif',
                    'komisi_dibayar' => 'Komisi Dibayar',
                ])->default('pending')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengajak.nama')
                    ->label('Pengajak')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('diajak.nama')
                    ->label('Diajak')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kode_referral')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'terdaftar' => 'info',
                        'aktif' => 'success',
                        'komisi_dibayar' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('komisi')
                    ->label('Komisi')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'terdaftar' => 'Terdaftar',
                        'aktif' => 'Aktif',
                        'komisi_dibayar' => 'Komisi Dibayar',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReferrals::route('/'),
            'create' => Pages\CreateReferral::route('/create'),
            'edit'   => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}
