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
    }
}
