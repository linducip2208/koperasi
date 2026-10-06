# Koperasi Management System

Aplikasi manajemen koperasi **standalone & komersial** — 1 instalasi = 1 koperasi = 1 database = 1 lisensi = 1 brand.

Stack: Laravel 12 · PHP 8.2+ · MySQL/MariaDB (production) · Filament 3 · Tabler + Tailwind lokal via Vite/npm (0 CDN) · Blade · Sanctum · Spatie (Permission, Activity Log, Backup, Media Library) · Dompdf · Laravel Excel · Chart.js lokal.

Modul: Anggota 360 (kartu + QR + statement), Simpanan (pokok/wajib/sukarela/berjangka + mutasi + bunga otomatis), Pinjaman konvensional & syariah (workflow approval 3 level, denda, kolektabilitas, restrukturisasi), Toko/POS, Unit Produsen & Jasa, Akuntansi SAK EP (jurnal immutable + reversal, tutup periode, buku besar, neraca saldo, neraca, laba rugi, arus kas, perubahan ekuitas, CALK, aging), SHU (snapshot alokasi), RAT/E-RAT (QR check-in, quorum otomatis, e-voting, buku tahunan), Portal anggota + API v1, **Report Center (30 report + executive dashboard + custom builder + arsip + scheduled + AI insights)**, **Import/Export Center (CSV/XLSX validasi + template + queue)**, Payment gateway abstraction (redirect/QRIS/VA + webhook idempotent), WhatsApp (Fonnte/WAblas/log + template DB + queue), PPOB, PDF Engine white-label, Backup terjadwal, System Health, License pairing v3 (RSA + AES-GCM + heartbeat + grace 7 hari), Installer web, Update Center.

## Persyaratan

PHP >= 8.2 dengan ekstensi: pdo, openssl, mbstring, tokenizer, xml, ctype, json, bcmath, fileinfo (gd opsional). MySQL 8 / MariaDB 10.6+ untuk production (SQLite untuk dev). Composer, Node 18+, cron.

## Instalasi

Opsi A — installer web (disarankan):

1. Arahkan web server ke `public/`, copy `.env.example` → `.env`, isi `APP_KEY` (`php artisan key:generate`).
2. Buka `/install` → ikuti Welcome → Requirements → Database → Application → Cooperative → Admin → License → Finish. Installer terkunci otomatis (`storage/app/.installed`).
3. Aktivasi lisensi di `/__pair`, lalu login `/admin`.

Opsi B — manual: `composer install && npm install && npm run build`, konfigurasi `.env`, `php artisan migrate --seed`, `php artisan storage:link`.

Production wajib: `APP_ENV=production`, `APP_DEBUG=false`, `LICENSE_DEV_BYPASS=false`, `php artisan config:cache route:cache view:cache`, cron `* * * * * php artisan schedule:run`, queue worker untuk `database` queue, SSL, backup offsite. Detail: `docs/deployment.md`.

## Konfigurasi

Semua secret via `.env` — lihat `.env.example` (section APPLICATION, DATABASE, CACHE, QUEUE, MAIL, FILESYSTEM, LICENSE, WHATSAPP, PAYMENT, PPOB, BACKUP, SUPPORT, BRANDING). Identitas koperasi (nama, logo, warna, pengurus, prefix nomor) diatur di admin **Profil Koperasi** — tidak ada hardcode brand di kode. Kontak vendor di `config/support.php`.

## Lisensi

Software licensed, not sold — aktivasi per instalasi via pairing key (`/__pair`), verifikasi signature RSA lokal, heartbeat 24 jam dengan grace 7 hari. Detail: `docs/licensing.md`, `LICENSE-COMMERCIAL.md`.

## Perintah penting

`php artisan app:health` · `php artisan app:license-status` · `php artisan app:backup [--full]` · `php artisan app:update-check` · `php artisan koperasi:rat-sync-quorum` · `php artisan test`

## Dokumentasi

Lihat folder `docs/`: installation, configuration, white-label, licensing, backup-restore-update, accounting, loans-savings, rat, api, deployment-security, reports, import-export, syariah.

## Lisensi pihak ketiga

Framework Laravel (MIT) dan dependency composer/npm tetap milik pemiliknya masing-masing. Aplikasi koperasi ini berlisensi komersial — lihat `LICENSE-COMMERCIAL.md`.
