<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Rat;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class EventCalendarWidget extends Widget
{
    protected static string $view = 'filament.widgets.event-calendar';
    protected static ?int $sort = 12;
    protected int|string|array $columnSpan = 'full';

    public function getEvents(): array
    {
        $events = [];

        // Birthday bulan ini
        $ultah = Anggota::where('status', 'aktif')
            ->whereNotNull('tanggal_lahir')
            ->whereMonth('tanggal_lahir', now()->month)
            ->orderByRaw('DAY(tanggal_lahir)')
            ->limit(10)
            ->get();

        foreach ($ultah as $a) {
            $tgl = Carbon::parse($a->tanggal_lahir);
            $events[] = [
                'tanggal' => $tgl->format('d M'),
                'judul'   => "🎂 {$a->nama}",
                'kategori'=> 'birthday',
                'hari'    => $tgl->day,
            ];
        }

        // RAT mendatang
        $rat = Rat::where('tanggal', '>=', now())->orderBy('tanggal')->limit(3)->get();
        foreach ($rat as $r) {
            $diff = Carbon::parse($r->tanggal)->diffInDays(now(), false);
            $events[] = [
                'tanggal' => Carbon::parse($r->tanggal)->format('d M Y'),
                'judul'   => "📋 RAT: {$r->nama}",
                'kategori'=> 'rat',
                'hari'    => (int) $diff,
            ];
        }

        // Jatuh tempo pinjaman (hari ini)
        $jatuhTempo = \App\Models\PinjamanJadwal::where('tanggal_jatuh_tempo', now()->toDateString())
            ->where('status_bayar', 'belum')
            ->with('pinjaman.anggota')
            ->limit(5)
            ->get();

        foreach ($jatuhTempo as $jt) {
            $events[] = [
                'tanggal' => 'Hari ini',
                'judul'   => "💳 {$jt->pinjaman->anggota->nama} — Rp " . number_format($jt->total, 0, ',', '.'),
                'kategori'=> 'jatuh_tempo',
                'hari'    => 0,
            ];
        }

        return $events;
    }

    public function getTodayEvents(): array
    {
        $today = [];

        $ultah = Anggota::where('status', 'aktif')
            ->whereMonth('tanggal_lahir', now()->month)
            ->whereDay('tanggal_lahir', now()->day)
            ->pluck('nama');

        foreach ($ultah as $nama) {
            $today[] = "🎂 {$nama} ulang tahun hari ini!";
        }

        return $today;
    }
}
