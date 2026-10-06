# White-label

Semua brand diambil dari profil koperasi + config produk — tidak ada hardcode di kode:

- Admin: brand name + footer versi + kontak support (`AdminPanelProvider`, `config/support.php`).
- Portal anggota: nama koperasi + nomor WA koperasi (fallback support vendor).
- PDF (laporan, kuitansi, kontrak, slip, invoice, buku RAT): kop memakai logo, nama pendek/slogan, alamat lengkap, telp/WA, footer dokumen — lihat `documents/layout.blade.php`, `laporan/layout.blade.php`.
- Kartu anggota: nama koperasi + QR login server-side.
- Email notifikasi: pengirim memakai email + nama koperasi bila diisi.
- SEO/landing (materi penjualan produk): nomor kontak dari `config/support.php`.

Menambah instalasi baru untuk koperasi lain = install ulang + ganti profil + lisensi sendiri.
