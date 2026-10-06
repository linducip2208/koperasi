<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\RatVotingResource\Pages;
use App\Models\Rat;
use App\Models\RatVoting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RatVotingResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'rat';
    protected static ?string $model = RatVoting::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'GOVERNANCE';
    protected static ?string $navigationLabel = 'E-Voting RAT';
    protected static ?int $navigationSort = 72;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Voting')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('rat_id')
                        ->label('RAT')
                        ->options(Rat::orderByDesc('tahun_buku')->pluck('tahun_buku', 'id'))
                        ->required()->searchable(),
                    Forms\Components\TextInput::make('judul')
                        ->required()->maxLength(255)
                        ->placeholder('cth: Persetujuan Laporan Keuangan 2025'),
                    Forms\Components\Textarea::make('deskripsi')
                        ->columnSpanFull()->rows(2),
                    Forms\Components\TagsInput::make('opsi')
                        ->label('Pilihan Suara')
                        ->placeholder('Ketik opsi lalu Enter')
                        ->default(['Setuju', 'Tidak Setuju'])
                        ->columnSpanFull()
                        ->helperText('Minimal 2 opsi, mis. Setuju / Tidak Setuju'),
                    Forms\Components\DateTimePicker::make('mulai')->label('Mulai')->required(),
                    Forms\Components\DateTimePicker::make('selesai')->label('Selesai')->required(),
                    Forms\Components\Toggle::make('is_aktif')->label('Aktif')->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rat.tahun_buku')->label('RAT')->sortable(),
                Tables\Columns\TextColumn::make('judul')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('suara_count')->label('Suara')->counts('suara')->badge()->color('success'),
                Tables\Columns\IconColumn::make('is_aktif')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('mulai')->dateTime('d M H:i'),
                Tables\Columns\TextColumn::make('selesai')->dateTime('d M H:i'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatVotings::route('/'),
            'create' => Pages\CreateRatVoting::route('/create'),
            'edit' => Pages\EditRatVoting::route('/{record}/edit'),
        ];
    }
}
