<?php

namespace App\Http\Controllers;

class DocsController extends Controller
{
    protected function demoAccounts(): array
    {
        return [
            ['role' => 'Super Admin',  'email' => 'admin@koperasi.local',     'password' => 'admin123', 'scope' => 'Semua fitur + settings + license + tenant'],
            ['role' => 'Manajer',      'email' => 'manager@koperasi.local',   'password' => 'admin123', 'scope' => 'View semua, approve pinjaman, export & print laporan'],
            ['role' => 'Kasir',        'email' => 'kasir@koperasi.local',     'password' => 'admin123', 'scope' => 'Simpanan, kas, pembayaran pinjaman, POS, tagihan'],
            ['role' => 'AO',           'email' => 'ao@koperasi.local',        'password' => 'admin123', 'scope' => 'View anggota, kelola pinjaman (pengajuan s/d akad)'],
            ['role' => 'Kolektor',     'email' => 'kolektor@koperasi.local',  'password' => 'admin123', 'scope' => 'View anggota, view & update pembayaran pinjaman'],
            ['role' => 'Pengawas',     'email' => 'pengawas@koperasi.local',  'password' => 'admin123', 'scope' => 'View-only semua modul + akses laporan'],
            ['role' => 'Akuntan',      'email' => 'akuntan@koperasi.local',   'password' => 'admin123', 'scope' => 'Jurnal, COA, laporan keuangan, setting'],
            ['role' => 'Anggota Demo', 'email' => 'anggota1@demo.local',      'password' => 'anggota123', 'scope' => 'Portal anggota: simpanan, pinjaman, setoran, PPOB, voting'],
        ];
    }

