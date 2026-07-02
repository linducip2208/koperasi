<?php

namespace App\Filament\Resources\PinjamanResource\RelationManagers;

use App\Domain\Pinjaman\PinjamanService;
use App\Models\PinjamanPembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';
    protected static ?string $title = 'Riwayat Pembayaran';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->copyable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('jenis')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'pending'    => 'warning',
                    'disetujui'  => 'success',
                    'ditolak'    => 'danger',
                    default      => 'gray',
                }),
                Tables\Columns\TextColumn::make('total_bayar')->money('IDR')->weight('bold'),
                Tables\Columns\TextColumn::make('alokasi_pokok')->label('Pokok')->money('IDR')->color('success'),
                Tables\Columns\TextColumn::make('alokasi_margin')->label('Margin')->money('IDR')->color('info'),
                Tables\Columns\TextColumn::make('alokasi_denda')->label('Denda')->money('IDR')->color('danger'),
                Tables\Columns\TextColumn::make('alokasi_titipan')->label('Titipan')->money('IDR')->color('warning'),
                Tables\Columns\TextColumn::make('metode_bayar')->badge(),
                Tables\Columns\TextColumn::make('verifiedBy.name')->label('Verifikator'),
                Tables\Columns\TextColumn::make('verified_at')->label('Tgl Verifikasi')->date('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('slip')->label('Slip PDF')
                    ->icon('heroicon-o-printer')->color('info')
                    ->url(fn (PinjamanPembayaran $r) => route('dokumen.slip', $r->id), shouldOpenInNewTab: true),
                Tables\Actions\Action::make('lihat_bukti')->label('Bukti Bayar')
                    ->icon('heroicon-o-photo')->color('gray')
                    ->visible(fn (PinjamanPembayaran $r) => ! empty($r->bukti_bayar))
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(fn (PinjamanPembayaran $r) => new HtmlString('<img src="'.asset('storage/'.$r->bukti_bayar).'" class="max-w-full rounded-lg" />')),
                Tables\Actions\Action::make('verifikasi_setujui')->label('Setujui Bayar')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (PinjamanPembayaran $r) => $r->status === 'pending' && auth()->user()?->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas']))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription('Setujui pembayaran ini? Saldo pinjaman akan diperbarui dan jurnal akan dibuat.')
                    ->action(function (PinjamanPembayaran $r) {
                        PinjamanService::verifikasiPembayaran($r, true);
                        Notification::make()->title('Pembayaran disetujui.')->success()->send();
                    }),
                Tables\Actions\Action::make('verifikasi_tolak')->label('Tolak Bayar')
                    ->icon('heroicon-o-x-circle')->color('danger')
                    ->visible(fn (PinjamanPembayaran $r) => $r->status === 'pending' && auth()->user()?->hasAnyRole(['super-admin', 'admin', 'manajer', 'pengawas']))
                    ->form([
                        Forms\Components\Textarea::make('catatan')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (PinjamanPembayaran $r, array $data) {
                        PinjamanService::verifikasiPembayaran($r, false, $data['catatan']);
                        Notification::make()->title('Pembayaran ditolak.')->warning()->send();
                    }),
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
