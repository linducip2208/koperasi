<?php

namespace App\Http\Controllers;

class DemoController extends Controller
{
    public function index()
    {
        $accounts = [
            ['role' => 'Super Admin', 'email' => 'admin@koperasi.local',    'password' => 'admin123', 'scope' => 'Semua fitur + settings + license + tenant'],
            ['role' => 'Manajer',     'email' => 'manager@koperasi.local',  'password' => 'admin123', 'scope' => 'View semua, approve pinjaman, export laporan'],
            ['role' => 'Kasir',       'email' => 'kasir@koperasi.local',    'password' => 'admin123', 'scope' => 'Simpanan, kas, pembayaran, POS, tagihan'],
            ['role' => 'AO',          'email' => 'ao@koperasi.local',       'password' => 'admin123', 'scope' => 'View anggota, kelola pengajuan pinjaman'],
            ['role' => 'Kolektor',    'email' => 'kolektor@koperasi.local', 'password' => 'admin123', 'scope' => 'View anggota, update pembayaran'],
            ['role' => 'Pengawas',    'email' => 'pengawas@koperasi.local', 'password' => 'admin123', 'scope' => 'View-only semua + laporan'],
            ['role' => 'Akuntan',     'email' => 'akuntan@koperasi.local',  'password' => 'admin123', 'scope' => 'Jurnal, COA, laporan keuangan'],
            ['role' => 'Anggota',     'email' => 'anggota1@demo.local',     'password' => 'anggota123', 'scope' => 'Portal anggota: simpanan, pinjaman, setoran, PPOB'],
        ];

        $tutorial = $this->tutorial();
        $features = $this->features();
        $menu = $this->menuStructure();

        return view('demo.index', compact('accounts', 'tutorial', 'features', 'menu'));
    }

