<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatResource\Pages;
use App\Models\Rat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class RatResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'rat';
    protected static ?string $model = Rat::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'SHU & RAT';
    protected static ?string $navigationLabel = 'Rapat Anggota Tahunan';
    protected static ?int $navigationSort = 71;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Acara RAT')
                ->description('Informasi pelaksanaan Rapat Anggota Tahunan')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('tahun_buku')
                            ->label('Tahun Buku')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Tahun buku yang dipertanggungjawabkan, mis. 2025'),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pelaksanaan')
                            ->required()
                            ->helperText('Tanggal RAT dilaksanakan'),
                        Forms\Components\TextInput::make('lokasi')
                            ->label('Lokasi')
                            ->columnSpanFull()
                            ->helperText('Tempat pelaksanaan RAT'),
                        Forms\Components\Select::make('status')
                            ->label('Status RAT')
                            ->options([
                                'rencana'     => 'Rencana',
                                'berlangsung' => 'Sedang Berlangsung',
                                'selesai'     => 'Selesai',
                                'batal'       => 'Dibatalkan',
                            ])
                            ->default('rencana')
                            ->required()
                            ->helperText('Status penyelenggaraan RAT'),
                    ]),
                ]),
            Forms\Components\Section::make('Agenda Rapat')
                ->description('Daftar agenda yang akan dibahas')
                ->schema([
                    Forms\Components\Repeater::make('agenda')
                        ->schema([
                            Forms\Components\TextInput::make('item')
                                ->label('Butir Agenda')
                                ->required()
                                ->helperText('Tulis satu butir agenda per baris'),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull()
                        ->addActionLabel('Tambah Agenda'),
                ]),
            Forms\Components\Section::make('Quorum & Kehadiran')
                ->description('Data kehadiran dan pencapaian quorum')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('jumlah_anggota_terdaftar')
                            ->label('Anggota Terdaftar')
                            ->numeric()
                            ->default(0)
                            ->helperText('Total anggota koperasi yang terdaftar'),
                        Forms\Components\TextInput::make('jumlah_hadir')
                            ->label('Jumlah Hadir')
                            ->numeric()
                            ->default(0)
                            ->helperText('Jumlah anggota yang hadir di RAT'),
                        Forms\Components\TextInput::make('quorum_persen')
                            ->label('Quorum Minimal (%)')
                            ->numeric()
                            ->default(50)
                            ->helperText('Persentase minimal kehadiran, sesuai AD/ART'),
                        Forms\Components\Toggle::make('quorum_tercapai')
                            ->label('Quorum Tercapai')
                            ->helperText('Centang jika jumlah hadir mencapai quorum'),
                    ]),
                ]),
            Forms\Components\Section::make('Notulen & Keputusan')
                ->description('Catatan rapat dan keputusan yang dihasilkan')
                ->schema([
                    Forms\Components\Textarea::make('notulen')
                        ->label('Notulen Rapat')
                        ->rows(6)
                        ->columnSpanFull()
                        ->helperText('Catatan lengkap jalannya rapat'),
                    Forms\Components\Repeater::make('keputusan')
                        ->schema([
                            Forms\Components\TextInput::make('butir')
                                ->label('Butir Keputusan')
                                ->required()
                                ->helperText('Tulis satu keputusan per baris'),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull()
                        ->addActionLabel('Tambah Keputusan'),
                ])
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun_buku')->label('Th. Buku')->sortable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('lokasi'),
                Tables\Columns\TextColumn::make('jumlah_hadir')->label('Hadir'),
                Tables\Columns\IconColumn::make('quorum_tercapai')->label('Quorum')->boolean(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'rencana' => 'gray', 'berlangsung' => 'info', 'selesai' => 'success', 'batal' => 'danger',
                }),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->defaultSort('tahun_buku', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRats::route('/'),
            'create' => Pages\CreateRat::route('/create'),
            'edit'   => Pages\EditRat::route('/{record}/edit'),
        ];
    }
}
