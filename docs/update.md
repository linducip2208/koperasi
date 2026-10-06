# Update Center & Versi Produk

- Versi sentral: `config/product.php` (`1.0.0`, format MAJOR.MINOR.PATCH). Tampil di footer admin, System Health, License, Update Center, PDF. Arsip report menyimpan versi (`report_version`).
- Cek update: `php artisan app:update-check` atau Admin → Update Center (baca `LICENSE_SERVER_URL/api/version/check`, cache 1 hari, tampil release notes).
- Alur aman wajib: `app:backup` → `down` → update files → `migrate --force` → `app:health` + `test` → `up`.