    protected function menuStructure(): array
    {
        return [
            ['group' => 'Keanggotaan', 'icon' => '👥', 'items' => [
                ['name' => 'Anggota', 'desc' => 'CRUD anggota + 5 tab: Data Pribadi, Ahli Waris, Simpanan, Pinjaman, Kartu Anggota. Auto-generate nomor anggota. Upload KTP & foto. Cetak kartu dengan QR Code.', 'screenshot' => '03-anggota-list.png'],
            ]],
            ['group' => 'Simpan Pinjam', 'icon' => '💰', 'items' => [
                ['name' => 'Produk Simpanan', 'desc' => '6 produk default: Pokok, Wajib, Sukarela, Berjangka, Wadiah, Mudharabah. Atur bunga, setoran minimum, tenor, COA mapping.', 'screenshot' => '04-produk-simpanan.png'],
                ['name' => 'Simpanan', 'desc' => 'Setor & tarik dengan auto-jurnal ke COA. Tracking saldo per anggota. Cetak kuitansi PDF. Transfer antar simpanan.', 'screenshot' => '05-simpanan.png'],
                ['name' => 'Simpanan Blokir', 'desc' => 'Blokir saldo anggota (biasanya jaminan pinjaman). Lepas blokir saat lunas. Saldo terblokir tidak bisa ditarik.', 'screenshot' => 'blokir.png'],
                ['name' => 'Produk Pinjaman', 'desc' => '9 produk: Flat, Efektif, Anuitas + 6 akad syariah (Murabahah, Mudharabah, Musyarakah, Ijarah, Ijarah MB, Qardh). Atur margin, tenor, biaya admin.', 'screenshot' => '06-produk-pinjaman.png'],
                ['name' => 'Pinjaman', 'desc' => 'Full lifecycle: Pengajuan → Approval → Akad → Pencairan → Pembayaran. 9 kalkulator otomatis. Cetak kontrak PDF. Jaminan + asuransi.', 'screenshot' => '07-pinjaman.png'],
                ['name' => 'Pinjaman Restrukturisasi', 'desc' => 'Restrukturisasi pinjaman bermasalah: perpanjang tenor, grace period, konversi akad. History restrukturisasi tercatat.', 'screenshot' => 'restruktur.png'],
                ['name' => 'Iuran & Tagihan', 'desc' => 'Kelola tagihan anggota: iuran wajib, iuran sukarela, tagihan lainnya. Status: belum bayar, sebagian, lunas. Filter jatuh tempo.', 'screenshot' => '08-tagihan.png'],
            ]],
            ['group' => 'Toko & Unit Usaha', 'icon' => '🛒', 'items' => [
                ['name' => 'Toko Barang', 'desc' => 'Master data barang toko: kode, nama, kategori, supplier, satuan, harga beli/jual, stok, barcode. Multi-satuan support.', 'screenshot' => '09-toko-barang.png'],
                ['name' => 'Toko Kategori', 'desc' => 'Kategori barang toko dengan struktur tree (parent-child). Misal: Sembako > Beras, Minyak, Gula.', 'screenshot' => 'kategori.png'],
                ['name' => 'Toko Supplier', 'desc' => 'Data supplier barang: nama, alamat, kontak, NPWP. Tracking hutang supplier.', 'screenshot' => 'supplier.png'],
                ['name' => 'Toko Satuan', 'desc' => 'Satuan barang: kg, liter, pcs, pack, dus, dll. Support konversi multi-satuan.', 'screenshot' => 'satuan.png'],
                ['name' => 'Toko Penjualan (POS)', 'desc' => 'POS Kasir: pilih barang (repeater), auto-hitung total, diskon, pajak, kembalian. 6 metode bayar. Cetak struk thermal 58mm/80mm. Auto-jurnal.', 'screenshot' => '10-toko-penjualan.png'],
                ['name' => 'Toko Pembelian', 'desc' => 'Pembelian ke supplier: input barang masuk, auto-tambah stok, jurnal otomatis. Tracking hutang supplier.', 'screenshot' => 'pembelian.png'],
                ['name' => 'Unit Jasa Layanan', 'desc' => 'Master layanan jasa: fotokopi, laundry, transport, dll. Atur tarif dan satuan.', 'screenshot' => 'jasa.png'],
                ['name' => 'Unit Jasa Order', 'desc' => 'Order jasa pelanggan: tracking status (diproses → selesai → diambil), pembayaran, jurnal otomatis.', 'screenshot' => 'jasa-order.png'],
                ['name' => 'Unit Produsen Komoditi', 'desc' => 'Master komoditi hasil produksi: pertanian, peternakan, perikanan, kerajinan. Atur harga beli & jual.', 'screenshot' => 'komoditi.png'],
                ['name' => 'Unit Produsen Setoran', 'desc' => 'Setoran hasil produksi dari anggota: timbang, grading kualitas, hitung harga, jurnal otomatis.', 'screenshot' => 'setoran.png'],
            ]],
            ['group' => 'Akuntansi', 'icon' => '🧮', 'items' => [
                ['name' => 'COA (Chart of Accounts)', 'desc' => '~75 akun standar koperasi: Harta, Kewajiban, Modal, Pendapatan, Beban. Struktur tree parent-child. Saldo awal per akun.', 'screenshot' => '11-coa.png'],
                ['name' => 'Jurnal', 'desc' => 'Jurnal double-entry: manual & otomatis dari transaksi. Validasi balance Debit=Kredit. Multi-baris. Morphable ke transaksi sumber.', 'screenshot' => '12-jurnal.png'],
                ['name' => 'Kas & Bank', 'desc' => 'Multi-kas: bisa punya banyak kas tunai + rekening bank. Monitoring saldo real-time per kas.', 'screenshot' => '13-kas.png'],
                ['name' => 'Kas Opname', 'desc' => 'Pencocokan fisik kas dengan sistem. Auto-hitung selisih. Auto-jurnal selisih kas.', 'screenshot' => 'opname.png'],
                ['name' => 'Rekonsiliasi Bank', 'desc' => 'Cocokkan mutasi rekening koran dengan catatan kas bank. Tandai transaksi clear. Hitung saldo unreconciled.', 'screenshot' => 'rekonsiliasi.png'],
                ['name' => 'Anggaran', 'desc' => 'Buat anggaran per akun per bulan. Bandingkan realisasi vs anggaran. Monitor variance dalam persen.', 'screenshot' => 'anggaran.png'],
                ['name' => 'Periode Akuntansi', 'desc' => 'Buka/tutup periode akuntansi bulanan & tahunan. Transaksi hanya bisa di periode terbuka.', 'screenshot' => 'periode.png'],
                ['name' => 'Payment Provider', 'desc' => 'Konfigurasi payment gateway: Redirect (Midtrans/Xendit), QRIS, Virtual Account. Dynamic — user input API key sendiri.', 'screenshot' => 'payment.png'],
            ]],
            ['group' => 'SHU & RAT', 'icon' => '🎂', 'items' => [
                ['name' => 'SHU Perhitungan', 'desc' => 'Hitung SHU otomatis: pendapatan - beban. Alokasi: jasa modal, jasa usaha, cadangan, pengurus, pendidikan. Distribusi per anggota.', 'screenshot' => '14-shu.png'],
                ['name' => 'RAT', 'desc' => 'Rapat Anggota Tahunan: tanggal, lokasi, agenda, quorum, kehadiran, notulen, keputusan. Status: rencana → berlangsung → selesai.', 'screenshot' => 'rat.png'],
            ]],
            ['group' => 'HR & Asset', 'icon' => '🧊', 'items' => [
                ['name' => 'Karyawan', 'desc' => 'Data karyawan: NIP, nama, jabatan, departemen. 4 tab: Data Karyawan, Masa Kerja, Penggajian, Identitas & Bank. Upload foto.', 'screenshot' => '15-karyawan.png'],
                ['name' => 'Gaji', 'desc' => 'Payroll bulanan: hitung gaji pokok + tunjangan - potongan. Auto-jurnal beban gaji. Slip gaji PDF.', 'screenshot' => 'gaji.png'],
                ['name' => 'Aset Tetap', 'desc' => 'Manajemen aset: tanah, bangunan, kendaraan, peralatan. Penyusutan otomatis (garis lurus/saldo menurun). Mapping COA.', 'screenshot' => 'asset.png'],
            ]],
            ['group' => 'Asuransi', 'icon' => '🛡️', 'items' => [
                ['name' => 'Produk Asuransi', 'desc' => 'Master produk asuransi: jiwa, kebakaran, kendaraan, dll. Rate premi per produk.', 'screenshot' => 'asuransi-produk.png'],
                ['name' => 'Polis Asuransi', 'desc' => 'Data polis: anggota, produk, pinjaman terkait, nilai pertanggungan, premi, masa berlaku.', 'screenshot' => 'asuransi-polis.png'],
                ['name' => 'Klaim Asuransi', 'desc' => 'Proses klaim: polis, tanggal kejadian, nilai klaim, status (diajukan → disetujui → dibayar/ditolak).', 'screenshot' => 'asuransi-klaim.png'],
            ]],
            ['group' => 'Laporan', 'icon' => '📊', 'items' => [
                ['name' => 'Laporan ODS Kemenkop', 'desc' => 'Laporan format standar Kemenkop UKM: keanggotaan, simpanan, pinjaman, NPL. Filter tahun & bulan. Export Excel.', 'screenshot' => 'laporan-ods.png'],
                ['name' => 'Laporan Keuangan', 'desc' => 'Neraca, Laba/Rugi, Arus Kas. Filter periode & cabang. Download PDF profesional. Export Excel.', 'screenshot' => '16-laporan-keuangan.png'],
            ]],
            ['group' => 'Pengaturan', 'icon' => '⚙️', 'items' => [
                ['name' => 'User & RBAC', 'desc' => 'Manajemen user dengan 8 role + 150+ permission granular. Spatie Permission — bisa assign role per user.', 'screenshot' => '17-users.png'],
                ['name' => 'Tenant & Cabang', 'desc' => 'Konfigurasi tenant koperasi: nama, badan hukum, NPWP, akta. Multi-cabang support.', 'screenshot' => 'tenant.png'],
                ['name' => 'Numbering Setting', 'desc' => 'Atur format nomor otomatis: anggota, simpanan, pinjaman, penjualan, pembelian. Format: PREFIX/TAHUN/BULAN/COUNTER.', 'screenshot' => 'numbering.png'],
                ['name' => 'Tagihan Master', 'desc' => 'Master jenis tagihan: iuran wajib, iuran sukarela, iuran khusus. Atur nominal default & COA.', 'screenshot' => 'tagihan-master.png'],
                ['name' => 'Notifikasi Template', 'desc' => 'Template notifikasi WhatsApp & Email: reminder angsuran, konfirmasi setoran, approval pinjaman.', 'screenshot' => 'notifikasi.png'],
                ['name' => 'Activity Log', 'desc' => 'Audit trail semua aksi user: create, update, delete. Siapa melakukan apa, kapan, dari IP mana.', 'screenshot' => 'activity-log.png'],
                ['name' => 'Pengaturan Sistem', 'desc' => 'Setting global: WhatsApp gateway (Fonnte/Wablas), persentase SHU, POS settings.', 'screenshot' => 'pengaturan.png'],
            ]],
        ];
    }

