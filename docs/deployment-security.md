# Deployment, Security & Troubleshooting

## Deployment (Nginx/Apache/Laragon/aaPanel/VPS)

1. `composer install --no-dev --optimize-autoloader`, `npm install && npm run build` (tanpa CDN — asset lokal `public/build`).
2. `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY`, DB, `LICENSE_DEV_BYPASS=false`, mail/queue (`database` + supervisor worker), `storage:link`.
3. `php artisan migrate --force`, `config:cache route:cache view:cache`, cron schedule:run, backup offsite, SSL. Jangan `chmod 777` (cukup owner writable: `storage/`, `bootstrap/cache/`).
4. Verifikasi: `app:health` (harus OK), `test`, pairing lisensi, smoke login → anggota → transaksi → laporan → backup.

## Security

CSRF aktif di semua form (termasuk fallback login), throttle login/API/QR, signed URL kedaluwarsa, otorisasi kepemilikan dokumen (IDOR fix), upload tervalidasi (mime/size/image), sanitasi HTML blog & template email, secret hanya di `.env`/kolom terenkripsi, log tanpa PII/secret, webhook terverifikasi + idempotent, transaksi finansial atomic + row-lock + unique, error page tanpa stack trace.

## Troubleshooting

- 419: sesi habis — login ulang. 403 dokumen: bukan pemilik / tanpa izin `anggota.view`.
- Webhook `invalid_signature`: set `webhook_secret` di extra_headers provider (wajib production).
- License GRACE: server unreachable — periksa koneksi keluar ke `LICENSE_SERVER_URL`; aplikasi tetap jalan 7 hari.
- Queue menumpuk: jalankan `php artisan queue:work`; cek `failed_jobs`.
- `app:health` ERROR pertama = petunjuk utama; log di `storage/logs/laravel.log`.
