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
    protected static ?string $navigationGroup = 'Pengaturan';
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
                        Forms\Components\TextInput::make('nama')->label('Nama Koperasi')->required(),
                        Forms\Components\TextInput::make('badan_hukum')->label('No. Badan Hukum'),
                        Forms\Components\TextInput::make('nik_koperasi')->label('NIK Koperasi'),
                        Forms\Components\TextInput::make('npwp')->label('NPWP'),
                        Forms\Components\TextInput::make('akta_pendirian')->label('No. Akta Pendirian'),
                        Forms\Components\FileUpload::make('logo_path')->label('Logo')->image()->directory('koperasi'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Kontak')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Textarea::make('alamat')->label('Alamat')->rows(2)->columnSpanFull(),
                        Forms\Components\TextInput::make('telp')->label('Telepon'),
                        Forms\Components\TextInput::make('email')->label('Email')->email(),
                        Forms\Components\TextInput::make('website')->label('Website')->url(),
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
                        Forms\Components\TextInput::make('tahun_buku')->label('Tahun Buku Aktif')->numeric()->required(),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Subscription (SaaS)')->schema([
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
