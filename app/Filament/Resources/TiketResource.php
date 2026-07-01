<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketResource\Pages;
use App\Models\Tiket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TiketResource extends Resource
{
    protected static ?string $model = Tiket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Tiket Support';
    protected static ?string $modelLabel = 'Tiket';
    protected static ?string $pluralModelLabel = 'Tiket Support';
    protected static ?int $navigationSort = 95;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('nomor')->label('No. Tiket')->disabled()->dehydrated(),
                Forms\Components\Select::make('anggota_id')->label('Anggota')
                    ->relationship('anggota', 'nama')->searchable()->preload(),
                Forms\Components\TextInput::make('subjek')->label('Subjek')->required()->maxLength(255),
                Forms\Components\Select::make('kategori')->label('Kategori')->options([
                    'umum' => 'Umum', 'simpanan' => 'Simpanan', 'pinjaman' => 'Pinjaman',
                    'teknis' => 'Teknis', 'keluhan' => 'Keluhan',
                ])->default('umum'),
                Forms\Components\Select::make('prioritas')->label('Prioritas')->options([
                    'rendah' => 'Rendah', 'normal' => 'Normal', 'tinggi' => 'Tinggi', 'urgent' => 'Urgent',
                ])->default('normal'),
                Forms\Components\Select::make('status')->label('Status')->options([
                    'terbuka' => 'Terbuka', 'proses' => 'Proses', 'menunggu' => 'Menunggu',
                    'selesai' => 'Selesai', 'tutup' => 'Tutup',
                ])->default('terbuka'),
                Forms\Components\Select::make('assigned_to')->label('Ditugaskan ke')
                    ->relationship('assignedTo', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('sla_jam')->label('SLA (jam)')->numeric()->default(24),
            ]),
            Forms\Components\RichEditor::make('deskripsi')->label('Deskripsi')->required()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('No.')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subjek')->label('Subjek')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori')->badge(),
                Tables\Columns\TextColumn::make('prioritas')->label('Prioritas')->badge()->color(fn ($s) => match ($s) {
                    'urgent' => 'danger', 'tinggi' => 'warning', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()->color(fn ($s) => match ($s) {
                    'selesai' => 'success', 'tutup' => 'gray', 'proses' => 'info', default => 'warning',
                }),
                Tables\Columns\TextColumn::make('assignedTo.name')->label('Ditugaskan'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('prioritas'),
                Tables\Filters\SelectFilter::make('kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTikets::route('/'),
            'create' => Pages\CreateTiket::route('/create'),
            'edit'   => Pages\EditTiket::route('/{record}/edit'),
        ];
    }
}
