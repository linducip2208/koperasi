# Lisensi (Pairing v3)

Alur: aplikasi → server `LICENSE_SERVER_URL` → payload bertanda tangan → verifikasi RSA lokal (`public/marketplace.public.pem`) → lock AES-256-GCM (`storage/app/.license.lock`, 0600, kunci HKDF dari APP_KEY + domain).

- Aktivasi: `/__pair` (wizard) atau Admin → License → Aktivasi.
- Status: UNPAIRED | ACTIVE | GRACE_PERIOD | EXPIRED | SUSPENDED | REVOKED | INVALID. Lihat Admin → License (ID, customer, plan, issued/expires, heartbeat, grace deadline, installation ID, features) atau `php artisan app:license-status`.
- Heartbeat tiap 24 jam (console `koperasi:license-heartbeat` harian + on-demand). Server unreachable → grace 7 hari (`LICENSE_HEARTBEAT_GRACE`); lewat itu lock dihapus (aplikasi minta pairing ulang — data aman).
- Private key hanya di server. Aplikasi tidak pernah menampilkan secret. Tidak ada bypass query-string.
- Dev: `LICENSE_DEV_BYPASS=true` hanya untuk host lokal + `APP_ENV!=production`. Health check ERROR bila bypass aktif di production.
