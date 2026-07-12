<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsuransiPolisResource\Pages;
use App\Models\Anggota;
use App\Models\AsuransiPolis;
use App\Models\AsuransiProduk;
use App\Models\Pinjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class AsuransiPolisResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'asuransi';
    protected static ?string $model = AsuransiPolis::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Asuransi';
    protected static ?string $navigationLabel = 'Polis Anggota';
    protected static ?string $modelLabel = 'Polis Asuransi';
    protected static ?string $pluralModelLabel = 'Polis Asuransi';
    protected static ?int $navigationSort = 62;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Polis')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('nomor_polis')->required()->maxLength(50)->unique(ignoreRecord: true)
                        ->placeholder('mis. POL-2026-00001'),
                    Forms\Components\Select::make('produk_id')->label('Produk')
                        ->options(AsuransiProduk::where('aktif', true)->pluck('nama', 'id'))
                        ->searchable()->required()->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if ($p = AsuransiProduk::find($state)) {
                                $nilai = (int) ($get('nilai_pertanggungan') ?? 0);
                                $set('premi', (int) round($nilai * $p->rate_premi / 100));
                            }
                        }),
                    Forms\Components\Select::make('anggota_id')->label('Anggota')
                        ->options(fn () => Anggota::where('status', 'aktif')->limit(50)->pluck('nama', 'id'))
                        ->getSearchResultsUsing(fn (string $s) => Anggota::where('nama', 'like', "%{$s}%")->limit(30)->pluck('nama', 'id'))
                        ->searchable()->required(),
                    Forms\Components\Select::make('pinjaman_id')->label('Pinjaman terkait (opsional)')
                        ->options(fn () => Pinjaman::with('anggota')->latest()->limit(50)->get()->mapWithKeys(fn ($p) => [$p->id => $p->nomor_akad . ' — ' . ($p->anggota->nama ?? '')]))
                        ->searchable()->placeholder('Tidak terkait pinjaman'),
                ]),
            ]),
            Forms\Components\Section::make('Nilai & Periode')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nilai_pertanggungan')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                        ->afterStateUpdated(function (Get $g, Set $s) {
                            if ($p = AsuransiProduk::find($g('produk_id'))) {
                                $s('premi', (int) round(((int) $g('nilai_pertanggungan')) * $p->rate_premi / 100));
                            }
                        }),
                    Forms\Components\TextInput::make('premi')->prefix('Rp')->numeric()->required()
                        ->helperText('Auto-hitung dari nilai × rate produk'),
                    Forms\Components\Select::make('status')->options([
                        'aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa', 'klaim' => 'Sudah Diklaim', 'batal' => 'Batal',
                    ])->default('aktif'),
                    Forms\Components\DatePicker::make('tanggal_mulai')->required()->default(now()),
                    Forms\Components\DatePicker::make('tanggal_akhir')->required()->default(now()->addYear()),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_mulai', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_polis')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->badge()->color('info'),
                Tables\Columns\TextColumn::make('anggota.nama')->searchable()->limit(25),
                Tables\Columns\TextColumn::make('nilai_pertanggungan')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('premi')->money('IDR')->color('warning'),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('d M Y'),
                Tables\Columns\TextColumn::make('tanggal_akhir')->date('d M Y')
                    ->color(fn ($record) => $record->tanggal_akhir < now() ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('klaim_count')->label('Klaim')->counts('klaim')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'aktif' => 'success', 'kadaluarsa' => 'gray', 'klaim' => 'warning', 'batal' => 'danger', default => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('produk_id')->relationship('produk', 'nama')->label('Produk'),
                Tables\Filters\SelectFilter::make('status')->options(['aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa', 'klaim' => 'Klaim', 'batal' => 'Batal']),
                Tables\Filters\Filter::make('expired')->label('Kadaluarsa')->query(fn ($q) => $q->where('tanggal_akhir', '<', now())),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsuransiPolises::route('/'),
            'create' => Pages\CreateAsuransiPolis::route('/create'),
            'edit'   => Pages\EditAsuransiPolis::route('/{record}/edit'),
        ];
    }
}
