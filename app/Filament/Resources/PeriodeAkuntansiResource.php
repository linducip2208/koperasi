<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodeAkuntansiResource\Pages;
use App\Models\PeriodeAkuntansi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PeriodeAkuntansiResource extends Resource
{
    protected static ?string $model = PeriodeAkuntansi::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Periode Akuntansi';
    protected static ?string $modelLabel = 'Periode';
    protected static ?string $pluralModelLabel = 'Periode Akuntansi';
    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Select::make('tahun')->options(array_combine(range(2020, now()->year + 1), range(2020, now()->year + 1)))->required()->default(now()->year),
                Forms\Components\Select::make('bulan')->options(array_combine(range(1, 12), ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']))->required()->default(now()->month),
                Forms\Components\Select::make('status')->options(['open' => 'Open (boleh edit)', 'closed' => 'Closed (locked)'])->default('open')->required(),
                Forms\Components\DatePicker::make('tanggal_mulai')->required(),
                Forms\Components\DatePicker::make('tanggal_akhir')->required(),
                Forms\Components\DateTimePicker::make('closed_at')->label('Tanggal Tutup Buku')->seconds(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tahun', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tahun')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('bulan')->formatStateUsing(fn ($state) => ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$state] ?? $state),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('d M Y'),
                Tables\Columns\TextColumn::make('tanggal_akhir')->date('d M Y'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => $state === 'closed' ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('closed_at')->dateTime('d M Y H:i')->placeholder('—'),
            ])
            ->filters([Tables\Filters\SelectFilter::make('status')->options(['open' => 'Open', 'closed' => 'Closed'])])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeriodeAkuntansis::route('/'),
            'create' => Pages\CreatePeriodeAkuntansi::route('/create'),
            'edit'   => Pages\EditPeriodeAkuntansi::route('/{record}/edit'),
        ];
    }
}
