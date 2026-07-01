<?php

namespace App\Filament\Resources\AnggotaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AhliWarisRelationManager extends RelationManager
{
    protected static string $relationship = 'ahliWaris';
    protected static ?string $title = 'Ahli Waris';
    protected static ?string $modelLabel = 'Ahli Waris';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('nama')->label('Nama')->required(),
                Forms\Components\Select::make('hubungan')->label('Hubungan')->options([
                    'istri' => 'Istri', 'suami' => 'Suami', 'anak' => 'Anak',
                    'orang_tua' => 'Orang Tua', 'saudara' => 'Saudara', 'lain' => 'Lainnya',
                ])->required(),
                Forms\Components\TextInput::make('nik')->label('NIK'),
                Forms\Components\DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                Forms\Components\TextInput::make('telp')->label('Telepon'),
                Forms\Components\TextInput::make('persentase')->label('Bagian (%)')->numeric()->default(100)->suffix('%'),
                Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('hubungan')->badge(),
                Tables\Columns\TextColumn::make('nik')->copyable(),
                Tables\Columns\TextColumn::make('telp'),
                Tables\Columns\TextColumn::make('persentase')->suffix('%'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}
