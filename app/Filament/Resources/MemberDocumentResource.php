<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberDocumentResource\Pages;
use App\Models\Anggota;
use App\Models\MemberDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class MemberDocumentResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'anggota';

    protected static ?string $model = MemberDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'OPERASIONAL';
    protected static ?string $navigationLabel = 'Dokumen Anggota';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('anggota_id')->label('Anggota')
                    ->options(Anggota::orderBy('nama')->limit(1000)->pluck('nama', 'id'))->searchable()->required(),
                Forms\Components\Select::make('jenis')->label('Jenis')->options([
                    'ktp' => 'KTP', 'kk' => 'KK', 'npwp' => 'NPWP', 'kontrak' => 'Kontrak',
                    'agunan' => 'Agunan', 'surat' => 'Surat', 'kuitansi' => 'Kuitansi', 'lainnya' => 'Lainnya',
                ])->required(),
                Forms\Components\TextInput::make('nama')->label('Nama Dokumen')->required()->maxLength(255),
                Forms\Components\FileUpload::make('file_path')->label('File')
                    ->directory(fn ($get) => 'dokumen/' . date('Y/m'))
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                    ->maxSize(5120)->required()->columnSpanFull()
                    ->getUploadedFileNameForStorageUsing(fn ($file) => \Illuminate\Support\Str::random(32) . '.' . $file->getClientOriginalExtension()),
                Forms\Components\DatePicker::make('tanggal_berlaku')->label('Berlaku'),
                Forms\Components\DatePicker::make('tanggal_kedaluwarsa')->label('Kedaluwarsa'),
                Forms\Components\Select::make('status')->label('Status')->options([
                    'aktif' => 'Aktif', 'kedaluwarsa' => 'Kedaluwarsa', 'arsip' => 'Arsip',
                ])->default('aktif'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('jenis')->badge(),
                Tables\Columns\TextColumn::make('nama')->limit(30),
                Tables\Columns\TextColumn::make('versi')->label('v'),
                Tables\Columns\TextColumn::make('tanggal_kedaluwarsa')->label('Exp')->date('d M y')
                    ->color(fn ($r) => $r->isExpired() ? 'danger' : null)->placeholder('-'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis'),
                Tables\Filters\Filter::make('kedaluwarsa')
                    ->label('Kedaluwarsa')->query(fn ($q) => $q->whereDate('tanggal_kedaluwarsa', '<', now())),
            ])
            ->actions([
                Tables\Actions\Action::make('unduh')->label('Unduh')->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($r) => route('dokumen.anggota', $r->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('versi_baru')->label('Versi Baru')->icon('heroicon-o-plus')
                    ->form([Forms\Components\FileUpload::make('file')->label('File pengganti')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])->maxSize(5120)->required()->storeFiles(false)])
                    ->action(function ($record, array $data) {
                        $path = $data['file']->store('dokumen/' . date('Y/m'), 'local');
                        $record->update([
                            'file_path' => $path, 'versi' => $record->versi + 1,
                            'mime' => $data['file']->getMimeType(), 'ukuran' => $data['file']->getSize(),
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberDocuments::route('/'),
            'create' => Pages\CreateMemberDocument::route('/create'),
            'edit' => Pages\EditMemberDocument::route('/{record}/edit'),
        ];
    }
}
