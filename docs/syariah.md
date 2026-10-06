# Koperasi Syariah

Aktif bila `operation_mode` = syariah/dual (Profil Koperasi). Akad didukung per produk (`akad_type`): murabahah, mudharabah, musyarakah, ijarah, ijarah_mb, qardh, rahn, salam, istishna — kalkulator + factory + COA syariah + produk default sudah ada; report hanya menampilkan akad yang **benar-benar bertransaksi**.

## Report syariah

Portofolio per akad (outstanding + margin + chart), pendapatan syariah (margin/ujrah + ta’zir denda). Lokasi: Report Center → kategori Syariah.

## Bagi hasil (`ProfitSharingCalculator`)

Pool → bobot (saldo × hari_aktif/hari_periode) → nisbah basis poin → rupiah **integer** dengan sisa largest-remainder deterministik. Snapshot JSON per perhitungan (pool, peserta, versi) agar historis stabil. Test: deterministik, pool nol, partial period, nisbah berubah (lihat `ReportCenterTest`).
