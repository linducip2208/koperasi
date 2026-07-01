<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'HR & Asset';
    protected static ?string $navigationLabel = 'Karyawan';
    protected static ?int $navigationSort = 51;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Data Karyawan')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->imageEditor()
                            ->directory('karyawan')
                            ->columnSpanFull()
                            ->helperText('Upload foto karyawan (format JPG/PNG, maks 2MB)'),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nip')
                                ->label('NIP')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->helperText('Nomor Induk Pegawai, harus unik'),
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->required()
                                ->helperText('Nama lengkap sesuai KTP'),
                            Forms\Components\TextInput::make('jabatan')
                                ->label('Jabatan')
                                ->helperText('Contoh: Kasir, Manager, Staff Admin'),
                            Forms\Components\TextInput::make('departemen')
                                ->label('Departemen')
                                ->helperText('Contoh: Keuangan, Operasional, HR'),
                        ]),
                    ]),
                Forms\Components\Tabs\Tab::make('Masa Kerja')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('tanggal_masuk')
                                ->label('Tanggal Masuk')
                                ->helperText('Tanggal pertama kali bergabung'),
                            Forms\Components\DatePicker::make('tanggal_keluar')
                                ->label('Tanggal Keluar')
                                ->helperText('Kosongkan jika masih aktif bekerja'),
                            Forms\Components\Select::make('status')
                                ->label('Status Kepegawaian')
                                ->options([
                                    'aktif'   => 'Aktif',
                                    'cuti'    => 'Cuti',
                                    'resign'  => 'Resign',
                                    'pensiun' => 'Pensiun',
                                ])
                                ->default('aktif')
                                ->required()
                                ->helperText('Status kepegawaian saat ini'),
                        ]),
                    ]),
                Forms\Components\Tabs\Tab::make('Penggajian')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('gaji_pokok')
                            ->label('Gaji Pokok')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Gaji pokok per bulan'),
                        Forms\Components\KeyValue::make('tunjangan')
                            ->label('Tunjangan')
                            ->keyLabel('Nama Tunjangan')
                            ->valueLabel('Nominal')
                            ->helperText('Isi nama tunjangan dan nominalnya'),
                    ]),
                Forms\Components\Tabs\Tab::make('Identitas & Bank')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('npwp')
                                ->label('NPWP')
                                ->helperText('Nomor Pokok Wajib Pajak'),
                            Forms\Components\TextInput::make('no_bpjs_kesehatan')
                                ->label('No. BPJS Kesehatan')
                                ->helperText('Nomor peserta BPJS Kesehatan'),
                            Forms\Components\TextInput::make('no_bpjs_naker')
                                ->label('No. BPJS Ketenagakerjaan')
                                ->helperText('Nomor peserta BPJS Ketenagakerjaan'),
                            Forms\Components\TextInput::make('rekening_bank')
                                ->label('Bank')
                                ->helperText('Nama bank untuk penggajian'),
                            Forms\Components\TextInput::make('nomor_rekening')
                                ->label('No. Rekening')
                                ->helperText('Nomor rekening untuk transfer gaji'),
                        ]),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('jabatan'),
                Tables\Columns\TextColumn::make('departemen'),
                Tables\Columns\TextColumn::make('gaji_pokok')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'aktif' => 'success', 'cuti' => 'warning', 'resign' => 'danger', 'pensiun' => 'gray',
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'aktif' => 'Aktif', 'cuti' => 'Cuti', 'resign' => 'Resign', 'pensiun' => 'Pensiun',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit'   => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
