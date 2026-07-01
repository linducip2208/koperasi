<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotifikasiTemplateResource\Pages;
use App\Models\NotifikasiTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotifikasiTemplateResource extends Resource
{
    protected static ?string $model = NotifikasiTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Template Notifikasi';
    protected static ?string $modelLabel = 'Template';
    protected static ?string $pluralModelLabel = 'Template Notifikasi';
    protected static ?int $navigationSort = 92;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('kode')->required()->maxLength(50)->placeholder('mis. setoran_berhasil'),
                    Forms\Components\TextInput::make('nama')->required()->maxLength(255)->placeholder('Setoran Berhasil'),
                    Forms\Components\Select::make('event')->required()->options([
                        'setoran_simpanan'   => 'Setoran Simpanan',
                        'penarikan_simpanan' => 'Penarikan Simpanan',
                        'pencairan_pinjaman' => 'Pencairan Pinjaman',
                        'cicilan_diterima'   => 'Cicilan Diterima',
                        'cicilan_jatuh_tempo'=> 'Reminder Cicilan Jatuh Tempo',
                        'undangan_rat'       => 'Undangan RAT',
                        'pengumuman'         => 'Pengumuman Umum',
                        'shu_distribusi'     => 'Pembagian SHU',
                        'ulang_tahun'        => 'Ucapan Ulang Tahun',
                    ]),
                    Forms\Components\Select::make('channel')->required()->options([
                        'whatsapp' => 'WhatsApp', 'email' => 'Email', 'sms' => 'SMS', 'push' => 'Push Notification',
                    ]),
                    Forms\Components\Toggle::make('aktif')->default(true),
                ]),
            ]),
            Forms\Components\Section::make('Konten')
                ->description('Variable: {nama}, {nominal}, {tanggal}, {nomor}, {saldo} — diganti otomatis saat dikirim')
                ->schema([
                    Forms\Components\TextInput::make('subject')->maxLength(255)->placeholder('Untuk email')
                        ->visible(fn (callable $get) => $get('channel') === 'email'),
                    Forms\Components\Textarea::make('body')->required()->rows(8)
                        ->placeholder("Halo {nama},\n\nSetoran sebesar Rp {nominal} telah kami terima.\nSaldo Anda kini: Rp {saldo}\n\nTerima kasih."),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->copyable()->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('event')->badge()->color('info'),
                Tables\Columns\TextColumn::make('channel')->badge()->color(fn ($state) => match ($state) {
                    'whatsapp' => 'success', 'email' => 'info', 'sms' => 'warning', 'push' => 'gray', default => 'gray',
                }),
                Tables\Columns\IconColumn::make('aktif')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event'),
                Tables\Filters\SelectFilter::make('channel')->options(['whatsapp' => 'WhatsApp', 'email' => 'Email', 'sms' => 'SMS', 'push' => 'Push']),
                Tables\Filters\TernaryFilter::make('aktif'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNotifikasiTemplates::route('/'),
            'create' => Pages\CreateNotifikasiTemplate::route('/create'),
            'edit'   => Pages\EditNotifikasiTemplate::route('/{record}/edit'),
        ];
    }
}
