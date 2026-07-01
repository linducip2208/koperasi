<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Models\Coa;
use App\Models\Jurnal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Akuntansi';
    protected static ?string $navigationLabel = 'Jurnal';
    protected static ?string $modelLabel = 'Jurnal';
    protected static ?string $pluralModelLabel = 'Jurnal';
    protected static ?int $navigationSort = 61;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Header Jurnal')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('nomor')->label('Nomor')->required()->unique(ignoreRecord: true),
                    Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->default(now())->required(),
                    Forms\Components\Select::make('tipe')->label('Tipe Jurnal')->options([
                        'umum'        => 'Jurnal Umum',
                        'otomatis'    => 'Otomatis',
                        'penyesuaian' => 'Penyesuaian',
                        'penutup'     => 'Penutup',
                        'balik'       => 'Balik',
                    ])->default('umum')->required(),
                    Forms\Components\Textarea::make('keterangan')->label('Keterangan')->required()->columnSpanFull(),
                ]),
            ]),
            Forms\Components\Section::make('Detail (Debit-Kredit)')->schema([
                Forms\Components\Repeater::make('details')->relationship()
                    ->schema([
                        Forms\Components\Select::make('coa_id')->label('Akun')
                            ->options(Coa::where('is_postable', true)->where('is_aktif', true)
                                ->get()->mapWithKeys(fn ($c) => [$c->id => $c->kode . ' — ' . $c->nama]))
                            ->searchable()->required(),
                        Forms\Components\TextInput::make('debit')->label('Debit')->numeric()->prefix('Rp')->default(0),
                        Forms\Components\TextInput::make('kredit')->label('Kredit')->numeric()->prefix('Rp')->default(0),
                        Forms\Components\TextInput::make('keterangan')->label('Keterangan'),
                    ])
                    ->columns(4)
                    ->defaultItems(2)
                    ->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Toggle::make('is_posted')->label('Posted')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('tipe')->badge(),
                Tables\Columns\TextColumn::make('keterangan')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('total_debit')->label('Debit')->money('IDR'),
                Tables\Columns\TextColumn::make('total_kredit')->label('Kredit')->money('IDR'),
                Tables\Columns\IconColumn::make('is_posted')->label('Posted')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')->options([
                    'umum' => 'Umum', 'otomatis' => 'Otomatis', 'penyesuaian' => 'Penyesuaian', 'penutup' => 'Penutup',
                ]),
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari'),
                        Forms\Components\DatePicker::make('sampai'),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when($data['dari'], fn ($q, $v) => $q->whereDate('tanggal', '>=', $v))
                        ->when($data['sampai'], fn ($q, $v) => $q->whereDate('tanggal', '<=', $v))),
            ])
            ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit'   => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}