    protected function menuStructure(): array
    {
        return [
            ['group' => 'Keanggotaan', 'icon' => '👥', 'items' => [
                ['name' => 'Anggota', 'desc' => 'CRUD anggota + 5 tab: Data Pribadi, Ahli Waris, Simpanan, Pinjaman, Kartu. Upload KTP & foto. Cetak kartu QR.', 'screenshot' => '03-anggota-list.png'],
            ]],
            ['group' => 'Simpan Pinjam', 'icon' => '💰', 'items' => [
                ['name' => 'Produk Simpanan', 'desc' => '6 produk: Pokok, Wajib, Sukarela, Berjangka, Wadiah, Mudharabah. Atur bunga & COA.', 'screenshot' => '04-produk-simpanan.png'],
                ['name' => 'Simpanan', 'desc' => 'Setor & tarik auto-jurnal. Cetak kuitansi PDF. Transfer antar simpanan.', 'screenshot' => '05-simpanan.png'],
                ['name' => 'Simpanan Blokir', 'desc' => 'Blokir saldo jaminan pinjaman. Lepas blokir saat lunas.', 'screenshot' => 'blokir.png'],
                ['name' => 'Produk Pinjaman', 'desc' => '9 produk: Flat, Efektif, Anuitas + 6 syariah. Atur margin & COA.', 'screenshot' => '06-produk-pinjaman.png'],
                ['name' => 'Pinjaman', 'desc' => 'Pengajuan → Approval → Akad → Cair → Bayar. 9 kalkulator.', 'screenshot' => '07-pinjaman.png'],
                ['name' => 'Restrukturisasi', 'desc' => 'Perpanjang tenor, grace period, konversi akad.', 'screenshot' => 'restruktur.png'],
                ['name' => 'Iuran & Tagihan', 'desc' => 'Tagihan anggota: iuran wajib/sukarela. Status bayar.', 'screenshot' => '08-tagihan.png'],
            ]],
            ['group' => 'Toko & Unit Usaha', 'icon' => '🛒', 'items' => [
                ['name' => 'Toko Barang', 'desc' => 'Master barang: kode, nama, kategori, supplier, satuan, harga, stok.', 'screenshot' => '09-toko-barang.png'],
                ['name' => 'Toko Kategori', 'desc' => 'Kategori tree (parent-child).', 'screenshot' => 'kategori.png'],
                ['name' => 'Toko Supplier', 'desc' => 'Data supplier + tracking hutang.', 'screenshot' => 'supplier.png'],
                ['name' => 'Toko Satuan', 'desc' => 'Satuan barang: kg, liter, pcs, pack, dus.', 'screenshot' => 'satuan.png'],
                ['name' => 'Toko Penjualan (POS)', 'desc' => 'POS Kasir: repeater item, 6 metode bayar, cetak struk thermal.', 'screenshot' => '10-toko-penjualan.png'],
                ['name' => 'Toko Pembelian', 'desc' => 'Beli dari supplier: auto-tambah stok, jurnal otomatis.', 'screenshot' => 'pembelian.png'],
                ['name' => 'Unit Jasa Layanan', 'desc' => 'Master layanan jasa: fotokopi, laundry, dll.', 'screenshot' => 'jasa.png'],
                ['name' => 'Unit Jasa Order', 'desc' => 'Order jasa: tracking status s/d selesai + bayar.', 'screenshot' => 'jasa-order.png'],
                ['name' => 'Unit Produsen Komoditi', 'desc' => 'Master komoditi: pertanian, peternakan, perikanan.', 'screenshot' => 'komoditi.png'],
                ['name' => 'Unit Produsen Setoran', 'desc' => 'Setoran hasil produksi: timbang, grading, jurnal.', 'screenshot' => 'setoran.png'],
            ]],
            ['group' => 'Akuntansi', 'icon' => '🧮', 'items' => [
                ['name' => 'COA', 'desc' => '~75 akun standar koperasi. Struktur tree.', 'screenshot' => '11-coa.png'],
                ['name' => 'Jurnal', 'desc' => 'Double-entry manual & otomatis. Validasi balance.', 'screenshot' => '12-jurnal.png'],
                ['name' => 'Kas & Bank', 'desc' => 'Multi-kas: tunai + bank. Saldo real-time.', 'screenshot' => '13-kas.png'],
                ['name' => 'Kas Opname', 'desc' => 'Cocok fisik vs sistem. Auto-selisih.', 'screenshot' => 'opname.png'],
                ['name' => 'Rekonsiliasi Bank', 'desc' => 'Cocokkan rekening koran.', 'screenshot' => 'rekonsiliasi.png'],
                ['name' => 'Anggaran', 'desc' => 'Anggaran vs realisasi. Monitor variance.', 'screenshot' => 'anggaran.png'],
                ['name' => 'Periode Akuntansi', 'desc' => 'Buka/tutup buku bulanan & tahunan.', 'screenshot' => 'periode.png'],
                ['name' => 'Payment Provider', 'desc' => 'Gateway: Redirect, QRIS, Virtual Account.', 'screenshot' => 'payment.png'],
            ]],
            ['group' => 'SHU & RAT', 'icon' => '🎂', 'items' => [
                ['name' => 'SHU Perhitungan', 'desc' => 'Hitung SHU → Alokasi → Distribusi per anggota.', 'screenshot' => '14-shu.png'],
                ['name' => 'RAT', 'desc' => 'Rapat Anggota Tahunan: agenda, quorum, notulen.', 'screenshot' => 'rat.png'],
            ]],
            ['group' => 'HR & Asset', 'icon' => '🧊', 'items' => [
                ['name' => 'Karyawan', 'desc' => '4 tab: Data, Masa Kerja, Penggajian, Identitas Bank.', 'screenshot' => '15-karyawan.png'],
                ['name' => 'Gaji', 'desc' => 'Payroll: gaji + tunjangan - potongan. Auto-jurnal.', 'screenshot' => 'gaji.png'],
                ['name' => 'Aset Tetap', 'desc' => 'Aset + penyusutan otomatis bulanan. COA mapping.', 'screenshot' => 'asset.png'],
            ]],
            ['group' => 'Asuransi', 'icon' => '🛡️', 'items' => [
                ['name' => 'Produk Asuransi', 'desc' => 'Master produk asuransi + rate premi.', 'screenshot' => 'asuransi-produk.png'],
                ['name' => 'Polis Asuransi', 'desc' => 'Data polis: anggota, pinjaman, pertanggungan.', 'screenshot' => 'asuransi-polis.png'],
                ['name' => 'Klaim Asuransi', 'desc' => 'Proses klaim: ajukan → setujui → bayar.', 'screenshot' => 'asuransi-klaim.png'],
            ]],
            ['group' => 'Laporan', 'icon' => '📊', 'items' => [
                ['name' => 'Laporan ODS Kemenkop', 'desc' => 'Format standar Kemenkop UKM. Export Excel.', 'screenshot' => 'laporan-ods.png'],
                ['name' => 'Laporan Keuangan', 'desc' => 'Neraca, Laba/Rugi, Arus Kas. PDF + Excel.', 'screenshot' => '16-laporan-keuangan.png'],
            ]],
            ['group' => 'Pengaturan', 'icon' => '⚙️', 'items' => [
                ['name' => 'User & RBAC', 'desc' => '8 role + 150 permission. Spatie Permission.', 'screenshot' => '17-users.png'],
                ['name' => 'Tenant & Cabang', 'desc' => 'Konfigurasi koperasi + multi-cabang.', 'screenshot' => 'tenant.png'],
                ['name' => 'Numbering Setting', 'desc' => 'Format nomor otomatis: AGT/{Y}/{m}/0001.', 'screenshot' => 'numbering.png'],
                ['name' => 'Tagihan Master', 'desc' => 'Master jenis iuran: wajib, sukarela, khusus.', 'screenshot' => 'tagihan-master.png'],
                ['name' => 'Notifikasi Template', 'desc' => 'Template WA & Email notifikasi.', 'screenshot' => 'notifikasi.png'],
                ['name' => 'Activity Log', 'desc' => 'Audit trail: siapa, apa, kapan.', 'screenshot' => 'activity-log.png'],
                ['name' => 'Pengaturan Sistem', 'desc' => 'WA gateway, SHU %, POS settings.', 'screenshot' => 'pengaturan.png'],
            ]],
        ];
    }

