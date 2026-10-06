# RAT / E-RAT

- Acara: tahun buku, tanggal, lokasi, agenda, notulen, keputusan (Admin → SHU & RAT).
- **Check-in QR**: tombol admin menghasilkan signed URL (30 hari) untuk panitia di pintu masuk; input nomor anggota; duplikat ditolak; **quorum dihitung otomatis** dari tabel kehadiran (RAT selesai = arsip angka manual).
- **E-voting**: kelola di E-Voting RAT (judul, opsi, jendela waktu); anggota vote di portal; validasi periode + anggota aktif + 1 suara per voting (unique DB); hasil live per opsi.
- **Buku tahunan** (`/rat/{id}/buku-tahunan` PDF): pengesahan, quorum, laporan SAK EP tahun buku, distribusi SHU, hasil voting, keputusan, notulen, daftar hadir — siap disahkan RAT.
- Audit: seluruh entitas RAT tercatat di activity log (isi pilihan suara tidak dicatat — kerahasiaan).
