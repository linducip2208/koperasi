<?php

namespace App\Filament\Resources\PinjamanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JaminanRelationManager extends RelationManager
{
    protected static string $relationship = 'jaminan';
    protected static ?string $title = 'Agunan & Penjamin';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('jenis')->label('Jenis Agunan')->options([
                    'bpkb' => 'BPKB', 'sertifikat' => 'Sertifikat Tanah', 'emas' => 'Emas',
                    'simpanan' => 'Simpanan', 'deposito' => 'Deposito', 'lainnya' => 'Lainnya',
                ])->required(),
                Forms\Components\TextInput::make('nama')->label('Nama Barang')->required(),
                Forms\Components\TextInput::make('nomor_dokumen')->label('No. Dokumen'),
                Forms\Components\TextInput::make('atas_nama')->label('Atas Nama'),
                Forms\Components\TextInput::make('nilai_taksiran')->label('Taksiran (Rp)')->numeric()->default(0),
                Forms\Components\TextInput::make('nilai_pasar')->label('Nilai Pasar (Rp)')->numeric()->default(0),
                Forms\Components\Select::make('status')->label('Status')->options([
                    'ditahan' => 'Ditahan', 'dikembalikan' => 'Dikembalikan', 'dieksekusi' => 'Dieksekusi',
                ])->default('ditahan'),
            ]),
            Forms\Components\Section::make('Penjamin (Guarantor)')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('penjamin_nama')->label('Nama Penjamin'),
                    Forms\Components\TextInput::make('penjamin_nik')->label('NIK Penjamin')->maxLength(25),
                    Forms\Components\TextInput::make('penjamin_telp')->label('Telp Penjamin')->maxLength(30),
                    Forms\Components\TextInput::make('penjamin_hubungan')->label('Hubungan')->placeholder('keluarga/rekan kerja'),
                ]),
            ])->collapsible(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis')->badge(),
                Tables\Columns\TextColumn::make('nama')->limit(25),
                Tables\Columns\TextColumn::make('nilai_taksiran')->label('Taksiran')->money('IDR'),
                Tables\Columns\TextColumn::make('penjamin_nama')->label('Penjamin')->placeholder('-'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}
