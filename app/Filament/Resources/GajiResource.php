<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GajiResource\Pages;
use App\Models\Gaji;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GajiResource extends Resource
{
    protected static ?string $model = Gaji::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationGroup = 'HR & Asset';
    protected static ?string $navigationLabel = 'Payroll Karyawan';
    protected static ?string $modelLabel = 'Slip Gaji';
    protected static ?string $pluralModelLabel = 'Slip Gaji';
    protected static ?int $navigationSort = 53;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Karyawan & Periode')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('karyawan_id')->label('Karyawan')
                        ->options(fn () => Karyawan::where('status', 'aktif')->pluck('nama', 'id'))
                        ->searchable()->required()->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($k = Karyawan::find($state)) {
                                $set('gaji_pokok', (int) $k->gaji_pokok);
                                $tunj = collect((array) $k->tunjangan)->sum();
                                $set('total_tunjangan', (int) $tunj);
                            }
                        }),
                    Forms\Components\Select::make('tahun')->options(array_combine(range(now()->year - 5, now()->year + 1), range(now()->year - 5, now()->year + 1)))
                        ->default(now()->year)->required(),
                    Forms\Components\Select::make('bulan')->options([
                        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
                        7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
                    ])->default(now()->month)->required(),
                ]),
            ]),
            Forms\Components\Section::make('Komponen Gaji')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('gaji_pokok')->prefix('Rp')->numeric()->required()->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('total_tunjangan')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('lembur')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('bpjs_potongan')->label('BPJS Potongan')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('pph21')->label('PPh 21')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('total_potongan')->label('Potongan Lain')->prefix('Rp')->numeric()->default(0)->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $g, Set $s) => self::recalc($g, $s)),
                    Forms\Components\TextInput::make('total_bruto')->prefix('Rp')->numeric()->disabled()->dehydrated(),
                    Forms\Components\TextInput::make('total_netto')->prefix('Rp')->numeric()->disabled()->dehydrated(),
                ]),
            ]),
            Forms\Components\Section::make('Pembayaran')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('status')->options([
                        'draft'    => 'Draft',
                        'approved' => 'Disetujui',
                        'paid'     => 'Sudah Dibayar',
                    ])->default('draft')->required(),
                    Forms\Components\DatePicker::make('tanggal_bayar'),
                ]),
                Forms\Components\KeyValue::make('detail')->label('Rincian Tunjangan/Potongan')->keyLabel('Item')->valueLabel('Nominal'),
            ]),
        ]);
    }

    private static function recalc(Get $get, Set $set): void
    {
        $bruto = (int) $get('gaji_pokok') + (int) $get('total_tunjangan') + (int) $get('lembur');
        $potongan = (int) $get('bpjs_potongan') + (int) $get('pph21') + (int) $get('total_potongan');
        $set('total_bruto', $bruto);
        $set('total_netto', $bruto - $potongan);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('karyawan.jabatan')->placeholder('—'),
                Tables\Columns\TextColumn::make('periode')->label('Periode')
                    ->getStateUsing(fn ($record) => sprintf('%02d-%d', $record->bulan, $record->tahun)),
                Tables\Columns\TextColumn::make('gaji_pokok')->money('IDR')->toggleable(),
                Tables\Columns\TextColumn::make('total_bruto')->money('IDR')->color('info'),
                Tables\Columns\TextColumn::make('total_netto')->money('IDR')->color('success')->weight('bold')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'draft' => 'gray', 'approved' => 'warning', 'paid' => 'success', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('tanggal_bayar')->date('d M Y')->placeholder('—')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'approved' => 'Disetujui', 'paid' => 'Dibayar']),
                Tables\Filters\SelectFilter::make('tahun')->options(array_combine(range(2020, now()->year), range(2020, now()->year))),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGajis::route('/'),
            'create' => Pages\CreateGaji::route('/create'),
            'edit'   => Pages\EditGaji::route('/{record}/edit'),
        ];
    }
}
