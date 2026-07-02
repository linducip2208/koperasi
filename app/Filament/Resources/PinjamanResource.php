<?php

namespace App\Filament\Resources;

use App\Domain\Calculation\CalculatorFactory;
use App\Domain\Pinjaman\PinjamanService;
use App\Filament\Resources\PinjamanResource\Pages;
use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Pinjaman;
use App\Models\ProdukPinjaman;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Simpan Pinjam';
    protected static ?string $navigationLabel = 'Pinjaman / Pembiayaan';
    protected static ?string $modelLabel = 'Pinjaman';
    protected static ?string $pluralModelLabel = 'Pinjaman';
    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pengajuan')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('anggota_id')->label('Anggota')
                        ->relationship('anggota', 'nama')
                        ->options(Anggota::where('status', 'aktif')->pluck('nama', 'id'))
                        ->searchable()->preload()->required(),
                    Forms\Components\Select::make('produk_id')->label('Produk Pinjaman')
                        ->options(ProdukPinjaman::where('aktif', true)->pluck('nama', 'id'))
                        ->required()->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $produk = ProdukPinjaman::find($state);
                            if ($produk) {
                                $set('bunga_persen', $produk->bunga_persen);
                                $set('margin_persen', $produk->margin_persen);
                            }
                        }),
                    Forms\Components\TextInput::make('plafon')->label('Plafon (Rp)')->numeric()->prefix('Rp')->required()->live(debounce: 500),
                    Forms\Components\TextInput::make('tenor')->label('Tenor (bulan)')->numeric()->required()->minValue(1)->maxValue(120),
                    Forms\Components\DatePicker::make('tanggal_pengajuan')->label('Tanggal Pengajuan')->default(now())->required(),
                    Forms\Components\Textarea::make('tujuan')->label('Tujuan Penggunaan')->columnSpanFull(),
                ]),
            ]),
            Forms\Components\Section::make('Analisa Kredit (5C)')->schema([
                Forms\Components\Textarea::make('analisa_kredit')->label('Analisa Kredit')->rows(4)->columnSpanFull(),
            ])->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_akad')->label('No. Akad')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Produk')->toggleable(),
                Tables\Columns\TextColumn::make('plafon')->label('Plafon')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('tenor')->label('Tenor')->suffix(' bln')->alignCenter(),
                Tables\Columns\TextColumn::make('saldo_pokok')->label('Sisa Pokok')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'aktif'    => 'success',
                    'lunas'    => 'gray',
                    'macet'    => 'danger',
                    'pengajuan', 'survey', 'analisa', 'approval', 'akad' => 'warning',
                    'ditolak', 'dibatalkan', 'hapus_buku', 'hapus_tagih' => 'danger',
                    default    => 'info',
                }),
                Tables\Columns\TextColumn::make('kolektabilitas')->label('Kol.')->badge()->color(fn (string $state) => match ($state) {
                    'lancar'        => 'success',
                    'dpk'           => 'warning',
                    'kurang_lancar' => 'warning',
                    'diragukan'     => 'danger',
                    'macet'         => 'danger',
                    default         => 'gray',
                }),
                Tables\Columns\TextColumn::make('approval_progress')->label('Approval')
                    ->state(function (Pinjaman $r) {
                        $total   = $r->approval()->count();
                        $setuju  = $r->approval()->where('keputusan', 'setuju')->count();
                        $tolak   = $r->approval()->where('keputusan', 'tolak')->count();
                        if ($tolak > 0) return '❌ Ditolak';
                        if ($total === 0) return '—';
                        return "{$setuju}/{$total} ✅";
                    })
                    ->badge()->color(fn ($state) => str_contains($state, '❌') ? 'danger' : (str_contains($state, '✅') ? 'success' : 'warning'))
                    ->visible(fn (Pinjaman $r) => in_array($r->status, ['pengajuan', 'approval', 'aktif', 'ditolak'])),
                Tables\Columns\TextColumn::make('tanggal_pengajuan')->label('Tgl Pengajuan')->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pengajuan' => 'Pengajuan', 'survey' => 'Survey', 'analisa' => 'Analisa',
                    'approval'  => 'Approval', 'akad' => 'Akad',
                    'aktif'     => 'Aktif', 'lunas' => 'Lunas', 'macet' => 'Macet',
                    'ditolak'   => 'Ditolak', 'dibatalkan' => 'Dibatalkan',
                ]),
                Tables\Filters\SelectFilter::make('kolektabilitas')->options([
                    'lancar' => 'Lancar', 'dpk' => 'DPK',
                    'kurang_lancar' => 'Kurang Lancar', 'diragukan' => 'Diragukan',
                    'macet' => 'Macet',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')->label('Setujui')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (Pinjaman $r) => in_array($r->status, ['pengajuan', 'survey', 'analisa', 'approval']))
                    ->requiresConfirmation()
                    ->action(function (Pinjaman $r) {
                        PinjamanService::approve($r, auth()->id());
                        Notification::make()->title('Pinjaman disetujui')->success()->send();
                    }),
                Tables\Actions\Action::make('cair')->label('Cairkan')
                    ->icon('heroicon-o-arrow-up-tray')->color('primary')
                    ->visible(fn (Pinjaman $r) => $r->status === 'aktif' && ! $r->tanggal_pencairan)
                    ->form([
                        Forms\Components\Select::make('kas_id')->label('Cairkan dari Kas')
                            ->options(Kas::where('aktif', true)->pluck('nama', 'id'))->required(),
                        Forms\Components\DatePicker::make('tanggal')->label('Tanggal Pencairan')->default(now())->required(),
                        Forms\Components\FileUpload::make('bukti_pencairan')->label('Bukti Pencairan')
                            ->directory('pinjaman/pencairan')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048)
                            ->required()
                            ->helperText('Upload bukti transfer / kuitansi tanda terima (PDF/JPG).'),
                    ])
                    ->action(function (Pinjaman $r, array $data) {
                        PinjamanService::cairkan($r, $data['kas_id'], Carbon::parse($data['tanggal']), $data['bukti_pencairan'] ?? null);
                        Notification::make()->title('Pencairan berhasil')->success()->send();
                    }),
                Tables\Actions\Action::make('bayar')->label('Bayar Angsuran')
                    ->icon('heroicon-o-banknotes')->color('warning')
                    ->visible(fn (Pinjaman $r) => $r->status === 'aktif' && $r->saldo_pokok > 0)
                    ->form([
                        Forms\Components\TextInput::make('jumlah')->label('Jumlah Bayar')->numeric()->prefix('Rp')->required(),
                        Forms\Components\Select::make('kas_id')->label('Kas Penerimaan')
                            ->options(Kas::where('aktif', true)->pluck('nama', 'id'))->required(),
                        Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->default(now())->required(),
                        Forms\Components\Select::make('metode')->label('Metode')
                            ->options(['cash' => 'Tunai', 'transfer' => 'Transfer', 'auto_debet' => 'Auto-debet Simpanan'])
                            ->default('cash')->required(),
                        Forms\Components\FileUpload::make('bukti_bayar')->label('Bukti Pembayaran')
                            ->image()->directory('bukti-bayar')->maxSize(2048)
                            ->helperText('Upload bukti transfer/slip. Wajib jika metode bukan tunai.')
                            ->required(fn (Forms\Get $get) => $get('metode') !== 'cash'),
                    ])
                    ->action(function (Pinjaman $r, array $data) {
                        $pembayaran = PinjamanService::bayar($r, (int) $data['jumlah'], (int) $data['kas_id'], Carbon::parse($data['tanggal']), $data['metode'], $data['bukti_bayar'] ?? null);
                        Notification::make()->title('Pembayaran tercatat, menunggu verifikasi.')->success()->send();
                    }),
                Tables\Actions\Action::make('tolak')->label('Tolak')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (Pinjaman $r) => in_array($r->status, ['pengajuan', 'survey', 'analisa', 'approval']))
                    ->form([
                        Forms\Components\Textarea::make('alasan')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (Pinjaman $r, array $data) {
                        PinjamanService::tolak($r, auth()->id(), $data['alasan']);
                        Notification::make()->title('Pinjaman ditolak')->warning()->send();
                    }),
                Tables\Actions\Action::make('cek_dti')->label('Cek Kemampuan Bayar')
                    ->icon('heroicon-o-calculator')->color('info')
                    ->visible(fn (Pinjaman $r) => in_array($r->status, ['pengajuan', 'survey', 'analisa', 'approval']))
                    ->modalHeading('Kalkulator Kemampuan Bayar (DTI)')
                    ->modalDescription(fn (Pinjaman $r) => $r->anggota?->nama ?? '-')
                    ->form(function (Pinjaman $r) {
                        $cicilanBaru = 0;
                        if ($r->total_bayar > 0 && $r->tenor > 0) {
                            $cicilanBaru = (int) round($r->total_bayar / $r->tenor);
                        }
                        return [
                            Forms\Components\Placeholder::make('info_anggota')
                                ->content(new HtmlString("
                                    <div class='text-sm space-y-1'>
                                        <div><b>Penghasilan:</b> Rp ".number_format($r->anggota?->penghasilan_bulanan ?? 0, 0, ',', '.')."</div>
                                        <div><b>Total hutang aktif:</b> Rp ".number_format($r->anggota?->totalHutang() ?? 0, 0, ',', '.')."</div>
                                        <div><b>Cicilan per bulan saat ini:</b> Rp ".number_format($r->anggota?->totalCicilanPerBulan() ?? 0, 0, ',', '.')."</div>
                                        <div><b>Rasio saat ini:</b> ".($r->anggota?->rasioHutang() ?? 0)."%</div>
                                        <div><b>Maks cicilan (40%):</b> Rp ".number_format($r->anggota?->maxCicilanBulanan() ?? 0, 0, ',', '.')."</div>
                                        <hr>
                                        <div><b>Cicilan pinjaman ini (estimasi):</b> Rp ".number_format($cicilanBaru, 0, ',', '.')."</div>
                                        <div><b>Sisa kemampuan:</b> Rp ".number_format($r->anggota?->sisaKemampuanCicilan() ?? 0, 0, ',', '.')."</div>
                                    </div>
                                ")),
                        ];
                    })
                    ->modalSubmitAction(false),
                Tables\Actions\Action::make('kontrak')->label('Kontrak PDF')
                    ->icon('heroicon-o-document-text')->color('info')
                    ->url(fn (Pinjaman $r) => route('dokumen.kontrak', $r->id), shouldOpenInNewTab: true),
                Tables\Actions\Action::make('restrukturisasi')->label('Restrukturisasi')
                    ->icon('heroicon-o-arrow-path-rounded-square')->color('purple')
                    ->visible(fn (Pinjaman $r) => in_array($r->status, ['aktif', 'macet']))
                    ->form([
                        Forms\Components\Select::make('jenis')->label('Jenis Restrukturisasi')
                            ->options([
                                'perpanjangan'   => 'Perpanjangan Tenor',
                                'reschedule'     => 'Reschedule (Atur Ulang Jadwal)',
                                'reconditioning' => 'Reconditioning (Ubah Syarat)',
                            ])->required()->live(),
                        Forms\Components\TextInput::make('tambahan_tenor')->label('Tambahan Tenor (bulan)')
                            ->numeric()->minValue(1)->visible(fn (Forms\Get $get) => $get('jenis') === 'perpanjangan'),
                        Forms\Components\DatePicker::make('tanggal_mulai_baru')->label('Tanggal Mulai Baru')
                            ->visible(fn (Forms\Get $get) => $get('jenis') === 'reschedule'),
                        Forms\Components\TextInput::make('bunga_persen')->label('Bunga Baru (%)')
                            ->numeric()->visible(fn (Forms\Get $get) => $get('jenis') === 'reconditioning'),
                        Forms\Components\TextInput::make('margin_persen')->label('Margin Baru (%)')
                            ->numeric()->visible(fn (Forms\Get $get) => $get('jenis') === 'reconditioning'),
                        Forms\Components\TextInput::make('diskon_pokok')->label('Diskon Pokok (Rp)')
                            ->numeric()->prefix('Rp')->visible(fn (Forms\Get $get) => $get('jenis') === 'reconditioning'),
                        Forms\Components\Toggle::make('hapus_denda')->label('Hapus Semua Denda')
                            ->visible(fn (Forms\Get $get) => $get('jenis') === 'reconditioning'),
                        Forms\Components\Textarea::make('alasan')->label('Alasan')->required()->rows(2),
                    ])
                    ->action(function (Pinjaman $r, array $data) {
                        PinjamanService::restrukturisasi($r, $data['jenis'], $data, $data['alasan']);
                        Notification::make()->title('Restrukturisasi berhasil')->success()->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PinjamanResource\RelationManagers\JadwalRelationManager::class,
            \App\Filament\Resources\PinjamanResource\RelationManagers\PembayaranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPinjamen::route('/'),
            'create' => Pages\CreatePinjaman::route('/create'),
            'edit'   => Pages\EditPinjaman::route('/{record}/edit'),
            'view'   => Pages\ViewPinjaman::route('/{record}'),
        ];
    }
}
