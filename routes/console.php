<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('koperasi:hitung-denda')->dailyAt('01:00');
Schedule::command('koperasi:auto-debet')->dailyAt('03:00');
Schedule::command('koperasi:penyusutan-aset')->monthlyOn(1, '02:00');
Schedule::command('backup:run --only-db')->dailyAt('03:00');     // Database-only daily backup
Schedule::command('backup:run')->weeklyOn(0, '04:00');           // Full backup (DB + files) tiap Minggu
Schedule::command('backup:clean')->dailyAt('05:00');             // Cleanup backup lama sesuai retention
Schedule::command('backup:monitor')->dailyAt('06:00');           // Health check backup
Schedule::command('koperasi:bunga-simpanan --periode=harian')->dailyAt('00:15');
Schedule::command('koperasi:bunga-simpanan --periode=bulanan')->monthlyOn(1, '00:30');
Schedule::command('koperasi:reminder-angsuran --days=3')->dailyAt('08:00');
Schedule::command('koperasi:reminder-angsuran --days=1')->dailyAt('08:30');
Schedule::command('seo:indexnow --new')->dailyAt('02:45');              // IndexNow auto-submit ke Bing, Yandex, Seznam, Naver
