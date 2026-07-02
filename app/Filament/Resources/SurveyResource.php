<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = '📝 Blog & Marketing';
    protected static ?string $navigationLabel = 'Survey';
    protected static ?string $modelLabel = 'Survey';
    protected static ?string $pluralModelLabel = 'Survey';
    protected static ?int $navigationSort = 83;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('judul')->label('Judul Survey')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\Textarea::make('deskripsi')->label('Deskripsi')->columnSpanFull(),
                Forms\Components\Select::make('tenant_id')->label('Tenant')
                    ->relationship('tenant', 'nama')->required(),
                Forms\Components\DateTimePicker::make('mulai')->label('Mulai'),
                Forms\Components\DateTimePicker::make('selesai')->label('Selesai'),
                Forms\Components\Toggle::make('is_aktif')->label('Aktif')->default(true),
                Forms\Components\KeyValue::make('pertanyaan')->label('Pertanyaan')
                    ->keyLabel('Kode')->valueLabel('Pertanyaan')
                    ->addActionLabel('Tambah Pertanyaan')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('mulai')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('selesai')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\IconColumn::make('is_aktif')->boolean()->label('Aktif'),
                Tables\Columns\TextColumn::make('jawaban_count')->counts('jawaban')->label('Jawaban'),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_aktif')->label('Aktif')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\JawabanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit'   => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
