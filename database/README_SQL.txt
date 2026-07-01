============================================================
  KOPERASI APP — Panduan File SQL
  Laravel 12 + Filament 3.3
============================================================

DAFTAR FILE:
-----------

1. koperasi_production_install.sql  (123 KB)
   → UNTUK INSTALASI PRODUKSI BARU
   → Berisi: Schema semua tabel + Admin user + Default tenant + Roles & Permissions
   → Tidak ada data demo
   → Cara pakai:
        CREATE DATABASE koperasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        mysql -u root -p koperasi < koperasi_production_install.sql

2. koperasi_full_with_demo.sql  (37 MB)
   → UNTUK DEMO / PRESENTASI
   → Berisi: Semua data demo (5.000 anggota, 15.000 simpanan, 8.000 pinjaman, dst)
   → Cara pakai:
        CREATE DATABASE koperasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        mysql -u root -p koperasi < koperasi_full_with_demo.sql

3. koperasi_schema.sql  (115 KB)
   → Schema saja (struktur tabel), tanpa data apapun
   → Berguna jika hanya ingin cek struktur atau update tabel baru

KREDENSIAL DEFAULT:
------------------
Admin Filament (/admin):
  Email    : admin@koperasi.local
  Password : admin123
  → Ganti password segera setelah login pertama!

Portal Anggota (/portal):
  Email    : anggota1@demo.local s/d anggota5000@demo.local
  Password : anggota123
  (hanya tersedia jika import koperasi_full_with_demo.sql)

SETELAH IMPORT:
--------------
1. Jalankan: php artisan migrate --pretend  (cek tidak ada migration baru yang belum jalan)
2. Jalankan: php artisan config:cache
3. Jalankan: php artisan route:cache
4. Set .env sesuai environment produksi (DB, APP_KEY, APP_URL, dll)
5. Ganti APP_DEBUG=false di .env produksi!

============================================================
