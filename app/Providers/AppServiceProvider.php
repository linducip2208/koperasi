<?php

namespace App\Providers;

use App\Models\Pinjaman;
use App\Models\PinjamanPembayaran;
use App\Models\SimpananTransaksi;
use App\Observers\PinjamanObserver;
use App\Observers\PinjamanPembayaranObserver;
use App\Observers\SimpananTransaksiObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        SimpananTransaksi::observe(SimpananTransaksiObserver::class);
        Pinjaman::observe(PinjamanObserver::class);
        PinjamanPembayaran::observe(PinjamanPembayaranObserver::class);

        // Identitas koperasi tersedia di SEMUA view sebagai $koperasi.
        // Aman saat installer/DB belum siap: fallback null.
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $koperasi = 'unresolved';
            if ($koperasi === 'unresolved') {
                $koperasi = \App\Support\CooperativeContext::current();
            }
            $view->with('koperasi', $koperasi);
        });
    }
}
