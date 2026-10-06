# Akuntansi (SAK EP)

- **COA** per koperasi + mapping otomatis per produk (simpanan/pinjaman/kas). Akun khusus syariah tersedia.
- **Jurnal**: semua transaksi finansial (setoran, tarikan, mutasi, pencairan, angsuran, gaji, susut, POS) auto-jurnal via `JurnalService::create()` dengan validasi `debit == kredit`, atomic (`DB::transaction`), nomor unik.
- **Immutability**: jurnal posted tidak bisa edit/hapus (model guard) — koreksi via **Reverse** (jurnal pembalik tertaut) atau Unpost (draft + audit log). Transaksi simpanan immutable (kecuali penautan jurnal); pembayaran terverifikasi nominalnya immutable.
- **Periode**: tutup buku di Periode Akuntansi (`closed`) — service menolak create/posting pada periode locked.
- **Laporan** (dari jurnal posted nyata, filter cabang + ekspor PDF/Excel): Buku Besar, Neraca Saldo, Neraca, Laba Rugi (SHU), Arus Kas, Perubahan Ekuitas, CALK, Aging piutang, Ringkasan produk, ODS Kemenkop.
