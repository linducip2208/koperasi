<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShuPerhitunganResource\Pages;
use App\Models\ShuPerhitungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShuPerhitunganResource extends Resource
{
    protected static ?string $model = ShuPerhitungan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationGroup = 'SHU & RAT';
    protected static ?string $navigationLabel = 'Perhitungan SHU';
    protected static ?string $modelLabel = 'Perhitungan SHU';
    protected static ?string $pluralModelLabel = 'Perhitungan SHU';
    protected static ?int $navigationSort = 81;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Periode & Total SHU')
                ->description('Hitung Sisa Hasil Usaha tahunan dari Laba Bersih (setelah pajak).')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('tahun')->label('Tahun Buku')
                            ->numeric()->required()->minValue(2000)->maxValue(2100)
                            ->default(now()->year - 1),
                        Forms\Components\TextInput::make('shu_total')->label('Total SHU (Laba Bersih)')
                            ->prefix('Rp')->numeric()->required()->minValue(0)->live(onBlur: true)
                            ->helperText('Diperoleh dari Laba-Rugi setelah pajak'),
                        Forms\Components\Select::make('status')->options([
                            'draft'        => 'Draft',
                            'disetujui'    => 'Disetujui (RAT)',
                            'distribusi'   => 'Selesai Distribusi',
                        ])->default('draft')->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Persentase Distribusi (Total harus 100%)')
                ->description('Diatur di RAT — bisa berbeda tiap koperasi.')
                ->schema([
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\TextInput::make('persen_jasa_modal')->label('Jasa Modal')
                            ->suffix('%')->numeric()->step(0.01)->default(20)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_jasa_anggota')->label('Jasa Anggota')
                            ->suffix('%')->numeric()->step(0.01)->default(25)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_dana_cadangan')->label('Cadangan')
                            ->suffix('%')->numeric()->step(0.01)->default(25)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_dana_pendidikan')->label('Pendidikan')
                            ->suffix('%')->numeric()->step(0.01)->default(10)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_dana_sosial')->label('Sosial')
                            ->suffix('%')->numeric()->step(0.01)->default(5)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_dana_pengurus')->label('Pengurus')
                            ->suffix('%')->numeric()->step(0.01)->default(10)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                        Forms\Components\TextInput::make('persen_dana_karyawan')->label('Karyawan')
                            ->suffix('%')->numeric()->step(0.01)->default(5)->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    ]),
                ]),

            Forms\Components\Section::make('Hasil Perhitungan (Auto)')
                ->description('Auto-update saat persentase atau total SHU diubah.')
                ->schema([
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\TextInput::make('jumlah_jasa_modal')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_jasa_anggota')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_dana_cadangan')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_dana_pendidikan')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_dana_sosial')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_dana_pengurus')->prefix('Rp')->disabled()->dehydrated(),
                        Forms\Components\TextInput::make('jumlah_dana_karyawan')->prefix('Rp')->disabled()->dehydrated(),
                    ]),
                ]),

            Forms\Components\Section::make('Approval & Distribusi')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\DateTimePicker::make('approved_at')->label('Disetujui RAT')->seconds(false),
                    Forms\Components\DateTimePicker::make('distributed_at')->label('Tanggal Distribusi')->seconds(false),
                ]),
                Forms\Components\Textarea::make('meta.catatan')->label('Catatan')->rows(2),
            ]),
        ]);
    }

    private static function recalc(Get $get, Set $set): void
    {
        $shu = (int) ($get('shu_total') ?? 0);
        foreach (['jasa_modal', 'jasa_anggota', 'dana_cadangan', 'dana_pendidikan', 'dana_sosial', 'dana_pengurus', 'dana_karyawan'] as $k) {
            $persen = (float) ($get("persen_{$k}") ?? 0);
            $set("jumlah_{$k}", (int) round($shu * $persen / 100));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tahun', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tahun')->sortable()->weight('bold')->size('lg'),
                Tables\Columns\TextColumn::make('shu_total')->label('Total SHU')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('jumlah_jasa_anggota')->label('Jasa Anggota')->money('IDR')->color('success'),
                Tables\Columns\TextColumn::make('jumlah_jasa_modal')->label('Jasa Modal')->money('IDR')->color('info'),
                Tables\Columns\TextColumn::make('jumlah_dana_cadangan')->label('Cadangan')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'draft' => 'gray', 'disetujui' => 'warning', 'distribusi' => 'success', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('distribusi_count')->label('Anggota')
                    ->counts('distribusi')->badge()->color('info'),
                Tables\Columns\TextColumn::make('approved_at')->date('d M Y')->placeholder('—')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'disetujui' => 'Disetujui', 'distribusi' => 'Distribusi']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListShuPerhitungans::route('/'),
            'create' => Pages\CreateShuPerhitungan::route('/create'),
            'edit'   => Pages\EditShuPerhitungan::route('/{record}/edit'),
        ];
    }
}
