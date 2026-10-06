<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Anggota;
use App\Models\Jurnal;
use App\Models\MemberDocument;
use App\Models\Pinjaman;
use Filament\Pages\Page;

class DataQualityPage extends Page
{
    use HasRoleAccess;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationLabel = 'Data Quality';
    protected static ?string $title = 'Data Quality Center';
    protected static ?int $navigationSort = 59;

    protected static string $view = 'filament.pages.data-quality';

    public function getViewData(): array
    {
        $dupNik = Anggota::whereNotNull('nik')->selectRaw('nik, COUNT(*) c')->groupBy('nik')->having('c', '>', 1)->get();
        $tanpaNik = Anggota::where('status', 'aktif')->whereNull('nik')->count();
        $telpInvalid = Anggota::where('status', 'aktif')->whereNotNull('telp')->pluck('telp')
            ->filter(fn ($t) => strlen(preg_replace('/[0-9+\-\s]/', '', (string) $t)) > 3)->count();
        $tanpaDokumen = Anggota::where('status', 'aktif')->whereDoesntHave('documents')->count();
        $jurnalBocor = Jurnal::whereColumn('total_debit', '!=', 'total_kredit')->count();
        $pinjamanTanpaJadwal = Pinjaman::where('status', 'aktif')->whereDoesntHave('jadwal')->count();
        $dokKedaluwarsa = MemberDocument::where('status', 'aktif')->whereDate('tanggal_kedaluwarsa', '<', now())->count();
        $pinjamanTanpaJaminan = Pinjaman::where('status', 'aktif')->whereDoesntHave('jaminan')->count();

        $issues = [
            ['level' => 'critical', 'label' => 'NIK ganda', 'count' => $dupNik->count(), 'detail' => $dupNik->pluck('nik')->take(5)->implode(', ')],
            ['level' => 'warning', 'label' => 'Anggota aktif tanpa NIK', 'count' => $tanpaNik, 'detail' => 'Lengkapi via import/edit'],
            ['level' => 'info', 'label' => 'Telepon tidak valid', 'count' => $telpInvalid, 'detail' => 'Mengganggu reminder WA'],
            ['level' => 'warning', 'label' => 'Anggota tanpa dokumen', 'count' => $tanpaDokumen, 'detail' => 'KTP/KK belum diarsipkan'],
            ['level' => 'critical', 'label' => 'Jurnal tidak balance', 'count' => $jurnalBocor, 'detail' => 'Harus 0 — reverse & perbaiki'],
            ['level' => 'warning', 'label' => 'Pinjaman aktif tanpa jadwal', 'count' => $pinjamanTanpaJadwal, 'detail' => 'Generate ulang jadwal'],
            ['level' => 'warning', 'label' => 'Dokumen kedaluwarsa', 'count' => $dokKedaluwarsa, 'detail' => 'Tindaklanjuti di Document Center'],
            ['level' => 'info', 'label' => 'Pinjaman aktif tanpa agunan', 'count' => $pinjamanTanpaJaminan, 'detail' => 'Review kebijakan agunan'],
        ];

        return ['issues' => $issues,
            'critical' => collect($issues)->where('level', 'critical')->sum('count'),
            'warning' => collect($issues)->where('level', 'warning')->sum('count')];
    }
}
