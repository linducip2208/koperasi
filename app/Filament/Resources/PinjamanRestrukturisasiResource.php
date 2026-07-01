<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinjamanRestrukturisasiResource\Pages;
use App\Models\Pinjaman;
use App\Models\PinjamanRestrukturisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PinjamanRestrukturisasiResource extends Resource
{
    protected static ?string $model = PinjamanRestrukturisasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Restrukturisasi';
    protected static ?string $modelLabel = 'Restrukturisasi';
    protected static ?string $pluralModelLabel = 'Restrukturisasi Pinjaman';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('pinjaman_id')->label('Pinjaman')
                    ->options(fn () => Pinjaman::with('anggota')->whereIn('status', ['aktif', 'macet'])->limit(100)->get()
                        ->mapWithKeys(fn ($p) => [$p->id => $p->nomor_akad . ' — ' . ($p->anggota->nama ?? '')]))
                    ->searchable()->required(),
                Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                Forms\Components\Select::make('jenis')->options([
                    'rescheduling'    => 'Rescheduling (Perpanjang Tenor)',
                    'restructuring'   => 'Restructuring (Ubah Plafon/Margin)',
                    'reconditioning'  => 'Reconditioning (Ubah Syarat)',
                    'top_up'          => 'Top Up (Tambah Plafon)',
                    'haircut'         => 'Haircut (Potong Pokok/Margin)',
                ])->required(),
            ]),
            Forms\Components\Section::make('Sebelum & Sesudah')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\KeyValue::make('sebelum')->required()
                        ->keyLabel('Field')->valueLabel('Nilai Sebelum')
                        ->default(['plafon' => '', 'tenor' => '', 'margin' => '']),
                    Forms\Components\KeyValue::make('sesudah')->required()
                        ->keyLabel('Field')->valueLabel('Nilai Sesudah')
                        ->default(['plafon' => '', 'tenor' => '', 'margin' => '']),
                ]),
                Forms\Components\Textarea::make('alasan')->required()->rows(3)
                    ->placeholder('Alasan restrukturisasi (kondisi anggota, force majeure, dll)'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('pinjaman.nomor_akad')->label('No. Akad')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('pinjaman.anggota.nama')->label('Anggota')->limit(20),
                Tables\Columns\TextColumn::make('jenis')->badge()->color(fn ($state) => match ($state) {
                    'rescheduling' => 'info', 'restructuring' => 'warning', 'reconditioning' => 'gray',
                    'top_up' => 'success', 'haircut' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('alasan')->limit(40),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')->options([
                    'rescheduling' => 'Rescheduling', 'restructuring' => 'Restructuring',
                    'reconditioning' => 'Reconditioning', 'top_up' => 'Top Up', 'haircut' => 'Haircut',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPinjamanRestrukturisasis::route('/'),
            'create' => Pages\CreatePinjamanRestrukturisasi::route('/create'),
            'edit'   => Pages\EditPinjamanRestrukturisasi::route('/{record}/edit'),
        ];
    }
}
