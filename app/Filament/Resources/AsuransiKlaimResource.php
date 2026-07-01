<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsuransiKlaimResource\Pages;
use App\Models\AsuransiKlaim;
use App\Models\AsuransiPolis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AsuransiKlaimResource extends Resource
{
    protected static ?string $model = AsuransiKlaim::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Asuransi';
    protected static ?string $navigationLabel = 'Klaim Asuransi';
    protected static ?string $modelLabel = 'Klaim Asuransi';
    protected static ?string $pluralModelLabel = 'Klaim Asuransi';
    protected static ?int $navigationSort = 73;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Klaim')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('polis_id')->label('Polis')
                        ->options(fn () => AsuransiPolis::with('anggota')->where('status', 'aktif')->limit(100)->get()->mapWithKeys(fn ($p) => [$p->id => $p->nomor_polis . ' — ' . ($p->anggota->nama ?? '')]))
                        ->searchable()->required(),
                    Forms\Components\Select::make('status')->options([
                        'pengajuan'  => 'Pengajuan',
                        'verifikasi' => 'Verifikasi',
                        'disetujui'  => 'Disetujui',
                        'dibayar'    => 'Sudah Dibayar',
                        'ditolak'    => 'Ditolak',
                    ])->default('pengajuan')->required(),
                    Forms\Components\DatePicker::make('tanggal_kejadian')->required(),
                    Forms\Components\DatePicker::make('tanggal_pengajuan')->required()->default(now()),
                ]),
            ]),
            Forms\Components\Section::make('Nilai Klaim')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('nilai_klaim')->prefix('Rp')->numeric()->required(),
                    Forms\Components\TextInput::make('nilai_diterima')->prefix('Rp')->numeric()->default(0)
                        ->helperText('Diisi setelah klaim disetujui & dibayar'),
                    Forms\Components\DatePicker::make('tanggal_diterima'),
                ]),
                Forms\Components\Textarea::make('uraian')->rows(3)->placeholder('Kronologi kejadian, dokumen pendukung, dll.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_pengajuan', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('polis.nomor_polis')->label('Polis')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('polis.anggota.nama')->label('Anggota')->limit(20),
                Tables\Columns\TextColumn::make('tanggal_kejadian')->date('d M Y'),
                Tables\Columns\TextColumn::make('nilai_klaim')->money('IDR')->color('danger'),
                Tables\Columns\TextColumn::make('nilai_diterima')->money('IDR')->color('success')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'pengajuan' => 'warning', 'verifikasi' => 'info', 'disetujui' => 'success',
                    'dibayar' => 'success', 'ditolak' => 'danger', default => 'gray',
                }),
            ])
            ->filters([Tables\Filters\SelectFilter::make('status')->options(['pengajuan' => 'Pengajuan', 'verifikasi' => 'Verifikasi', 'disetujui' => 'Disetujui', 'dibayar' => 'Dibayar', 'ditolak' => 'Ditolak'])])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsuransiKlaims::route('/'),
            'create' => Pages\CreateAsuransiKlaim::route('/create'),
            'edit'   => Pages\EditAsuransiKlaim::route('/{record}/edit'),
        ];
    }
}
