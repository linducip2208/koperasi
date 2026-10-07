<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\CollectionFollowup;
use App\Models\Pinjaman;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class CollectionCenterPage extends Page implements HasTable
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'pinjaman';
    protected static ?string $navKey = 'Collection Center';
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'OPERATIONS';
    protected static ?string $navigationLabel = 'Collection Center';
    protected static ?string $title = 'Collection Center';
    protected static ?int $navigationSort = 30;

    protected static string $view = 'filament.pages.collection-center';

    public string $bucket = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->baseQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nomor_akad')->label('Akad')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('saldo_pokok')->label('Outstanding')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('tunggakan_hari')->label('Telat (hr)')->sortable()->badge()
                    ->color(fn ($s) => $s <= 0 ? 'success' : ($s <= 30 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('kolektor.name')->label('Kolektor')->placeholder('-'),
                Tables\Columns\TextColumn::make('followups_count')->label('Follow-up')->counts('followups')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bucket')->label('Bucket')->options([
                    'today' => 'Jatuh tempo hari ini', 'overdue' => 'Semua overdue',
                    'd1_7' => '1–7', 'd8_30' => '8–30', 'd31_60' => '31–60',
                    'd61_90' => '61–90', 'd91_180' => '91–180', 'd180' => '180+',
                ])->query(fn ($q, $v) => $this->applyBucket($q, $v['value'] ?? null)),
            ])
            ->actions([
                Tables\Actions\Action::make('assign')
                    ->label('Assign')->icon('heroicon-o-user-plus')
                    ->form([Select::make('kolektor_id')->label('Kolektor')
                        ->options(User::role('kolektor')->pluck('name', 'id'))->searchable()->required()])
                    ->action(function ($record, array $data) {
                        $record->update(['kolektor_id' => $data['kolektor_id']]);
                        Notification::make()->title('Kolektor ditetapkan.')->success()->send();
                    }),
                Tables\Actions\Action::make('followup')
                    ->label('Follow-up')->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->form([
                        Select::make('jenis')->label('Jenis')->options([
                            'kunjungan' => 'Kunjungan', 'telepon' => 'Telepon', 'wa' => 'WhatsApp',
                            'janji_bayar' => 'Janji Bayar (PTP)', 'eskalasi' => 'Eskalasi', 'bayar' => 'Bayar di Tempat',
                        ])->required(),
                        Textarea::make('catatan')->label('Catatan')->rows(3)->required(),
                        TextInput::make('janji_nominal')->label('Janji Nominal (Rp)')->numeric()
                            ->visible(fn ($get) => $get('jenis') === 'janji_bayar'),
                        DatePicker::make('janji_tanggal')->label('Janji Tanggal')
                            ->visible(fn ($get) => $get('jenis') === 'janji_bayar'),
                        FileUpload::make('bukti_path')->label('Bukti (foto)')->image()->maxSize(2048)->directory('collection'),
                    ])
                    ->action(function ($record, array $data) {
                        CollectionFollowup::create([
                            'tenant_id' => $record->tenant_id, 'pinjaman_id' => $record->id,
                            'user_id' => auth()->id(), 'jenis' => $data['jenis'],
                            'catatan' => $data['catatan'],
                            'janji_nominal' => $data['janji_nominal'] ?? null,
                            'janji_tanggal' => $data['janji_tanggal'] ?? null,
                            'bukti_path' => $data['bukti_path'] ?? null,
                        ]);
                        Notification::make()->title('Follow-up tercatat.')->success()->send();
                    }),
                Tables\Actions\Action::make('remind')
                    ->label('WA Reminder')->icon('heroicon-o-bell')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $jadwal = $record->jadwal()->whereIn('status', ['jatuh_tempo', 'telat'])->orderBy('angsuran_ke')->first();
                        \App\Domain\Notifikasi\NotifikasiService::reminderAngsuran($record->anggota, [
                            'nomor_akad' => $record->nomor_akad,
                            'jumlah' => $jadwal?->total_angsuran ?? 0,
                            'jatuh_tempo' => $jadwal?->tanggal_jatuh_tempo?->format('d M Y') ?? '-',
                        ]);
                        Notification::make()->title('Reminder dikirim via queue.')->success()->send();
                    }),
            ])
            ->paginated(25);
    }

    protected function baseQuery()
    {
        return Pinjaman::with(['anggota', 'kolektor'])->whereIn('status', ['aktif', 'macet'])->orderByDesc('tunggakan_hari');
    }

    protected function applyBucket($q, ?string $bucket)
    {
        if (! $bucket || $bucket === 'all') return $q;
        if ($bucket === 'today') {
            return $q->whereHas('jadwal', fn ($j) => $j->whereDate('tanggal_jatuh_tempo', now()->toDateString())->whereIn('status', ['belum_jatuh_tempo', 'jatuh_tempo']));
        }
        if ($bucket === 'overdue') return $q->where('tunggakan_hari', '>', 0);
        [$min, $max] = match ($bucket) {
            'd1_7' => [1, 7], 'd8_30' => [8, 30], 'd31_60' => [31, 60],
            'd61_90' => [61, 90], 'd91_180' => [91, 180], 'd180' => [181, 99999],
            default => [0, 0],
        };
        return $q->whereBetween('tunggakan_hari', [$min, $max]);
    }

    public function getViewData(): array
    {
        $perf = User::role('kolektor')->withCount(['followups' => fn ($q) => $q->whereMonth('created_at', now()->month)])->get()
            ->map(fn ($u) => ['nama' => $u->name, 'followup' => $u->followups_count,
                'assigned' => Pinjaman::where('kolektor_id', $u->id)->whereIn('status', ['aktif', 'macet'])->count()])->all();
        return ['performance' => $perf];
    }
}