    protected function tutorial(): array
    {
        return [
            [
                'phase' => 'Fase 1: Setup Awal Koperasi',
                'steps' => [
                    ['num' => 1, 'title' => 'Daftar Tenant Koperasi', 'detail' => 'Buka menu Pengaturan → Tenant. Isi nama koperasi, badan hukum, NPWP, akta pendirian, alamat, telp, email. Pilih mode operasi: Konvensional, Syariah, atau Dual. Simpan.'],
                    ['num' => 2, 'title' => 'Tambah Cabang (Opsional)', 'detail' => 'Jika koperasi punya beberapa unit pelayanan, buka Pengaturan → Tenant → klik nama tenant → tambah Cabang. Isi kode, nama, alamat, tipe (pusat/cabang).'],
                    ['num' => 3, 'title' => 'Setup COA (Chart of Accounts)', 'detail' => 'Buka menu Akuntansi → COA. Seeder sudah menyediakan ~75 akun standar. Verifikasi struktur: 1xxxxx Harta, 2xxxxx Kewajiban, 3xxxxx Modal, 4xxxxx Pendapatan, 5xxxxx Beban. Tambahkan akun khusus sesuai kebutuhan.'],
                    ['num' => 4, 'title' => 'Buat Kas & Bank', 'detail' => 'Buka Akuntansi → Kas → New. Buat minimal 1 kas tunai (misal "Kas Utama") dan 1 rekening bank (misal "Bank BRI"). Pilih COA yang sesuai. Set saldo awal sesuai kondisi riil.'],
                    ['num' => 5, 'title' => 'Atur Numbering Setting', 'detail' => 'Buka Pengaturan → Numbering Setting. Atur format nomor untuk: anggota (contoh: AGT/{Y}/{m}/0001), simpanan (SPN/{Y}/{m}/0001), pinjaman (PNJ/{Y}/{m}/0001), penjualan (INV/{Y}/{m}/0001).'],
                    ['num' => 6, 'title' => 'Konfigurasi Payment Provider', 'detail' => 'Buka Akuntansi → Payment Provider → New. Pilih api_format: redirect (Midtrans Snap), qris, atau virtual_account. Isi nama provider, base_url, api_key. Aktifkan.'],
                    ['num' => 7, 'title' => 'Setup Notifikasi WhatsApp', 'detail' => 'Buka Pengaturan → Pengaturan Sistem. Pilih WhatsApp driver: fonnte atau wablas. Isi API key & nomor sender. Klik Test untuk verifikasi. Notifikasi akan dikirim otomatis saat setoran, approval pinjaman, dan reminder angsuran.'],
                ],
            ],
            [
                'phase' => 'Fase 2: Master Data Anggota & Produk',
                'steps' => [
                    ['num' => 8, 'title' => 'Daftarkan Anggota Baru', 'detail' => 'Buka Keanggotaan → Anggota → New. Isi: NIK, nama lengkap, alamat, tempat/tanggal lahir, pekerjaan, penghasilan. Upload KTP + selfie. System auto-generate nomor anggota. Setelah save, buka tab "Ahli Waris" untuk tambah data penerima manfaat.'],
                    ['num' => 9, 'title' => 'Tambah Produk Simpanan', 'detail' => 'Buka Simpan Pinjam → Produk Simpanan → New. Seeder sudah buat 6 default. Sesuaikan: setoran minimum, saldo minimum, bunga per tahun, metode bunga (saldo_harian/saldo_rata2/saldo_akhir). Pilih COA simpanan dan COA bunga. Untuk syariah, atur nisbah anggota & koperasi.'],
                    ['num' => 10, 'title' => 'Tambah Produk Pinjaman', 'detail' => 'Buka Simpan Pinjam → Produk Pinjaman → New. Seeder sudah buat 9 default. Sesuaikan: plafon min/max, bunga/margin per tahun, tenor min/max, biaya admin. Pilih COA pokok, COA bunga, COA denda. Untuk syariah, pilih akad_type.'],
                    ['num' => 11, 'title' => 'Input Data Toko (Optional)', 'detail' => 'Jika punya unit toko: buka Toko & Unit Usaha → Kategori → Tambah. Lalu Supplier → Tambah. Lalu Satuan → Tambah. Terakhir Barang → Tambah: isi kode, nama, pilih kategori, supplier, satuan, harga beli, harga jual, stok awal.'],
                    ['num' => 12, 'title' => 'Setup Unit Jasa & Produsen (Optional)', 'detail' => 'Unit Jasa: buka Toko & Unit Usaha → Unit Jasa Layanan → Tambah. Isi kode, nama, tarif, satuan. Unit Produsen: buka Unit Produsen Komoditi → Tambah. Isi kode, nama, jenis, satuan, harga beli & jual default.'],
                ],
            ],
            [
                'phase' => 'Fase 3: Transaksi Simpanan',
                'steps' => [
                    ['num' => 13, 'title' => 'Setor Simpanan Anggota', 'detail' => 'Buka Simpan Pinjam → Simpanan → klik nama anggota → Action: Setor. Pilih produk simpanan (Pokok/Wajib/Sukarela), masukkan jumlah, pilih metode bayar (tunai/transfer). System auto-generate nomor transaksi + jurnal: Kas Debit, Simpanan Kredit. Cetak kuitansi PDF.'],
                    ['num' => 14, 'title' => 'Tarik Simpanan', 'detail' => 'Buka Simpan Pinjam → Simpanan → klik anggota → Action: Tarik. Hanya simpanan sukarela yang bisa ditarik (pokok & wajib tidak). System cek saldo mencukupi. Auto-jurnal: Simpanan Debit, Kas Kredit.'],
                    ['num' => 15, 'title' => 'Blokir Simpanan (Jaminan)', 'detail' => 'Buka Simpan Pinjam → Simpanan Blokir → New. Pilih simpanan anggota, masukkan jumlah yang diblokir. Saldo terblokir tidak bisa ditarik. Biasanya untuk jaminan pinjaman. Lepas blokir saat pinjaman lunas.'],
                    ['num' => 16, 'title' => 'Transfer Antar Simpanan', 'detail' => 'Buka Simpan Pinjam → Simpanan → klik anggota → Action: Transfer. Pindahkan dana antar produk simpanan dalam 1 anggota (contoh: Sukarela → Berjangka). Auto-jurnal dua sisi.'],
                ],
            ],
            [
                'phase' => 'Fase 4: Transaksi Pinjaman',
                'steps' => [
                    ['num' => 17, 'title' => 'Pengajuan Pinjaman', 'detail' => 'Buka Simpan Pinjam → Pinjaman → New. Pilih anggota (cari nama/NIK), pilih produk pinjaman, masukkan jumlah plafon & tenor. System auto-hitung angsuran via kalkulator (Flat/Efektif/Anuitas/Murabahah/dll). Upload jaminan (BPKB, sertifikat) di tab Jaminan. Submit — status: Pengajuan.'],
                    ['num' => 18, 'title' => 'Approval Pinjaman', 'detail' => 'Login sebagai Manajer. Dashboard akan menampilkan widget "Pending Approvals". Buka Pinjaman → klik pengajuan → Review data anggota, riwayat pinjaman, saldo simpanan, kolektabilitas. Klik Approve atau Reject dengan alasan.'],
                    ['num' => 19, 'title' => 'Akad & Cetak Kontrak', 'detail' => 'Setelah approve, buka Pinjaman → klik pinjaman → Action: Akad. Pilih tanggal akad. System generate kontrak PDF lengkap dengan jadwal angsuran, tanda tangan digital, dan materai. Cetak atau download.'],
                    ['num' => 20, 'title' => 'Pencairan Dana', 'detail' => 'Setelah akad, buka Pinjaman → Action: Cairkan. Pilih kas sumber dana. System auto-jurnal multi-baris: Piutang Debit, Pendapatan Admin Kredit, Asuransi Kredit, Kas Kredit. Status berubah menjadi Aktif.'],
                    ['num' => 21, 'title' => 'Pembayaran Angsuran', 'detail' => 'Buka Pinjaman → klik pinjaman → Action: Bayar. Masukkan jumlah pembayaran. System auto-alokasi: pokok → bunga → denda. Pilih metode bayar (tunai/transfer). Cetak slip pembayaran PDF. Kolektabilitas auto-update.'],
                    ['num' => 22, 'title' => 'Pelunasan Dipercepat', 'detail' => 'Buka Pinjaman → Action: Pelunasan. System hitung sisa pokok + bunga berjalan (bunga masa depan tidak dihitung). Generate surat lunas. Lepas jaminan & buka blokir simpanan. Status: Lunas.'],
                    ['num' => 23, 'title' => 'Restrukturisasi Pinjaman', 'detail' => 'Jika anggota kesulitan bayar, buka Simpan Pinjam → Restrukturisasi → New. Pilih pinjaman. Atur ulang: perpanjang tenor, grace period, atau konversi akad. System generate jadwal baru. History restrukturisasi tercatat.'],
                ],
            ],
            [
                'phase' => 'Fase 5: Operasional Toko & Unit Usaha',
                'steps' => [
                    ['num' => 24, 'title' => 'Penjualan Tunai (POS Kasir)', 'detail' => 'Login sebagai Kasir. Buka Toko & Unit Usaha → Penjualan → New. Pilih barang (repeater — bisa tambah banyak item), auto-hitung total. Kasir bisa atur diskon, tambah pajak. Pilih metode bayar: tunai / transfer / QRIS / potong simpanan. System hitung kembalian otomatis. Cetak struk thermal 58mm/80mm. Auto-jurnal: Kas Debit, Penjualan Kredit, HPP Debit, Persediaan Kredit.'],
                    ['num' => 25, 'title' => 'Pembelian ke Supplier', 'detail' => 'Buka Toko & Unit Usaha → Pembelian → New. Pilih supplier, input barang yang dibeli (repeater), harga beli, quantity. System auto-tambah stok barang. Jurnal: Persediaan Debit, Kas/Hutang Kredit.'],
                    ['num' => 26, 'title' => 'Order Jasa', 'detail' => 'Buka Toko & Unit Usaha → Unit Jasa Order → New. Pilih layanan & anggota. Input quantity. Status tracking: diproses → selesai → diambil. Pembayaran bisa via POS atau terpisah. Auto-jurnal saat status selesai.'],
                    ['num' => 27, 'title' => 'Setoran Produsen', 'detail' => 'Buka Toko & Unit Usaha → Unit Produsen Setoran → New. Pilih anggota produsen & komoditi. Input quantity, grade kualitas, harga aktual. System hitung total. Jurnal: Persediaan Debit, Hutang Anggota Kredit. Pembayaran ke anggota via Kas atau potong pinjaman.'],
                ],
            ],
            [
                'phase' => 'Fase 6: Akuntansi & Keuangan',
                'steps' => [
                    ['num' => 28, 'title' => 'Input Jurnal Manual', 'detail' => 'Buka Akuntansi → Jurnal → New. Untuk transaksi di luar auto-jurnal. Input double-entry: pilih COA, masukkan debit/kredit. System validasi balance (total Debit = total Kredit). Set nomor referensi & deskripsi.'],
                    ['num' => 29, 'title' => 'Kas Opname Rutin', 'detail' => 'Buka Akuntansi → Kas Opname → New. Pilih kas, input saldo fisik (hasil hitung tunai). System hitung selisih dengan saldo sistem. Auto-jurnal selisih ke akun pendapatan/beban lain-lain.'],
                    ['num' => 30, 'title' => 'Rekonsiliasi Bank', 'detail' => 'Buka Akuntansi → Rekonsiliasi Bank → New. Pilih kas bank. Input data rekening koran. Cocokkan transaksi yang sudah clear di bank. System hitung saldo yang belum reconcile.'],
                    ['num' => 31, 'title' => 'Buat Anggaran Tahunan', 'detail' => 'Buka Akuntansi → Anggaran → New. Pilih tahun & COA. Input anggaran per bulan. Bandingkan realisasi vs anggaran. Monitor variance untuk kontrol keuangan.'],
                    ['num' => 32, 'title' => 'Tutup Buku Periode', 'detail' => 'Buka Akuntansi → Periode Akuntansi. Buka periode baru atau tutup periode lama. Transaksi hanya bisa di periode terbuka. Tutup buku bulanan & tahunan secara bertahap.'],
                ],
            ],
            [
                'phase' => 'Fase 7: SHU & RAT',
                'steps' => [
                    ['num' => 33, 'title' => 'Hitung SHU Tahunan', 'detail' => 'Buka SHU & RAT → SHU Perhitungan → New. Pilih tahun buku. System auto-hitung total SHU = total pendapatan - total beban. Atur persentase alokasi: jasa modal (simpanan), jasa usaha (pinjaman/transaksi), dana cadangan, dana pendidikan, dana pengurus, dana karyawan.'],
                    ['num' => 34, 'title' => 'Distribusi SHU ke Anggota', 'detail' => 'Klik Action: Distribusikan. System auto-hitung SHU per anggota berdasarkan proporsi simpanan & partisipasi pinjaman/transaksi. Hasil bisa di-export ke Excel untuk diumumkan saat RAT.'],
                    ['num' => 35, 'title' => 'Catat RAT', 'detail' => 'Buka SHU & RAT → RAT → New. Isi tahun buku, tanggal, lokasi. Input agenda rapat dengan repeater. Catat jumlah hadir & quorum. Tulis notulen & keputusan. Status: rencana → berlangsung → selesai.'],
                ],
            ],
            [
                'phase' => 'Fase 8: HR, Asset & Asuransi',
                'steps' => [
                    ['num' => 36, 'title' => 'Input Data Karyawan', 'detail' => 'Buka HR & Asset → Karyawan → New. Isi NIP, nama, jabatan, departemen. Upload foto. Tab Masa Kerja: tanggal masuk, status. Tab Penggajian: gaji pokok, tunjangan (key-value). Tab Identitas: NPWP, BPJS, rekening bank.'],
                    ['num' => 37, 'title' => 'Proses Gaji Bulanan', 'detail' => 'Buka HR & Asset → Gaji → New. Pilih karyawan & periode. System auto-load gaji pokok + tunjangan. Input potongan (BPJS, pinjaman, dll). Auto-jurnal: Beban Gaji Debit, Kas/Hutang Gaji Kredit.'],
                    ['num' => 38, 'title' => 'Tambah Aset Tetap', 'detail' => 'Buka HR & Asset → Aset → New. Isi kode, nama, kategori (tanah/bangunan/kendaraan/peralatan), tanggal perolehan, harga perolehan, nilai residu, umur ekonomis, metode susut. Pilih COA aset, COA susut, COA akumulasi. System auto-hitung penyusutan bulanan via cron.'],
                    ['num' => 39, 'title' => 'Buat Polis Asuransi', 'detail' => 'Buka Asuransi → Produk Asuransi → New (jika belum ada). Lalu Asuransi → Polis → New. Pilih produk, anggota, pinjaman terkait (jika asuransi jiwa kredit). Input nilai pertanggungan, premi, masa berlaku.'],
                    ['num' => 40, 'title' => 'Proses Klaim Asuransi', 'detail' => 'Buka Asuransi → Klaim → New. Pilih polis, tanggal kejadian, nilai klaim, kronologi. Status: diajukan → diproses → disetujui → dibayar. Upload dokumen pendukung.'],
                ],
            ],
            [
                'phase' => 'Fase 9: Laporan & Monitoring',
                'steps' => [
                    ['num' => 41, 'title' => 'Dashboard Koperasi', 'detail' => 'Login sebagai Admin. Dashboard menampilkan: NPL ratio, total simpanan, total pinjaman, anggota aktif, SHU berjalan. Grafik: tren simpanan, kolektabilitas (5 level OJK), financial health. Tabel: top anggota, upcoming payments, aktivitas terbaru. Widget berbeda per role (Kasir lihat transaksi hari ini, AO lihat pengajuan pending, Kolektor lihat jadwal tagihan).'],
                    ['num' => 42, 'title' => 'Laporan ODS Kemenkop', 'detail' => 'Buka Laporan → Laporan ODS. Pilih tahun & bulan. Tampilkan ringkasan: total anggota, simpanan, pinjaman, NPL. Export Excel format standar Kemenkop UKM untuk pelaporan ke dinas koperasi.'],
                    ['num' => 43, 'title' => 'Laporan Keuangan', 'detail' => 'Buka Laporan → Laporan Keuangan. Pilih periode & cabang. Lihat Neraca, Laba/Rugi, Arus Kas. Download PDF profesional. Export data ke Excel.'],
                    ['num' => 44, 'title' => 'Monitoring Kolektabilitas', 'detail' => 'System auto-update kolektabilitas harian via cron (5 level OJK): Lancar, Dalam Perhatian Khusus, Kurang Lancar, Diragukan, Macet. Dashboard menampilkan grafik distribusi. Console command `koperasi:update-kolektabilitas` jalan tiap jam 01:00.'],
                    ['num' => 45, 'title' => 'Portal Anggota', 'detail' => 'Anggota bisa akses di /portal. Login dengan email & password. Lihat dashboard: saldo total, pinjaman aktif. Menu: Simpanan (riwayat transaksi), Pinjaman (jadwal angsuran, history bayar), Profil (edit data diri), Pengajuan Pinjaman (form online), Setoran (upload bukti transfer). Mobile-friendly.'],
                ],
            ],
        ];
    }