    protected function tutorial(): array
    {
        return [
            [
                'phase' => 'Fase 1: Setup Awal',
                'steps' => [
                    ['title' => 'Konfigurasi Tenant & Cabang', 'menu' => 'Pengaturan → Tenant → New', 'detail' => 'Isi nama koperasi, alamat, NPWP, logo. Tambahkan cabang jika koperasi memiliki beberapa unit pelayanan. Tenant adalah unit organisasi tertinggi.'],
                    ['title' => 'Import / Buat COA (Chart of Accounts)', 'menu' => 'Akuntansi → COA', 'detail' => 'Seeder menyediakan ~75 akun standar koperasi (1xxxxx Harta, 2xxxxx Kewajiban, 3xxxxx Modal, 4xxxxx Pendapatan, 5xxxxx Beban). Tambahkan akun khusus sesuai kebutuhan koperasi.'],
                    ['title' => 'Setup Kas & Bank', 'menu' => 'Akuntansi → Kas → New', 'detail' => 'Buat minimal 1 kas tunai dan 1 rekening bank. Ini diperlukan untuk semua transaksi simpanan, pinjaman, dan penjualan. Set saldo awal sesuai kondisi riil.'],
                    ['title' => 'Konfigurasi Numbering Setting', 'menu' => 'Pengaturan → Numbering Setting', 'detail' => 'Atur format nomor otomatis untuk: anggota, simpanan, pinjaman, penjualan, pembelian. Format: prefix + year + month + counter (contoh: AGT/2026/06/0001).'],
                ],
            ],
            [
                'phase' => 'Fase 2: Data Master',
                'steps' => [
                    ['title' => 'Daftarkan Anggota', 'menu' => 'Keanggotaan → Anggota → New', 'detail' => 'Isi data lengkap: NIK, nama, alamat, pekerjaan, penghasilan. Upload foto KTP + selfie. System auto-generate nomor anggota sesuai Numbering Setting. Tab "Ahli Waris" untuk data penerima manfaat.'],
                    ['title' => 'Tambah Produk Simpanan', 'menu' => 'Simpan Pinjam → Produk Simpanan → New', 'detail' => 'Buat produk simpanan: Pokok (wajib saat daftar), Wajib (bulanan), Sukarela (bebas), Berjangka (tenor tetap + bunga). Untuk syariah: Wadiah (titipan), Mudharabah (bagi hasil). Set bunga/bagi hasil per produk.'],
                    ['title' => 'Tambah Produk Pinjaman', 'menu' => 'Simpan Pinjam → Produk Pinjaman → New', 'detail' => 'Konvensional: bunga flat/efektif/anuitas. Syariah: Murabahah (jual-beli), Mudharabah (bagi hasil), Musyarakah (patungan), Ijarah (sewa), Qardh (pinjaman kebajikan), Rahn (gadai). Set margin, tenor maks, biaya admin.'],
                    ['title' => 'Input Data Supplier & Barang Toko', 'menu' => 'Toko & Unit Usaha → Supplier / Barang / Kategori', 'detail' => 'Untuk unit pertokoan: tambah supplier, kategori barang, satuan, dan data barang. Set harga beli, harga jual, stok awal, dan barcode.'],
                    ['title' => 'Setup Unit Jasa & Produsen', 'menu' => 'Toko & Unit Usaha → Unit Jasa / Unit Produsen', 'detail' => 'Jika koperasi punya unit usaha lain: jasa (fotokopi, laundry, dll) atau produsen (hasil tani, peternakan). Daftarkan layanan/komoditi dengan harga dan satuan.'],
                ],
            ],
            [
                'phase' => 'Fase 3: Transaksi Simpanan',
                'steps' => [
                    ['title' => 'Setor Simpanan', 'menu' => 'Simpan Pinjam → Simpanan → Action: Setor', 'detail' => 'Pilih anggota, produk simpanan, jumlah setoran, metode bayar (tunai/transfer). System auto-generate nomor transaksi + jurnal otomatis (Kas Debit, Simpanan Kredit). Cetak kuitansi via PDF.'],
                    ['title' => 'Tarik Simpanan', 'menu' => 'Simpan Pinjam → Simpanan → Action: Tarik', 'detail' => 'Penarikan simpanan sukarela kapan saja (saldo mencukupi). Simpanan berjangka hanya bisa ditarik saat jatuh tempo. Auto-jurnal: Simpanan Debit, Kas Kredit.'],
                    ['title' => 'Blokir Simpanan', 'menu' => 'Simpan Pinjam → Simpanan Blokir → New', 'detail' => 'Blokir simpanan anggota (biasanya sebagai jaminan pinjaman). Saldo terblokir tidak bisa ditarik. Jumlah blokir otomatis dikurangi dari saldo tersedia. Buka blokir saat pinjaman lunas.'],
                    ['title' => 'Transfer Antar Simpanan', 'menu' => 'Simpan Pinjam → Simpanan → Action: Transfer', 'detail' => 'Pindahkan dana antar produk simpanan dalam 1 anggota (contoh: Sukarela → Berjangka). Auto-jurnal dua sisi.'],
                ],
            ],
            [
                'phase' => 'Fase 4: Transaksi Pinjaman',
                'steps' => [
                    ['title' => 'Pengajuan Pinjaman', 'menu' => 'Simpan Pinjam → Pinjaman → New', 'detail' => 'Pilih anggota, produk pinjaman, jumlah, tenor. System auto-hitung angsuran via 9 kalkulator (flat/efektif/anuitas + 6 syariah). Upload jaminan (BPKB, sertifikat). Status awal: Pengajuan.'],
                    ['title' => 'Approval Pinjaman', 'menu' => 'Pinjaman → Action: Approve', 'detail' => 'Manajer/Pengawas review pengajuan: cek saldo simpanan, riwayat pinjaman, kolektabilitas. Dapat approve atau reject dengan alasan. Widget "Pending Approvals" muncul di dashboard.'],
                    ['title' => 'Akad & Pencairan', 'menu' => 'Pinjaman → Action: Cairkan', 'detail' => 'Setelah approve → akad. System generate kontrak PDF. Pencairan dana: auto-jurnal multi-baris (Piutang Debit, Pendapatan Admin Kredit, Asuransi Kredit, Kas Kredit). Status: Aktif.'],
                    ['title' => 'Generate Jadwal Angsuran', 'menu' => 'Pinjaman → Tab: Jadwal', 'detail' => 'System auto-generate jadwal angsuran lengkap: tanggal jatuh tempo, pokok, bunga/margin, total. Tabel jadwal bisa di-export ke Excel. Reminder WhatsApp H-3 & H-1 otomatis.'],
                    ['title' => 'Pembayaran Angsuran', 'menu' => 'Simpan Pinjam → Pinjaman → Action: Bayar', 'detail' => 'Pilih pinjaman, masukkan jumlah bayar. System auto-alokasi ke pokok, bunga, dan denda (jika telat). Cetak slip pembayaran PDF. Auto-update kolektabilitas.'],
                    ['title' => 'Pelunasan Dipercepat', 'menu' => 'Pinjaman → Action: Pelunasan', 'detail' => 'Anggota bisa lunasi lebih awal. System hitung sisa pokok + bunga berjalan (tidak termasuk bunga masa depan). Generate surat lunas. Lepas jaminan & buka blokir simpanan.'],
                    ['title' => 'Restrukturisasi Pinjaman', 'menu' => 'Simpan Pinjam → Pinjaman Restrukturisasi', 'detail' => 'Untuk anggota yg kesulitan bayar: perpanjang tenor, grace period, atau konversi akad. Buat jadwal baru. History restrukturisasi tercatat.'],
                ],
            ],
            [
                'phase' => 'Fase 5: Toko & Unit Usaha',
                'steps' => [
                    ['title' => 'Penjualan Tunai (POS)', 'menu' => 'Toko & Unit Usaha → Toko Penjualan → New', 'detail' => 'Kasir bisa input penjualan: pilih barang (repeater items), auto-hitung total, pilih metode bayar (tunai, transfer, QRIS, virtual account). Cetak struk thermal 58mm/80mm. Auto-jurnal: Kas Debit, Penjualan Kredit, HPP.'],
                    ['title' => 'Pembelian ke Supplier', 'menu' => 'Toko & Unit Usaha → Toko Pembelian → New', 'detail' => 'Input barang masuk dari supplier. Auto-tambah stok. Jurnal: Persediaan Debit, Kas/Hutang Kredit.'],
                    ['title' => 'Order Jasa', 'menu' => 'Toko & Unit Usaha → Unit Jasa Order → New', 'detail' => 'Untuk unit jasa: catat order pelanggan (laundry, fotokopi, dll). Tracking status: diproses → selesai → diambil. Pembayaran via POS atau terpisah.'],
                    ['title' => 'Setoran Produsen', 'menu' => 'Toko & Unit Usaha → Unit Produsen Setoran → New', 'detail' => 'Untuk unit produsen: catat setoran hasil tani/peternakan dari anggota. Timbang, grade kualitas, hitung harga. Jurnal: Persediaan Debit, Hutang Anggota Kredit.'],
                ],
            ],
            [
                'phase' => 'Fase 6: Akuntansi',
                'steps' => [
                    ['title' => 'Input Jurnal Manual', 'menu' => 'Akuntansi → Jurnal → New', 'detail' => 'Untuk transaksi di luar auto-jurnal: input jurnal double-entry. System validasi balance (Debit = Kredit). Support multi-baris. Set tanggal, no referensi, deskripsi.'],
                    ['title' => 'Kas Opname', 'menu' => 'Akuntansi → Kas Opname → New', 'detail' => 'Pencocokan fisik kas dengan saldo sistem. Input saldo fisik → system hitung selisih. Auto-jurnal selisih kas ke akun pendapatan/beban lain-lain.'],
                    ['title' => 'Rekonsiliasi Bank', 'menu' => 'Akuntansi → Rekonsiliasi Bank → New', 'detail' => 'Cocokkan mutasi rekening koran dengan catatan kas bank. Tandai transaksi yg sudah clear. Hitung saldo yg belum reconcile.'],
                    ['title' => 'Buat Anggaran', 'menu' => 'Akuntansi → Anggaran → New', 'detail' => 'Set anggaran per akun per bulan. Bandingkan realisasi vs anggaran. Monitor variance.'],
                    ['title' => 'Tutup Buku Periode', 'menu' => 'Akuntansi → Periode Akuntansi', 'detail' => 'Buka/tutup periode akuntansi. Transaksi hanya bisa di periode terbuka. Tutup buku bulanan & tahunan.'],
                ],
            ],
            [
                'phase' => 'Fase 7: SHU & RAT',
                'steps' => [
                    ['title' => 'Hitung SHU', 'menu' => 'SHU & RAT → SHU Perhitungan → New', 'detail' => 'Pilih tahun buku. System auto-hitung total SHU dari pendapatan dikurangi beban. Alokasikan SHU sesuai AD/ART: jasa modal (simpanan), jasa usaha (pinjaman/transaksi), cadangan, dana pengurus, dana pendidikan.'],
                    ['title' => 'Distribusi SHU ke Anggota', 'menu' => 'SHU Perhitungan → Action: Distribusikan', 'detail' => 'System auto-hitung SHU per anggota berdasarkan proporsi simpanan dan partisipasi pinjaman/transaksi. Hasil bisa di-export Excel untuk diumumkan saat RAT.'],
                    ['title' => 'Catat RAT', 'menu' => 'SHU & RAT → RAT → New', 'detail' => 'Catat Rapat Anggota Tahunan: tanggal, lokasi, jumlah hadir, keputusan. Upload notulen & foto kegiatan. Status: direncanakan → selesai.'],
                ],
            ],
            [
                'phase' => 'Fase 8: Laporan & Monitoring',
                'steps' => [
                    ['title' => 'Dashboard Koperasi', 'menu' => 'Admin Panel → Dashboard', 'detail' => 'Dashboard premium real-time: NPL ratio, total simpanan, total pinjaman, anggota aktif, SHU tahun berjalan. Grafik tren simpanan, kolektabilitas, financial health. Tabel top anggota + upcoming payments.'],
                    ['title' => 'Laporan Neraca', 'menu' => 'Laporan → Laporan Keuangan → Neraca', 'detail' => 'Pilih periode (tanggal). System generate neraca: Aset, Kewajiban, Modal. Download PDF format profesional.'],
                    ['title' => 'Laporan Laba/Rugi', 'menu' => 'Laporan → Laporan Keuangan → Laba/Rugi', 'detail' => 'Pilih periode (bulanan/tahunan). Tampilkan pendapatan vs beban. Hitung sisa hasil usaha (SHU). Export PDF + Excel.'],
                    ['title' => 'Laporan Arus Kas', 'menu' => 'Laporan → Laporan Keuangan → Arus Kas', 'detail' => 'Arus kas operasional, investasi, pendanaan. Filter per cabang & kas/bank.'],
                    ['title' => 'Ekspor Data ke Excel', 'menu' => 'Laporan → Export Excel', 'detail' => 'Export data anggota, simpanan, pinjaman, dan jurnal ke format Excel untuk pelaporan ke dinas koperasi / ODS Kemenkop.'],
                    ['title' => 'Monitoring Kolektabilitas', 'menu' => 'Dashboard → Grafik Kolektabilitas + Console Command', 'detail' => 'System auto-update kolektabilitas harian (5 level OJK): Lancar, Dalam Perhatian Khusus, Kurang Lancar, Diragukan, Macet. Grafik & tabel real-time.'],
                ],
            ],
        ];
    }

