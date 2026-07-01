<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumumanResource\Pages;
use App\Models\Pengumuman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Blog & Marketing';
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?string $modelLabel = 'Pengumuman';
    protected static ?string $pluralModelLabel = 'Pengumuman';
    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->tabs([
                Forms\Components\Tabs\Tab::make('Konten')->schema([
                    Forms\Components\TextInput::make('judul')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\Select::make('kategori')->label('Kategori')->options([
                        'umum' => 'Umum', 'urgent' => 'Urgent', 'event' => 'Event', 'produk' => 'Produk',
                    ])->default('umum')->required(),
                    Forms\Components\RichEditor::make('isi')->label('Isi')->required()->columnSpanFull(),
                    Forms\Components\Select::make('author_id')->label('Penulis')
                        ->relationship('author', 'name')->searchable()->preload()->required(),
                ]),
                Forms\Components\Tabs\Tab::make('Publikasi')->schema([
                    Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(false)->inline(false),
                    Forms\Components\Toggle::make('is_highlighted')->label('Highlight / Pin')->default(false),
                    Forms\Components\DateTimePicker::make('published_at')->label('Tanggal Publikasi')->default(now()),
                    Forms\Components\DateTimePicker::make('expires_at')->label('Kadaluarsa')->nullable(),
                    Forms\Components\Toggle::make('broadcast_wa')->label('Broadcast WhatsApp')->default(false),
                    Forms\Components\Toggle::make('broadcast_email')->label('Broadcast Email')->default(false),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->label('Judul')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('kategori')->label('Kategori')->badge()->color(fn ($s) => match ($s) {
                    'urgent' => 'danger', 'event' => 'info', 'produk' => 'success', default => 'gray',
                }),
                Tables\Columns\IconColumn::make('is_highlighted')->label('Pin')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->label('Publikasi')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('published_at')->label('Tgl')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori'),
                Tables\Filters\Filter::make('published')->label('Dipublikasi')->query(fn ($q) => $q->where('is_published', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('broadcast')->label('Broadcast WA')->icon('heroicon-o-arrow-right-circle')
                    ->action(fn (Pengumuman $r) => $r->update(['broadcast_wa' => true, 'is_published' => true, 'published_at' => now()]))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPengumuman::route('/'),
            'create' => Pages\CreatePengumuman::route('/create'),
            'edit'   => Pages\EditPengumuman::route('/{record}/edit'),
        ];
    }
}
