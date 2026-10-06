# Instalasi

## Via installer web (`/install`)

1. Welcome → 2. Requirements (PHP/extensi/writable dicek otomatis) → 3. Database (SQLite/MySQL + test koneksi) → 4. Application (nama, URL, timezone) → 5. Cooperative (nama, mode operasi, kontak, tahun buku) → 6. Admin (min. password 12) → 7. License → 8. Finish (terkunci otomatis, file `storage/app/.installed`).

Hapus file `.installed` hanya bila ingin install ulang dari nol (data hilang).

## Manual

```bash
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
# edit .env (DB_*, APP_URL, LICENSE_*, SUPPORT_*)
php artisan migrate --seed
php artisan storage:link
```

## First-run

Bila installer dilewati: lengkapi Admin → Profil Koperasi, Nomor Akun (COA sudah di-seed), Produk Simpanan/Pinjaman + COA mapping, Kas, Notifikasi (group=notifikasi), Payment Gateway, lalu aktivasi lisensi.