    protected function features(): array
    {
        return [
            ['screenshot' => '02-dashboard.png', 'group' => 'Dashboard', 'title' => 'Dashboard Real-Time dengan Widget Per-Role',
             'description' => 'Dashboard premium menampilkan KPI utama: NPL, simpanan, pinjaman, anggota aktif, SHU. 3 grafik interaktif. Widget berbeda otomatis per role.',
             'bullets' => ['13 widget dashboard', 'Widget filter per role (DashboardWidgetFilter)', 'Polling 30 detik', 'Grafik tren simpanan 12 bulan', 'Kolektabilitas 5 level OJK', 'Financial health indicators']],
            ['screenshot' => '03-anggota-list.png', 'group' => 'Anggota', 'title' => 'Manajemen Anggota — 5 Tab dalam 1 Halaman',
             'description' => 'CRUD anggota: Data Pribadi, Ahli Waris, Simpanan, Pinjaman, Kartu Anggota dengan QR Code. Auto-generate nomor. Upload KTP & selfie.',
             'bullets' => ['5 tab informatif', 'Upload KTP + selfie', 'Auto-generate nomor anggota', 'QR Code di kartu', 'Status log history']],
            ['screenshot' => '05-simpanan.png', 'group' => 'Simpanan', 'title' => 'Simpanan: Setor, Tarik & Transfer Auto-Jurnal',
             'description' => '6 jenis simpanan. Setor & tarik via action modal. Auto-jurnal. Cetak kuitansi PDF. Transfer antar produk. Bunga harian/bulanan auto-cron.',
             'bullets' => ['6 produk (Konvensional + Syariah)', 'Setor/tarik via modal action', 'Auto-jurnal', 'Kuitansi PDF', 'Bunga auto-cron', 'Transfer antar produk']],
            ['screenshot' => '07-pinjaman.png', 'group' => 'Pinjaman', 'title' => 'Pinjaman: Full Lifecycle + 9 Kalkulator',
             'description' => 'Pengajuan → Approval → Akad → Cair → Bayar. 9 kalkulator (3 konvensional + 6 syariah). Jadwal auto. Reminder WhatsApp. Kolektabilitas.',
             'bullets' => ['9 kalkulator', 'Workflow approval', 'Kontrak + slip PDF', 'Reminder WhatsApp H-3 & H-1', 'Denda auto-calculate', 'Restrukturisasi']],
            ['screenshot' => '10-toko-penjualan.png', 'group' => 'POS', 'title' => 'POS Kasir: Touch-Friendly, 6 Metode Bayar',
             'description' => 'Repeater items. Auto-hitung total, diskon, pajak, kembalian. 6 metode bayar. Cetak struk thermal 58mm/80mm. Auto-jurnal penjualan + HPP.',
             'bullets' => ['Repeater items', '6 metode bayar', 'Auto-hitung kembalian', 'Struk thermal 58mm & 80mm', 'Auto-jurnal + HPP', 'Touch-friendly 44px']],
            ['screenshot' => '12-jurnal.png', 'group' => 'Akuntansi', 'title' => 'Akuntansi Double-Entry Otomatis',
             'description' => 'COA 75+ akun. Jurnal manual & otomatis. Validasi Debit=Kredit. Multi-kas. Kas opname. Rekonsiliasi bank. Periode akuntansi. Payment provider.',
             'bullets' => ['COA 75+ akun', 'Jurnal double-entry', 'Auto-jurnal dari transaksi', 'Multi-kas + bank', 'Kas opname', 'Rekonsiliasi bank']],
            ['screenshot' => '14-shu.png', 'group' => 'SHU', 'title' => 'SHU Otomatis: Hitung → Alokasi → Distribusi',
             'description' => 'Hitung SHU per tahun. Alokasi 7 komponen. Distribusi per anggota proporsional. Export Excel untuk RAT. Jurnal distribusi otomatis.',
             'bullets' => ['Auto-hitung SHU', '7 komponen alokasi', 'Distribusi per anggota', 'Export Excel', 'Jurnal distribusi']],
            ['screenshot' => '02-dashboard.png', 'group' => 'Scheduler', 'title' => '11 Cron Job Otomatis',
             'description' => 'Kolektabilitas, denda, bunga simpanan, penyusutan aset, backup database, reminder WhatsApp — semua berjalan otomatis via scheduler.',
             'bullets' => ['Kolektabilitas: 01:00', 'Denda: 01:30', 'Bunga simpanan: 00:15', 'Penyusutan aset: tgl 1', 'Backup: harian & mingguan', 'Reminder WA: H-3 & H-1']],
            ['screenshot' => '17-users.png', 'group' => 'Keamanan', 'title' => 'RBAC 8 Role + 150 Permission + Audit Trail',
             'description' => '8 role granular. 150+ permission per modul. Activity log semua aksi. Multi-tenant ready. API Sanctum. License pairing v3.',
             'bullets' => ['8 role: Super Admin s/d Akuntan', '150+ permission', 'Activity log', 'Multi-tenant ready', 'API Sanctum', 'License pairing v3']],
        ];
    }

    public function index()
    {
        $accounts = $this->demoAccounts();
        $menu = $this->menuStructure();
        $tutorial = $this->tutorial();
        $features = $this->features();

        $seoMeta = [
            'title' => 'Dokumentasi & Demo — KoperasiApp',
            'description' => 'Akses demo KoperasiApp, ikuti panduan langkah demi langkah, dan lihat semua fitur dengan screenshot. Software koperasi simpan pinjam + unit usaha.',
        ];

        return view('pseo.docs-index', compact(
            'accounts', 'menu', 'tutorial', 'features', 'seoMeta'
        ));
    }
}
