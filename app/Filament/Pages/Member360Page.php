<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Anggota;
use App\Models\RatKehadiran;
use App\Models\RatVotingSuara;
use App\Models\ShuDistribusi;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Member360Page extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'anggota';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = '📊 Laporan';
    protected static ?string $navigationLabel = 'Anggota 360';
    protected static ?string $title = 'Anggota 360';
    protected static ?int $navigationSort = 52;

    protected static string $view = 'filament.pages.member-360';

    public ?array $data = ['anggota_id' => null];

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('anggota_id')->label('Anggota')
                ->options(Anggota::orderBy('nama')->limit(1000)->pluck('nama', 'id'))
                ->searchable()->required(),
        ])->statePath('data');
    }

    public function getViewData(): array
    {
        $id = $this->data['anggota_id'] ?? request()->query('anggota_id');
        if (! $id) return ['anggota' => null];
        $a = Anggota::with([
            'simpanan.produk', 'simpanan.transaksi' => fn ($q) => $q->latest('tanggal')->limit(10),
            'pinjaman.produk', 'pinjaman.jadwal' => fn ($q) => $q->orderBy('angsuran_ke'),
            'ahliWaris',
        ])->find($id);
        if (! $a) return ['anggota' => null];

        return ['anggota' => [
            'model' => $a,
            'simpanan_saldo' => $a->simpanan->where('status', 'aktif')->sum('saldo'),
            'pinjaman_outstanding' => $a->pinjaman->whereIn('status', ['aktif', 'macet'])->sum('saldo_pokok'),
            'tunggakan' => $a->pinjaman->whereIn('status', ['aktif', 'macet'])->where('tunggakan_hari', '>', 0)->sum('saldo_pokok'),
            'shu' => (int) ShuDistribusi::where('anggota_id', $a->id)->sum('total_shu'),
            'hadir_rat' => RatKehadiran::where('anggota_id', $a->id)->count(),
            'voting' => RatVotingSuara::where('anggota_id', $a->id)->count(),
        ]];
    }
}
