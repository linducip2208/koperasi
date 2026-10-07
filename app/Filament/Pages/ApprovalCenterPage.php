<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\PinjamanPembayaran;
use App\Models\Procurement;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class ApprovalCenterPage extends Page implements HasTable
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'pinjaman';
    protected static ?string $navKey = 'Approval Center';
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationGroup = 'OPERATIONS';
    protected static ?string $navigationLabel = 'Approval Center';
    protected static ?string $title = 'Approval Center';
    protected static ?int $navigationSort = 31;

    protected static string $view = 'filament.pages.approval-center';

    public string $tab = 'pinjaman';

    public function table(Table $table): Table
    {
        return match ($this->tab) {
            'pembayaran' => $this->pembayaranTable($table),
            'procurement' => $this->procurementTable($table),
            'anggota' => $this->anggotaTable($table),
            default => $this->pinjamanTable($table),
        };
    }

    protected function pinjamanTable(Table $table): Table
    {
        return $table->query(Pinjaman::with('anggota')->where('status', 'pengajuan')->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('nomor_akad')->label('Akad')->copyable(),
                Tables\Columns\TextColumn::make('anggota.nama')->label('Anggota'),
                Tables\Columns\TextColumn::make('plafon')->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')->label('Diajukan')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('setuju')->label('Setujui Level')->color('success')->requiresConfirmation()
                    ->action(fn ($r) => $this->wrap(fn () => \App\Domain\Pinjaman\PinjamanService::approve($r, auth()->id()))),
                Tables\Actions\Action::make('tolak')->label('Tolak')->color('danger')
                    ->form([\Filament\Forms\Components\Textarea::make('alasan')->label('Alasan')->required()])
                    ->action(fn ($r, $d) => $this->wrap(fn () => \App\Domain\Pinjaman\PinjamanService::tolak($r, auth()->id(), $d['alasan']))),
            ])->paginated(15);
    }

    protected function pembayaranTable(Table $table): Table
    {
        return $table->query(PinjamanPembayaran::with(['pinjaman.anggota'])->where('status', 'pending')->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('Nomor'),
                Tables\Columns\TextColumn::make('pinjaman.nomor_akad')->label('Akad'),
                Tables\Columns\TextColumn::make('total_bayar')->label('Jumlah')->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibayar')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi')->label('Verifikasi')->color('success')->requiresConfirmation()
                    ->action(fn ($r) => $this->wrap(fn () => \App\Domain\Pinjaman\PinjamanService::verifikasiPembayaran($r, true, 'Via Approval Center'))),
                Tables\Actions\Action::make('tolak')->label('Tolak')->color('danger')->requiresConfirmation()
                    ->action(fn ($r) => $this->wrap(fn () => \App\Domain\Pinjaman\PinjamanService::verifikasiPembayaran($r, false, 'Ditolak via Approval Center'))),
            ])->paginated(15);
    }

    protected function procurementTable(Table $table): Table
    {
        return $table->query(Procurement::whereIn('status', ['submitted', 'review'])->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->label('Nomor'),
                Tables\Columns\TextColumn::make('judul')->limit(40),
                Tables\Columns\TextColumn::make('estimasi')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('proses')->label('Review/Approve')->icon('heroicon-o-arrow-path')
                    ->url(fn ($r) => \App\Filament\Resources\ProcurementResource::getUrl('index')),
            ])->paginated(15);
    }

    protected function anggotaTable(Table $table): Table
    {
        return $table->query(Anggota::where('kategori', 'calon')->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('nomor_anggota')->label('Nomor'),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('telp')->label('Telp'),
                Tables\Columns\TextColumn::make('created_at')->label('Daftar')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('aktifkan')->label('Aktifkan')->color('success')->requiresConfirmation()
                    ->action(fn ($r) => $this->wrap(fn () => $r->update(['kategori' => 'biasa', 'status' => 'aktif']))),
            ])->paginated(15);
    }

    protected function wrap(callable $fn): void
    {
        try {
            $fn();
            Notification::make()->title('Berhasil diproses.')->success()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Gagal: '.$e->getMessage())->danger()->send();
        }
    }
}
