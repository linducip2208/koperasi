<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasOpnameResource\Pages;
use App\Models\Kas;
use App\Models\KasOpname;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class KasOpnameResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'kas';
    protected static ?string $model = KasOpname::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Kas Opname';
    protected static ?string $modelLabel = 'Kas Opname';
    protected static ?string $pluralModelLabel = 'Kas Opname';
    protected static ?int $navigationSort = 35;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('kas_id')->label('Kas')
                    ->options(Kas::where('tipe', 'kas')->where('aktif', true)->pluck('nama', 'id'))
                    ->searchable()->required()->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($k = Kas::find($state)) {
                            $set('saldo_sistem', (int) $k->saldo);
                        }
                    }),
                Forms\Components\DatePicker::make('tanggal')->required()->default(now()),
                Forms\Components\TextInput::make('saldo_sistem')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $g, Set $s) => $s('selisih', (int) $g('saldo_fisik') - (int) $g('saldo_sistem')))
                    ->helperText('Saldo menurut sistem koperasi'),
                Forms\Components\TextInput::make('saldo_fisik')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $g, Set $s) => $s('selisih', (int) $g('saldo_fisik') - (int) $g('saldo_sistem')))
                    ->helperText('Hasil hitung fisik kas'),
                Forms\Components\TextInput::make('selisih')->prefix('Rp')->numeric()->disabled()->dehydrated(),
            ]),
            Forms\Components\Textarea::make('catatan')->rows(2)->placeholder('Penjelasan jika ada selisih: kelebihan/kekurangan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('kas.nama')->badge()->color('info'),
                Tables\Columns\TextColumn::make('saldo_sistem')->money('IDR'),
                Tables\Columns\TextColumn::make('saldo_fisik')->money('IDR'),
                Tables\Columns\TextColumn::make('selisih')->money('IDR')
                    ->color(fn ($state) => $state == 0 ? 'success' : 'danger')->weight('bold'),
                Tables\Columns\TextColumn::make('catatan')->limit(40)->placeholder('—'),
            ])
            ->filters([Tables\Filters\SelectFilter::make('kas_id')->relationship('kas', 'nama')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKasOpnames::route('/'),
            'create' => Pages\CreateKasOpname::route('/create'),
            'edit'   => Pages\EditKasOpname::route('/{record}/edit'),
        ];
    }
}
