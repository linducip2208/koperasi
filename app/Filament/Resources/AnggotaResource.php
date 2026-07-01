<?php

namespace App\Filament\Resources;

use App\Domain\Numbering\NumberingService;
use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Keanggotaan';
    protected static ?string $navigationLabel = 'Anggota';
    protected static ?string $modelLabel = 'Anggota';
    protected static ?string $pluralModelLabel = 'Anggota';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Data Pribadi')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nomor_anggota')
                            ->label('Nomor Anggota')
                            ->default(fn () => NumberingService::next('anggota', 'AGT-', '{prefix}{ymd}{seq:5}'))
                            ->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('nik')->label('NIK')->maxLength(20),
                        Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required()->columnSpanFull(),
                        Forms\Components\TextInput::make('tempat_lahir')->label('Tempat Lahir'),
                        Forms\Components\DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('telp')->label('Telepon/HP'),
                        Forms\Components\TextInput::make('email')->label('Email')->email(),
                        Forms\Components\TextInput::make('npwp')->label('NPWP'),
                    ]),
                    Forms\Components\Section::make('Identitas')->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Radio::make('jenis_kelamin')->label('Jenis Kelamin')
                                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                ->inline()->inlineLabel(false),
                            Forms\Components\Radio::make('agama')->label('Agama')
                                ->options(['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu'])
                                ->inline()->inlineLabel(false),
                            Forms\Components\Radio::make('status_perkawinan')->label('Status')
                                ->options(['belum' => 'Belum Menikah', 'menikah' => 'Menikah', 'cerai' => 'Cerai'])
                                ->inline()->inlineLabel(false),
                        ]),
                    ])->collapsible(),
                ]),
                Forms\Components\Tabs\Tab::make('Alamat')->schema([
                    Forms\Components\Textarea::make('alamat')->label('Alamat Lengkap')->rows(2)->columnSpanFull(),
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\TextInput::make('rt')->label('RT'),
                        Forms\Components\TextInput::make('rw')->label('RW'),
                        Forms\Components\TextInput::make('kelurahan')->label('Kelurahan'),
                        Forms\Components\TextInput::make('kecamatan')->label('Kecamatan'),
                        Forms\Components\TextInput::make('kota')->label('Kota/Kabupaten'),
                        Forms\Components\TextInput::make('provinsi')->label('Provinsi'),
                        Forms\Components\TextInput::make('kode_pos')->label('Kode Pos'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Pekerjaan & Keuangan')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('pekerjaan')->label('Pekerjaan'),
                        Forms\Components\TextInput::make('nama_perusahaan')->label('Nama Perusahaan'),
                        Forms\Components\TextInput::make('penghasilan_bulanan')->label('Penghasilan Bulanan')->numeric()->prefix('Rp'),
                        Forms\Components\TextInput::make('sumber_dana')->label('Sumber Dana'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Status Keanggotaan')->schema([
                    Forms\Components\Radio::make('kategori')->label('Kategori')
                        ->options(['biasa' => 'Biasa', 'luar_biasa' => 'Luar Biasa', 'calon' => 'Calon', 'kehormatan' => 'Kehormatan'])
                        ->default('biasa')->required()->inline()->inlineLabel(false),
                    Forms\Components\Radio::make('status')->label('Status')
                        ->options(['aktif' => 'Aktif', 'tidak_aktif' => 'Non-Aktif', 'keluar' => 'Keluar', 'meninggal' => 'Meninggal', 'dikeluarkan' => 'Dikeluarkan'])
                        ->default('aktif')->required()->inline()->inlineLabel(false),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('tanggal_masuk')->label('Tanggal Masuk')->default(now()),
                        Forms\Components\DatePicker::make('tanggal_keluar')->label('Tanggal Keluar'),
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Login')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Hubungkan anggota ke akun User agar bisa login portal.'),
                        Forms\Components\Textarea::make('alasan_keluar')->label('Alasan Keluar')->columnSpanFull(),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Dokumen')->schema([
                    Forms\Components\FileUpload::make('foto_path')->label('Foto Profil')->image()->directory('anggota/foto'),
                    Forms\Components\FileUpload::make('ktp_path')->label('KTP')->directory('anggota/ktp'),
                    Forms\Components\FileUpload::make('kk_path')->label('Kartu Keluarga')->directory('anggota/kk'),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_path')->label('Foto')->circular(),
                Tables\Columns\TextColumn::make('nomor_anggota')->label('No.')->searchable()->sortable()->copyable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('telp')->label('Telp')->toggleable(),
                Tables\Columns\TextColumn::make('kategori')->badge()->color('info'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'aktif'       => 'success',
                    'tidak_aktif' => 'warning',
                    default       => 'danger',
                }),
                Tables\Columns\TextColumn::make('tanggal_masuk')->label('Masuk')->date('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'aktif' => 'Aktif', 'tidak_aktif' => 'Tidak Aktif', 'keluar' => 'Keluar',
                    'meninggal' => 'Meninggal', 'dikeluarkan' => 'Dikeluarkan',
                ]),
                Tables\Filters\SelectFilter::make('kategori')->options([
                    'biasa' => 'Biasa', 'luar_biasa' => 'Luar Biasa',
                    'calon' => 'Calon', 'kehormatan' => 'Kehormatan',
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('importCsv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                            ->required()
                            ->helperText('Format: nama,nik,email,telp,alamat,jenis_kelamin (semua optional kecuali nama). Header di baris pertama.'),
                    ])
                    ->action(function (array $data) {
                        $path = storage_path('app/public/' . $data['file']);
                        if (!file_exists($path)) {
                            \Filament\Notifications\Notification::make()->title('File tidak ditemukan')->danger()->send();
                            return;
                        }
                        $rows = array_map('str_getcsv', file($path));
                        $header = array_map('trim', array_shift($rows));
                        $created = 0;
                        $skipped = 0;
                        $tenantId = (int) (\App\Models\Tenant::query()->value('id') ?? 1);
                        $nextNomorBase = \App\Models\Anggota::max('id') + 1;
                        foreach ($rows as $idx => $row) {
                            $r = array_combine($header, array_pad($row, count($header), null));
                            if (empty($r['nama'])) { $skipped++; continue; }
                            $anggota = \App\Models\Anggota::create([
                                'tenant_id'     => $tenantId,
                                'nomor_anggota' => 'AGT-' . str_pad((string) ($nextNomorBase + $idx), 6, '0', STR_PAD_LEFT),
                                'nama'          => $r['nama'],
                                'nik'           => $r['nik'] ?? null,
                                'email'         => $r['email'] ?? null,
                                'telp'          => $r['telp'] ?? null,
                                'alamat'        => $r['alamat'] ?? null,
                                'jenis_kelamin' => $r['jenis_kelamin'] ?? 'L',
                                'status'        => 'aktif',
                                'tanggal_masuk' => now()->toDateString(),
                            ]);
                            if (!empty($r['email'])) {
                                $existingUser = \App\Models\User::where('email', $r['email'])->first();
                                if ($existingUser) {
                                    $anggota->updateQuietly(['user_id' => $existingUser->id]);
                                    if (!$existingUser->hasRole('anggota')) {
                                        $existingUser->assignRole('anggota');
                                    }
                                } else {
                                    $tempPassword = \Illuminate\Support\Str::random(8);
                                    $user = \App\Models\User::create([
                                        'tenant_id' => $tenantId,
                                        'name'      => $r['nama'],
                                        'email'     => $r['email'],
                                        'password'  => \Illuminate\Support\Facades\Hash::make($tempPassword),
                                        'aktif'     => true,
                                    ]);
                                    $user->assignRole('anggota');
                                    $anggota->updateQuietly(['user_id' => $user->id]);
                                }
                            }
                            $created++;
                        }
                        \Filament\Notifications\Notification::make()
                            ->title("Import selesai: {$created} dibuat, {$skipped} dilewati")
                            ->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('kartuQr')
                    ->label('Kartu QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->url(fn (\App\Models\Anggota $record) => route('anggota.kartu', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\AnggotaResource\RelationManagers\AhliWarisRelationManager::class,
            \App\Filament\Resources\AnggotaResource\RelationManagers\SimpananRelationManager::class,
            \App\Filament\Resources\AnggotaResource\RelationManagers\PinjamanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnggotas::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit'   => Pages\EditAnggota::route('/{record}/edit'),
            'view'   => Pages\ViewAnggota::route('/{record}'),
        ];
    }
}
