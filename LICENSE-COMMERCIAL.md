# Commercial License — Koperasi Management System (DRAFT)

> DRAF untuk direview pemilik/legal. Bukan nasihat hukum.

1. **Licensed, not sold.** Anda membeli hak pakai, bukan kepemilikan source code.
2. **Satu lisensi = satu instalasi** (1 koperasi, 1 database, 1 domain). Pindah domain = revoke + aktivasi ulang via `/__pair`.
3. **Aktivasi wajib.** Aplikasi membutuhkan lock lisensi valid (ditandatangani server). Heartbeat 24 jam; bila server unreachable berlaku grace 7 hari, setelah itu instalasi terkunci sampai terhubung kembali.
4. **Dilarang:** redistribusi, resale, sublicense, atau mempublikasikan ulang tanpa izin tertulis — kecuali hak white-label pada paket yang mencakupnya.
5. **White-label:** paket Professional ke atas boleh mengganti nama/logo/brand. Jejak atribusi produk pada file lisensi tidak boleh dihapus.
6. **Update & support** mengikuti masa berlaku paket (lihat `expires_at` di halaman Admin → License). Update wajib didahului backup (`php artisan app:backup`).
7. **Batasan tanggung jawab:** sejauh diizinkan hukum, vendor tidak bertanggung jawab atas kerugian tidak langsung akibat kesalahan pencatatan operator. Selalu verifikasi laporan sebelum RAT.
8. **Dependency pihak ketiga** (Laravel, Filament, Spatie, dll) tunduk pada lisensinya masing-masing dan bukan bagian dari penjualan ini.
