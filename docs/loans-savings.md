# Simpanan & Pinjaman

## Simpanan

Produk: pokok, wajib, sukarela, berjangka/deposito, khusus (+ wadiah/mudharabah syariah). Operasi via `SimpananService`: setor, tarik (cek `saldoTersedia` = saldo − blokir, di dalam row-lock), **mutasi antar rekening** (atomic + jurnal), buka rekening. Bunga harian/bulanan via scheduler (`koperasi:bunga-simpanan`). Blokir saldo untuk jaminan. Semua transaksi beraudit + berjurnal; tanpa hard delete.

## Pinjaman

Produk konvensional (flat/efektif/anuitas) & syariah (murabahah/mudharabah/musyarakah/ijarah/qardh/…). Alur: pengajuan → (survey/analisa) → **approval 3 level berjenjang dengan penegakan role** (AO → Manajer → Admin/Ketua) → pencairan → aktif → angsuran (alokasi denda→margin→pokok, perlu verifikasi) → lunas / macet. Denda harian otomatis, kolektabilitas 5 level OJK, restrukturisasi (tercatat + snapshot), auto-debet, pelunasan dipercepat. Jadwal deterministik (integer rupiah, tested). Pengajuan & setoran online dari portal masuk sebagai draft menunggu verifikasi admin.
