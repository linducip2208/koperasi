<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Models\Anggota;
use App\Models\Tagihan;
use App\Filament\Concerns\HasRoleAccess;
use App\Models\TagihanMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TagihanResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'tagihan';

    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Iuran & Tagihan';
    protected static ?int $navigationSort = 16;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Tagihan')
                ->description('Informasi utama tagihan / iuran anggota')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('master_id')
                            ->label('Jenis Tagihan')
                            ->options(TagihanMaster::where('aktif', true)->pluck('nama', 'id'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih jenis iuran atau tagihan yang berlaku'),
                        Forms\Components\Select::make('anggota_id')
                            ->label('Anggota')
                            ->options(Anggota::where('status', 'aktif')->pluck('nama', 'id'))
                            ->searchable()
                            ->required()
                            ->helperText('Cari anggota berdasarkan nama'),
                        Forms\Components\TextInput::make('nomor')
                            ->label('No. Tagihan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Nomor unik tagihan, otomatis jika dikosongkan'),
                        Forms\Components\DatePicker::make('periode')
                            ->label('Periode')
                            ->required()
                            ->helperText('Tanggal periode tagihan (biasanya awal bulan)'),
                        Forms\Components\DatePicker::make('jatuh_tempo')
                            ->label('Jatuh Tempo')
                            ->required()
                            ->helperText('Batas akhir pembayaran tagihan'),
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->helperText('Jumlah yang harus dibayar anggota'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'belum_bayar' => 'Belum Bayar',
                                'sebagian'    => 'Dibayar Sebagian',
                                'lunas'       => 'Lunas',
                                'batal'       => 'Batal',
                            ])
                            ->default('belum_bayar')
                            ->required()
                            ->helperText('Status pembayaran tagihan saat ini'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->label('No. Tagihan')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Anggota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('master.nama')
                    ->label('Jenis Tagihan')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->date('M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->jatuh_tempo && $record->jatuh_tempo->isPast() && $record->status === 'belum_bayar' ? 'danger' : null),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('terbayar')
                    ->label('Terbayar')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sisa')
                    ->label('Sisa')
                    ->money('IDR')
                    ->state(fn ($record) => max(0, $record->nominal - $record->terbayar))
                    ->color(fn ($record) => ($record->nominal - $record->terbayar) > 0 ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lunas'        => 'success',
                        'sebagian'     => 'warning',
                        'belum_bayar'  => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'belum_bayar'  => 'Belum Bayar',
                        'sebagian'     => 'Dibayar Sebagian',
                        'lunas'        => 'Lunas',
                        'batal'        => 'Batal',
                        default        => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'sebagian'    => 'Dibayar Sebagian',
                        'lunas'       => 'Lunas',
                        'batal'       => 'Batal',
                    ]),
                Tables\Filters\SelectFilter::make('master_id')
                    ->label('Jenis Tagihan')
                    ->relationship('master', 'nama'),
                Tables\Filters\Filter::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->form([
                        Forms\Components\DatePicker::make('dari')->label('Dari'),
                        Forms\Components\DatePicker::make('sampai')->label('Sampai'),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when($data['dari'], fn ($q, $d) => $q->where('jatuh_tempo', '>=', $d))
                        ->when($data['sampai'], fn ($q, $d) => $q->where('jatuh_tempo', '<=', $d))
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('jatuh_tempo', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'edit'   => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
