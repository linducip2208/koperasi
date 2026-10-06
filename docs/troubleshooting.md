# Troubleshooting

- `app:health` ERROR pertama = petunjuk utama. Log: `storage/logs/laravel.log`.
- 419: sesi habis — login ulang. 403 dokumen: bukan pemilik / tanpa `anggota.view`.
- Webhook `invalid_signature`: set `webhook_secret` di extra_headers provider (wajib production). Replay aman via Webhook Center (idempotent).
- License GRACE: server unreachable — cek egress ke `LICENSE_SERVER_URL`; aplikasi jalan 7 hari.
- Import gagal: cek Error CSV di history batch; finansial selalu rollback penuh per 500 baris.
- Report kosong padahal ada transaksi: pastikan jurnal **posted** (draft tidak masuk laporan) dan periode benar; cek Data Quality → jurnal tidak balance.
- Queue menumpuk: `php artisan queue:work`; gagal di `failed_jobs`.
- `npm run build` gagal: hapus `node_modules` + `npm install` ulang (Node 18+).
