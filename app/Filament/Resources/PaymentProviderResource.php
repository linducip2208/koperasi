<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentProviderResource\Pages;
use App\Models\PaymentProvider;
use App\Services\Payment\PaymentManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentProviderResource extends Resource
{
    protected static ?string $model = PaymentProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Payment Gateway';
    protected static ?string $modelLabel = 'Payment Provider';
    protected static ?int $navigationSort = 26;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Provider')
                ->description('Isi nama provider sesuai vendor yang Anda pakai. Tidak ada vendor pre-defined — input bebas.')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nama')->label('Nama Provider')->required()
                            ->placeholder('mis. Midtrans Production, Xendit Test, BCA QRIS'),
                        Forms\Components\Select::make('api_format')->label('Format API')
                            ->options(PaymentManager::availableFormats())
                            ->required()->live()
                            ->helperText('Pilih format API yang sesuai vendor — ada 3 format generic.'),
                    ]),
                ]),
            Forms\Components\Section::make('Endpoint & Kredensial')
                ->description('Semua URL & API key di-input sendiri. Kami tidak menyimpan list URL vendor — biar selalu up-to-date.')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('base_url')->label('Base URL')->required()
                            ->url()
                            ->placeholder('https://app.midtrans.com/snap/v1 atau https://api.xendit.co'),
                        Forms\Components\TextInput::make('session_endpoint')->label('Session Endpoint (opsional)')
                            ->placeholder('transactions / qris/generate / callback_virtual_accounts'),
                        Forms\Components\TextInput::make('merchant_id')->label('Merchant ID (kalau ada)')
                            ->visible(fn ($get) => $get('api_format') === 'qris'),
                        Forms\Components\TextInput::make('api_key')->label('API Key / Secret')
                            ->password()->revealable()->required()
                            ->helperText('Tersimpan terenkripsi. Tidak pernah ditampilkan di log atau response API.'),
                    ]),
                    Forms\Components\KeyValue::make('extra_headers')->label('Extra Headers (opsional)')
                        ->keyLabel('Header')->valueLabel('Value')
                        ->addable()->reorderable(false),
                ]),
            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make('is_sandbox')->label('Mode Sandbox')->default(true),
                    Forms\Components\Toggle::make('aktif')->label('Aktif')->default(true),
                ]),
                Forms\Components\Textarea::make('catatan')->label('Catatan internal')->rows(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('api_format')->label('Format')->badge()
                    ->color(fn ($state) => match ($state) { 'redirect' => 'success', 'qris' => 'info', 'virtual_account' => 'warning', default => 'gray' }),
                Tables\Columns\TextColumn::make('base_url')->limit(40)->copyable(),
                Tables\Columns\IconColumn::make('is_sandbox')->label('Sandbox')->boolean(),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->since()->size('sm'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('api_format')->options(PaymentManager::availableFormats()),
                Tables\Filters\TernaryFilter::make('aktif'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPaymentProviders::route('/'),
            'create' => Pages\CreatePaymentProvider::route('/create'),
            'edit'   => Pages\EditPaymentProvider::route('/{record}/edit'),
        ];
    }
}
