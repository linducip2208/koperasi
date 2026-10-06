# Konfigurasi

- `.env` — semua secret (lihat `.env.example` per section). Production: `APP_ENV=production`, `APP_DEBUG=false`, `LICENSE_DEV_BYPASS=false`.
- `config/product.php` — identitas produk (nama, versi `1.0.0`, vendor). Versi tampil di footer admin, health, license, update center.
- `config/support.php` — kontak vendor (WA/email) untuk footer & halaman bantuan.
- Admin → **Profil Koperasi** — identitas koperasi: nama pendek, badan hukum, NIK, NPWP, akta, logo, favicon, alamat + wilayah, telp/WA/email/website, pengurus (ketua/sekretaris/bendahara), mode operasi (konvensional/syariah/dual), mata uang, timezone, tahun buku, tema + warna, footer dokumen, prefix nomor (invoice/anggota/pinjaman/simpanan).
- Admin → Pengaturan (tabel `settings`) — `notifikasi` (wa_provider/wa_api_key/wa_api_url), `ppob` (ppob_provider), dan umum lainnya. Di-cache 1 jam.
