<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class TenantResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'tenant';
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'Profil Koperasi';
    protected static ?string $modelLabel = 'Koperasi';
    protected static ?string $pluralModelLabel = 'Koperasi';
    protected static ?int $navigationSort = 91;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Identitas')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nama')->label('Nama Koperasi')->required()->columnSpanFull(),
                        Forms\Components\TextInput::make('short_name')->label('Nama Pendek')->maxLength(50)
                            ->helperText('Untuk kop dokumen & tampilan ringkas.'),
                        Forms\Components\TextInput::make('slogan')->label('Slogan / Motto')->columnSpanFull(),
                        Forms\Components\TextInput::make('badan_hukum')->label('No. Badan Hukum'),
                        Forms\Components\TextInput::make('nik_koperasi')->label('NIK Koperasi'),
                        Forms\Components\TextInput::make('npwp')->label('NPWP'),
                        Forms\Components\TextInput::make('akta_pendirian')->label('No. Akta Pendirian'),
                        Forms\Components\DatePicker::make('tanggal_akta')->label('Tanggal Akta'),
                        Forms\Components\FileUpload::make('logo_path')->label('Logo')->image()->directory('koperasi'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Kontak & Wilayah')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Textarea::make('alamat')->label('Alamat Jalan')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('desa')->label('Desa/Kelurahan'),
                        Forms\Components\TextInput::make('kecamatan')->label('Kecamatan'),
                        Forms\Components\TextInput::make('kabupaten')->label('Kabupaten/Kota'),
                        Forms\Components\TextInput::make('provinsi')->label('Provinsi'),
                        Forms\Components\TextInput::make('kode_pos')->label('Kode Pos')->maxLength(10),
                        Forms\Components\TextInput::make('telp')->label('Telepon'),
                        Forms\Components\TextInput::make('whatsapp')->label('WhatsApp')->maxLength(30),
                        Forms\Components\TextInput::make('email')->label('Email')->email(),
                        Forms\Components\TextInput::make('website')->label('Website')->url(),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Pengurus')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nama_ketua')->label('Nama Ketua'),
                        Forms\Components\TextInput::make('nama_sekretaris')->label('Nama Sekretaris'),
                        Forms\Components\TextInput::make('nama_bendahara')->label('Nama Bendahara'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Branding')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('theme')->label('Tema')->options([
                            'emerald' => 'Emerald', 'teal' => 'Teal', 'blue' => 'Blue', 'amber' => 'Amber',
                        ])->default('emerald'),
                        Forms\Components\ColorPicker::make('primary_color')->label('Warna Primer'),
                        Forms\Components\ColorPicker::make('secondary_color')->label('Warna Sekunder'),
                        Forms\Components\Textarea::make('footer_text')->label('Teks Footer Dokumen')->rows(2)->columnSpanFull(),
                        Forms\Components\FileUpload::make('logo_dark_path')->label('Logo Mode Gelap')->image()->directory('koperasi'),
                        Forms\Components\FileUpload::make('favicon_path')->label('Favicon')->image()->directory('koperasi'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Penomoran')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('prefix_invoice')->label('Prefix Invoice')->maxLength(10),
                        Forms\Components\TextInput::make('prefix_member')->label('Prefix Anggota')->maxLength(10),
                        Forms\Components\TextInput::make('prefix_loan')->label('Prefix Pinjaman')->maxLength(10),
                        Forms\Components\TextInput::make('prefix_savings')->label('Prefix Simpanan')->maxLength(10),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Operasional')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('operation_mode')->label('Mode Operasi')->options([
                            'konvensional' => 'Konvensional',
                            'syariah'      => 'Syariah',
                            'dual'         => 'Dual (Konvensional + Syariah)',
                        ])->required(),
                        Forms\Components\TextInput::make('mata_uang')->label('Mata Uang')->default('IDR')->maxLength(3),
                        Forms\Components\Select::make('timezone')->label('Zona Waktu')->options([
                            'Asia/Jakarta' => 'WIB (Asia/Jakarta)',
                            'Asia/Makassar' => 'WITA (Asia/Makassar)',
                            'Asia/Jayapura' => 'WIT (Asia/Jayapura)',
                        ])->default('Asia/Jakarta'),
                        Forms\Components\TextInput::make('tahun_buku')->label('Tahun Buku Aktif')->numeric()->required(),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Lisensi & Plan')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('status')->label('Status')->options([
                            'aktif'      => 'Aktif',
                            'suspended'  => 'Suspended',
                            'terminated' => 'Terminated',
                        ])->required(),
                        Forms\Components\Select::make('plan')->label('Plan')->options([
                            'basic'      => 'Basic',
                            'pro'        => 'Pro',
                            'enterprise' => 'Enterprise',
                        ])->required(),
                        Forms\Components\DatePicker::make('subscription_until')->label('Berlaku Sampai'),
                    ]),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')->label('Logo')->circular(),
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('operation_mode')->label('Mode')->badge()
                    ->color(fn ($state) => match ($state) {
                        'konvensional' => 'gray', 'syariah' => 'success', 'dual' => 'info', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('plan')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'aktif' => 'success', 'suspended' => 'warning', 'terminated' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('subscription_until')->label('Sampai')->date('d M Y'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit'   => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