    protected function features(): array
    {
        return [
            [
                'screenshot' => '02-dashboard.png', 'group' => 'Dashboard',
                'title' => 'Dashboard Real-Time dengan Widget Per-Role',
                'desc' => 'Dashboard premium menampilkan KPI utama koperasi: NPL ratio, total simpanan, total pinjaman, anggota aktif, SHU berjalan. 3 grafik interaktif (tren simpanan, kolektabilitas 5 level OJK, financial health). Widget berbeda otomatis per role — Kasir lihat transaksi hari ini, AO lihat pengajuan pending, Kolektor lihat jadwal tagihan.',
                'bullets' => ['10 widget dashboard real-time', 'Widget otomatis filter per role (DashboardWidgetFilter)', 'Polling 30 detik untuk data real-time', 'Grafik tren simpanan 12 bulan', 'Kolektabilitas OJK: Lancar s/d Macet', 'Financial health: CAR, ROA, BOPO, FDR'],
            ],
            [
                'screenshot' => '03-anggota-list.png', 'group' => 'Anggota',
                'title' => 'Manajemen Anggota — 5 Tab dalam 1 Halaman',
                'desc' => 'CRUD anggota dengan 5 tab informatif dalam 1 halaman: Data Pribadi (NIK, nama, alamat, pekerjaan, upload KTP & selfie), Ahli Waris (multiple), Simpanan (semua rekening + saldo), Pinjaman (semua pinjaman + status), Kartu Anggota (printable dengan QR Code). Nomor anggota auto-generate via Numbering Setting.',
                'bullets' => ['5 tab: Data Pribadi, Ahli Waris, Simpanan, Pinjaman, Kartu', 'Upload KTP + selfie via media library', 'Auto-generate nomor anggota', 'QR Code di kartu anggota (bisa scan untuk verifikasi)', 'Status log: aktif, non-aktif, keluar — dengan history'],
            ],
            [
                'screenshot' => '05-simpanan.png', 'group' => 'Simpanan',
                'title' => 'Simpanan: Setor, Tarik & Transfer dengan Auto-Jurnal',
                'desc' => 'Kelola 6 jenis simpanan: Pokok, Wajib, Sukarela, Berjangka, Wadiah (syariah), Mudharabah (syariah). Setor & tarik via action modal cepat tanpa pindah halaman. Auto-jurnal ke COA yang sesuai. Cetak kuitansi PDF. Simpanan berjangka dengan tenor & jatuh tempo otomatis.',
                'bullets' => ['6 produk default (Konvensional + Syariah)', 'Setor & tarik via action modal', 'Auto-jurnal: Kas Debit, Simpanan Kredit', 'Simpanan berjangka: tenor + jatuh tempo + bunga', 'Cetak kuitansi PDF profesional', 'Transfer antar produk dalam 1 anggota'],
            ],
            [
                'screenshot' => '07-pinjaman.png', 'group' => 'Pinjaman',
                'title' => 'Pinjaman: Full Lifecycle Pengajuan s/d Pelunasan',
                'desc' => 'Workflow pinjaman lengkap: Pengajuan → Approval → Akad → Pencairan → Pembayaran → Pelunasan. 9 kalkulator (3 konvensional + 6 syariah). Jadwal angsuran auto-generated. Reminder WhatsApp H-3 & H-1. Kolektabilitas auto-update harian. Restrukturisasi untuk kredit bermasalah.',
                'bullets' => ['9 kalkulator: Flat, Efektif, Anuitas + Murabahah, Mudharabah, Musyarakah, Ijarah, IMBT, Qardh', 'Workflow approval berjenjang (AO → Manajer → Admin)', 'Cetak kontrak PDF + slip pembayaran', 'Reminder WhatsApp otomatis H-3 & H-1', 'Denda auto-calculate harian', 'Restrukturisasi: tenor, grace period, konversi akad'],
            ],
            [
                'screenshot' => '10-toko-penjualan.png', 'group' => 'POS',
                'title' => 'POS Kasir: Penjualan Toko dengan Touch-Friendly UI',
                'desc' => 'Interface kasir untuk penjualan toko: pilih barang via repeater (bisa tambah banyak item sekaligus), auto-hitung total, diskon, pajak, dan kembalian. 6 metode bayar: tunai, transfer, QRIS, virtual account, potong simpanan, kredit anggota. Cetak struk thermal 58mm & 80mm. Auto-jurnal penjualan + HPP.',
                'bullets' => ['Repeater items: tambah banyak barang dalam 1 transaksi', '6 metode bayar (tunai, transfer, QRIS, VA, simpanan, kredit)', 'Auto-hitung total, diskon, pajak, kembalian', 'Cetak struk thermal 58mm & 80mm', 'Auto-jurnal: Kas Debit, Penjualan Kredit, HPP', 'Touch-friendly — tombol besar 44px'],
            ],
            [
                'screenshot' => '12-jurnal.png', 'group' => 'Akuntansi',
                'title' => 'Akuntansi Double-Entry dengan Jurnal Otomatis',
                'desc' => 'COA ~75 akun standar koperasi (struktur tree parent-child). Jurnal manual double-entry dengan validasi Debit = Kredit. Semua transaksi operasional (simpanan, pinjaman, penjualan, pembelian) auto-create jurnal via Observer + JurnalService. Multi-kas & multi-bank. Kas opname & rekonsiliasi bank.',
                'bullets' => ['COA 75+ akun: 1xxxxx s/d 5xxxxx', 'Jurnal double-entry: validasi Debit = Kredit', 'Auto-jurnal dari semua transaksi operasional', 'Multi-kas + multi-bank', 'Kas opname: auto-hitung selisih', 'Rekonsiliasi bank: cocokkan dengan rekening koran'],
            ],
            [
                'screenshot' => '14-shu.png', 'group' => 'SHU',
                'title' => 'SHU Otomatis: Hitung → Alokasi → Distribusi per Anggota',
                'desc' => 'Hitung Sisa Hasil Usaha otomatis per tahun buku. Alokasi sesuai AD/ART: jasa modal (simpanan anggota), jasa usaha (partisipasi pinjaman), dana cadangan, dana pendidikan, dana pengurus, dana karyawan. Distribusi per anggota dengan perhitungan proporsional. Export Excel untuk pengumuman RAT.',
                'bullets' => ['Auto-hitung total SHU dari pendapatan - beban', 'Alokasi SHU 7 komponen (modal, usaha, cadangan, dll)', 'Distribusi per anggota otomatis', 'Export Excel untuk RAT', 'Mapping COA untuk jurnal distribusi'],
            ],
            [
                'screenshot' => '02-dashboard.png', 'group' => 'Scheduler',
                'title' => '11 Scheduled Job Otomatis',
                'desc' => 'System menjalankan 11 cron job otomatis: update kolektabilitas (setiap jam 01:00), generate denda keterlambatan (01:30), bunga simpanan harian (00:15) & bulanan (tgl 1), penyusutan aset (tgl 1), backup database harian (03:00) & mingguan (Minggu 04:00), reminder angsuran H-3 (08:00) & H-1 (08:30).',
                'bullets' => ['Kolektabilitas: daily 01:00', 'Denda keterlambatan: daily 01:30', 'Bunga simpanan: harian 00:15 + bulanan tgl 1', 'Penyusutan aset: tgl 1', 'Backup database: daily + weekly', 'Reminder WhatsApp: H-3 & H-1'],
            ],
            [
                'screenshot' => '17-users.png', 'group' => 'Keamanan',
                'title' => 'RBAC 8 Role + 150 Permission + Audit Trail Lengkap',
                'desc' => '8 role dengan permission granular: Super Admin, Admin, Manajer, Kasir, AO (Account Officer), Kolektor, Pengawas, Akuntan. 150+ permission spesifik per modul (view, create, update, delete, approve, export, print). Activity log mencatat semua aksi user: siapa, apa, kapan, dari IP mana. License pairing v3 untuk proteksi source code.',
                'bullets' => ['8 role: Super Admin s/d Akuntan', '150+ permission granular per modul', 'Activity log: audit trail semua aksi', 'Multi-tenant ready: tenant_id di semua tabel', 'License pairing v3 whitelabel.co.id', 'API Sanctum untuk mobile app'],
            ],
        ];
    }
}
