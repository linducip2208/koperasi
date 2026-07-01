<?php

/*
|--------------------------------------------------------------------------
| Programmatic SEO data for Koperasi SaaS
|--------------------------------------------------------------------------
|
| Tabel data statis untuk men-generate halaman pSEO. Diakses oleh
| ProgrammaticSeoController. Tambah/edit entry di sini → halaman baru
| otomatis terdaftar di sitemap.xml.
|
*/

return [

    /*
    | Kota — 514 kabupaten/kota seluruh Indonesia (dari pseo-kota.php).
    | File terpisah karena ukuran data besar; include di sini agar tetap 1 config.
    */
    'kota' => (function () {
        $file = __DIR__ . '/pseo-kota.php';
        return file_exists($file) ? (include $file)['kota'] ?? [] : [];
    })(),

    /*
    | Jenis Koperasi (sesuai UU No. 25 Tahun 1992 + UU No. 17 Tahun 2012).
    */
    'jenis' => [
        [
            'slug' => 'simpan-pinjam',
            'nama' => 'Koperasi Simpan Pinjam',
            'singkatan' => 'KSP',
            'tagline' => 'Layanan keuangan simpan-pinjam untuk anggota',
            'deskripsi' => 'Koperasi Simpan Pinjam (KSP) adalah jenis koperasi yang menjalankan kegiatan usaha tunggal: menerima simpanan anggota dan menyalurkannya kembali sebagai pinjaman dengan bunga yang lebih rendah daripada bank konvensional. KSP merupakan bentuk koperasi paling populer di Indonesia dengan lebih dari 80.000 unit aktif tersebar di seluruh kota.',
            'fitur_utama' => ['Simpanan Pokok, Wajib & Sukarela', 'Pinjaman dengan bunga flat/efektif', 'Angsuran bulanan otomatis', 'Asuransi pinjaman opsional', 'Laporan keuangan PSAK 27/ETAP'],
            'regulasi' => 'Permenkop No. 11/Per/M.KUKM/XII/2017 — Pedoman Pelaksanaan KSP/USP',
        ],
        [
            'slug' => 'serba-usaha',
            'nama' => 'Koperasi Serba Usaha',
            'singkatan' => 'KSU',
            'tagline' => 'Multi-layanan koperasi: simpan-pinjam + perdagangan + jasa',
            'deskripsi' => 'Koperasi Serba Usaha (KSU) adalah koperasi yang menjalankan beberapa unit usaha sekaligus — biasanya kombinasi simpan-pinjam, toko/perdagangan ritel, dan jasa (catering, transport, sewa). KSU memberi fleksibilitas terbesar untuk koperasi yang ingin berkembang melayani kebutuhan harian anggotanya secara menyeluruh.',
            'fitur_utama' => ['Multi-unit usaha (SP + Toko + Jasa)', 'Konsolidasi laporan per unit', 'Inventory & POS terintegrasi', 'Bagi hasil per unit usaha', 'Manajemen anggota terpusat'],
            'regulasi' => 'UU No. 25 Tahun 1992 — Pasal 16 ayat (1) jenis koperasi',
        ],
        [
            'slug' => 'syariah',
            'nama' => 'Koperasi Syariah',
            'singkatan' => 'KSPPS',
            'tagline' => 'Koperasi berbasis prinsip Islam — bagi hasil, bukan bunga',
            'deskripsi' => 'Koperasi Simpan Pinjam dan Pembiayaan Syariah (KSPPS) menjalankan operasi keuangan berdasar prinsip syariah Islam. Tidak ada bunga (riba); pendapatan diperoleh dari akad mudharabah (bagi hasil), murabahah (jual-beli dengan margin), ijarah (sewa), atau musyarakah (kemitraan modal). Cocok untuk komunitas muslim yang ingin layanan keuangan halal sesuai fatwa DSN-MUI.',
            'fitur_utama' => ['12 akad syariah lengkap (termasuk Wakalah, Kafalah, Hawalah, Rahn)', 'Margin & nisbah bagi hasil', 'Tabungan wadiah & mudharabah', 'Pembiayaan murabahah & ijarah', 'Audit DPS (Dewan Pengawas Syariah)'],
            'regulasi' => 'Permenkop No. 11/Per/M.KUKM/IX/2018 — KSPPS & USPPS Koperasi',
        ],
        [
            'slug' => 'pegawai-republik-indonesia',
            'nama' => 'Koperasi Pegawai Republik Indonesia',
            'singkatan' => 'KPRI',
            'tagline' => 'Koperasi khusus PNS dan ASN di lingkungan instansi pemerintah',
            'deskripsi' => 'Koperasi Pegawai Republik Indonesia (KPRI) didirikan di lingkungan kementerian, dinas, atau instansi pemerintah untuk melayani kebutuhan keuangan dan kesejahteraan PNS. Anggotanya tertutup (hanya pegawai instansi tersebut), simpanan dipotong langsung dari gaji, dan pinjaman bisa lebih besar karena tingkat default sangat rendah.',
            'fitur_utama' => ['Auto-debit dari gaji bulanan', 'Plafon pinjaman s/d 10x gaji', 'Integrasi data ASN/SIMPEG', 'Asuransi PHK & pensiun', 'Laporan ke instansi induk'],
            'regulasi' => 'Permendagri No. 21 Tahun 2018 — Pengelolaan KPRI di lingkungan pemerintah',
        ],
        [
            'slug' => 'karyawan',
            'nama' => 'Koperasi Karyawan',
            'singkatan' => 'Kopkar',
            'tagline' => 'Koperasi internal perusahaan swasta untuk karyawan',
            'deskripsi' => 'Koperasi Karyawan (Kopkar) adalah koperasi yang didirikan di lingkungan perusahaan swasta untuk melayani karyawan. Mirip KPRI tapi di sektor swasta — simpanan dipotong dari gaji via payroll perusahaan, dan koperasi sering menjalankan unit toko/kantin internal sebagai sumber tambahan SHU.',
            'fitur_utama' => ['Integrasi payroll perusahaan', 'Toko/kantin karyawan', 'Pinjaman talangan gaji', 'Voucher belanja anggota', 'Laporan ke HRD'],
            'regulasi' => 'Berinduk pada UU No. 25 Tahun 1992 — Koperasi Sekunder',
        ],
        [
            'slug' => 'pondok-pesantren',
            'nama' => 'Koperasi Pondok Pesantren',
            'singkatan' => 'Kopontren',
            'tagline' => 'Koperasi syariah di lingkungan pondok pesantren',
            'deskripsi' => 'Koperasi Pondok Pesantren (Kopontren) adalah koperasi syariah yang berkembang di lingkungan pesantren untuk melayani santri, ustadz, dan masyarakat sekitar. Kopontren biasanya menjalankan unit toko kebutuhan santri, simpan-pinjam syariah, dan kadang produksi (laundry, percetakan kitab, agribisnis pesantren).',
            'fitur_utama' => ['Toko kebutuhan santri', 'Simpanan wadiah santri', 'Pembiayaan murabahah peralatan', 'Bagi hasil sesuai syariah', 'Laporan keuangan dwi bahasa'],
            'regulasi' => 'Berinduk pada Permenkop No. 11/Per/M.KUKM/IX/2018 (KSPPS)',
        ],
    ],

    /*
    | Akad Syariah (12 akad utama yang didukung Filament ProdukPinjamanResource).
    */
    'akad' => [
        ['slug' => 'mudharabah',  'nama' => 'Mudharabah',  'kategori' => 'Bagi Hasil',           'ringkas' => 'Kerjasama modal-tenaga; pembagian keuntungan dengan nisbah disepakati.',
            'deskripsi' => 'Mudharabah adalah akad kerjasama antara shahibul maal (pemilik modal) dan mudharib (pengelola usaha). Modal 100% dari koperasi, pengelolaan oleh anggota. Keuntungan dibagi sesuai nisbah yang disepakati di awal (misal 60:40). Kerugian ditanggung pemilik modal, kecuali akibat kelalaian pengelola.',
            'rumus' => 'Bagi Hasil = (Pendapatan Bersih × Nisbah Anggota%) untuk anggota',
            'contoh' => 'Anggota minta modal usaha warung Rp 10jt. Nisbah 60:40. Untung Rp 2jt/bulan → anggota dapat Rp 1.2jt, koperasi Rp 800rb.',
            'fatwa' => 'Fatwa DSN-MUI No. 07/DSN-MUI/IV/2000'],
        ['slug' => 'musyarakah', 'nama' => 'Musyarakah', 'kategori' => 'Bagi Hasil',           'ringkas' => 'Kemitraan modal patungan; semua pihak menyertakan modal & tenaga.',
            'deskripsi' => 'Musyarakah adalah akad kemitraan di mana koperasi dan anggota sama-sama menyertakan modal dan tenaga untuk usaha bersama. Berbeda dengan mudharabah, di musyarakah anggota juga menyetor modal (misal 30%) dan koperasi 70%. Keuntungan dan kerugian ditanggung sesuai porsi modal.',
            'rumus' => 'Bagi Hasil = (Untung × Porsi Modal Pihak)',
            'contoh' => 'Usaha bengkel butuh modal Rp 50jt. Anggota setor Rp 15jt (30%), koperasi Rp 35jt (70%). Untung Rp 5jt → anggota Rp 1.5jt, koperasi Rp 3.5jt.',
            'fatwa' => 'Fatwa DSN-MUI No. 08/DSN-MUI/IV/2000'],
        ['slug' => 'murabahah',  'nama' => 'Murabahah',  'kategori' => 'Jual-Beli',            'ringkas' => 'Jual-beli dengan margin keuntungan diketahui kedua pihak.',
            'deskripsi' => 'Murabahah adalah akad jual-beli di mana koperasi membeli barang yang dibutuhkan anggota, lalu menjualnya kembali kepada anggota dengan harga pokok + margin yang disepakati. Anggota membayar secara cicilan. Akad murabahah paling sering dipakai untuk pembiayaan konsumtif (motor, alat rumah tangga).',
            'rumus' => 'Total Bayar = Harga Pokok + Margin (margin tetap, tidak berubah)',
            'contoh' => 'Anggota mau beli motor Rp 18jt. Koperasi beli motor itu, jual ke anggota Rp 21.6jt (margin 20%) cicilan 24 bulan.',
            'fatwa' => 'Fatwa DSN-MUI No. 04/DSN-MUI/IV/2000'],
        ['slug' => 'ijarah',     'nama' => 'Ijarah',     'kategori' => 'Sewa',                  'ringkas' => 'Sewa-menyewa atas barang atau jasa dengan upah tetap.',
            'deskripsi' => 'Ijarah adalah akad sewa-menyewa atas manfaat barang atau jasa dengan kompensasi (ujrah) yang disepakati. Koperasi membeli aset dan menyewakannya kepada anggota. Variasi populer: Ijarah Muntahiyyah Bittamlik (IMBT) — sewa yang berakhir dengan kepemilikan, mirip leasing syariah.',
            'rumus' => 'Total Bayar = Ujrah Bulanan × Tenor (kepemilikan tetap di koperasi atau berpindah di akhir)',
            'contoh' => 'Anggota sewa ruko untuk usaha. Koperasi beli ruko Rp 500jt, sewakan Rp 5jt/bulan untuk 5 tahun.',
            'fatwa' => 'Fatwa DSN-MUI No. 09/DSN-MUI/IV/2000'],
        ['slug' => 'ijarah-mb',   'nama' => 'Ijarah Muntahiya Bittamlik', 'kategori' => 'Sewa',  'ringkas' => 'Sewa yang diakhiri dengan kepemilikan barang oleh penyewa (leasing syariah).',
            'deskripsi' => 'Ijarah Muntahiya Bittamlik (IMBT) adalah varian Ijarah di mana akad sewa berakhir dengan perpindahan kepemilikan barang sewaan dari pemberi sewa (koperasi) ke penyewa (anggota), baik melalui hibah, jual-beli simbolis, atau hadiah pada akhir masa sewa. Sangat mirip dengan leasing finance konvensional, hanya berbeda struktur akadnya yang taat syariah.',
            'rumus' => 'Total Bayar = (Ujrah Bulanan × Tenor) + Akad Pemindahan Hak (hibah / jual simbolis)',
            'contoh' => 'Anggota mau punya motor Rp 18jt via cicilan syariah. Koperasi beli motor, sewakan Rp 850rb × 24 bulan, motor jadi milik anggota di akhir.',
            'fatwa' => 'Fatwa DSN-MUI No. 27/DSN-MUI/III/2002'],
        ['slug' => 'salam',      'nama' => 'Salam',      'kategori' => 'Jual-Beli',            'ringkas' => 'Jual-beli dengan pembayaran di muka, barang diserahkan kemudian.',
            'deskripsi' => 'Salam adalah akad jual-beli di mana pembayaran dilakukan di muka, sementara barang diserahkan kemudian sesuai spesifikasi yang disepakati. Banyak digunakan untuk pembiayaan pertanian — koperasi bayar petani di muka untuk panen yang akan datang.',
            'rumus' => 'Pembayaran muka = Harga Pasar Saat Akad − Diskon Salam (5–15%)',
            'contoh' => 'Petani butuh modal tanam padi. Koperasi bayar Rp 30jt sekarang, petani serahkan 5 ton beras saat panen 4 bulan kemudian.',
            'fatwa' => 'Fatwa DSN-MUI No. 05/DSN-MUI/IV/2000'],
        ['slug' => 'istishna',   'nama' => 'Istishna',   'kategori' => 'Jual-Beli',            'ringkas' => 'Pesanan barang yang harus dibuat/diproduksi.',
            'deskripsi' => 'Istishna adalah akad jual-beli barang pesanan, di mana barang akan dibuat sesuai spesifikasi pemesan. Pembayaran bisa di muka, cicilan, atau di akhir. Ideal untuk pembiayaan konstruksi rumah, mebel custom, atau produk manufaktur lainnya.',
            'rumus' => 'Total Bayar = Biaya Produksi + Margin (cicilan fleksibel)',
            'contoh' => 'Anggota pesan rumah Rp 250jt. Koperasi kontrak ke kontraktor, anggota cicil 60 bulan dengan margin 15%.',
            'fatwa' => 'Fatwa DSN-MUI No. 06/DSN-MUI/IV/2000'],
        ['slug' => 'qardh',      'nama' => 'Qardh',      'kategori' => 'Pinjaman Kebajikan',   'ringkas' => 'Pinjaman tanpa imbal hasil — wajib dikembalikan pokoknya saja.',
            'deskripsi' => 'Qardh adalah pinjaman kebajikan tanpa imbalan apapun. Anggota mengembalikan persis sebesar pokok pinjaman. Koperasi bisa meminta administrasi nominal (bukan persentase) untuk biaya operasional. Cocok untuk dana darurat anggota.',
            'rumus' => 'Total Bayar = Pokok Pinjaman + Biaya Administrasi Tetap',
            'contoh' => 'Anggota perlu dana darurat Rp 5jt untuk biaya RS. Koperasi pinjamkan Rp 5jt, anggota kembalikan Rp 5jt + biaya admin Rp 25rb.',
            'fatwa' => 'Fatwa DSN-MUI No. 19/DSN-MUI/IV/2001'],
        ['slug' => 'rahn',       'nama' => 'Rahn',       'kategori' => 'Gadai',                'ringkas' => 'Gadai syariah — anggota menyerahkan barang berharga sebagai jaminan utang.',
            'deskripsi' => 'Rahn adalah akad penyerahan barang berharga (marhun) sebagai jaminan utang (marhun bih). Koperasi menerima barang seperti emas, BPKB, atau sertifikat tanah, lalu memberi pinjaman dengan plafon di bawah nilai taksir. Bila anggota tidak bisa bayar, barang dilelang. Akad utama: Qardh + Rahn + Ijarah (jasa pemeliharaan barang).',
            'rumus' => 'Plafon = Nilai Taksir × LTV (60–80%); Biaya = Ujrah Pemeliharaan × Bulan',
            'contoh' => 'Anggota gadaikan emas 10gr (taksir Rp 10jt). Koperasi pinjamkan Rp 7.5jt (75% LTV), ujrah Rp 50rb/bulan untuk 4 bulan.',
            'fatwa' => 'Fatwa DSN-MUI No. 25/DSN-MUI/III/2002'],
        ['slug' => 'wakalah',    'nama' => 'Wakalah',    'kategori' => 'Perwakilan',           'ringkas' => 'Pendelegasian wewenang — koperasi mewakili anggota melakukan transaksi.',
            'deskripsi' => 'Wakalah adalah akad pemberian kuasa dari muwakkil (anggota) kepada wakil (koperasi) untuk melakukan suatu transaksi atas nama anggota. Sering dipakai sebagai akad pelengkap di transfer uang, pembelian barang impor, atau pengurusan dokumen.',
            'rumus' => 'Ujrah Wakalah = Biaya Tetap atau Persentase Kecil dari Nilai Transaksi',
            'contoh' => 'Anggota minta koperasi belikan saham/sukuk. Koperasi belikan dan kena ujrah Rp 50rb per transaksi.',
            'fatwa' => 'Fatwa DSN-MUI No. 10/DSN-MUI/IV/2000'],
        ['slug' => 'kafalah',    'nama' => 'Kafalah',    'kategori' => 'Penjaminan',           'ringkas' => 'Penjaminan utang — koperasi sebagai penjamin pihak ketiga.',
            'deskripsi' => 'Kafalah adalah akad penjaminan di mana koperasi menjamin pembayaran utang anggota kepada pihak ketiga. Mirip bank garansi di perbankan konvensional. Koperasi mendapat fee penjaminan, dan jika anggota gagal bayar, koperasi menalanginya.',
            'rumus' => 'Fee Kafalah = % × Nilai Penjaminan × Tenor',
            'contoh' => 'Anggota ikut tender proyek Rp 100jt, butuh jaminan. Koperasi terbitkan surat kafalah, fee 1% = Rp 1jt.',
            'fatwa' => 'Fatwa DSN-MUI No. 11/DSN-MUI/IV/2000'],
        ['slug' => 'hawalah',    'nama' => 'Hawalah',    'kategori' => 'Pengalihan Utang',     'ringkas' => 'Pengalihan kewajiban utang dari satu pihak ke pihak lain.',
            'deskripsi' => 'Hawalah adalah akad pengalihan utang dari satu pihak (muhil) kepada pihak lain (muhal alaih). Anggota yang punya piutang ke pihak A bisa "menjual" piutang itu ke koperasi dengan diskon. Anggota dapat dana segera, koperasi tagih A nanti.',
            'rumus' => 'Dana Cair = Nilai Piutang − Diskon Hawalah (3–10%)',
            'contoh' => 'Anggota punya tagihan invoice Rp 50jt jatuh tempo 60 hari. Koperasi ambil-alih, bayar anggota Rp 47.5jt sekarang.',
            'fatwa' => 'Fatwa DSN-MUI No. 12/DSN-MUI/IV/2000'],
    ],

    /*
    | Panduan / artikel evergreen — long-tail keyword Indonesia.
    */
    'panduan' => [
        ['slug' => 'cara-mendirikan-koperasi',           'judul' => 'Cara Mendirikan Koperasi: Panduan Lengkap dari Akta hingga NIK',              'deskripsi' => 'Langkah-langkah lengkap mendirikan koperasi di Indonesia.', 'estimasi_baca' => 12],
        ['slug' => 'akuntansi-koperasi-psak-27',         'judul' => 'Akuntansi Koperasi PSAK 27 — Jurnal, Simpanan, Pinjaman & SHU',              'deskripsi' => 'Penjelasan lengkap PSAK 27 Akuntansi Koperasi.', 'estimasi_baca' => 18],
        ['slug' => 'laporan-keuangan-koperasi',          'judul' => '5 Laporan Keuangan Koperasi yang Wajib Dibuat Setiap Tahun',                  'deskripsi' => 'Neraca, SHU, Arus Kas, Ekuitas, CALK untuk koperasi.', 'estimasi_baca' => 10],
        ['slug' => 'menghitung-shu-koperasi',            'judul' => 'Cara Menghitung SHU Koperasi — Rumus, Contoh, dan Kasus',                     'deskripsi' => 'Rumus SHU per anggota jasa modal & jasa usaha.', 'estimasi_baca' => 8],
        ['slug' => 'jenis-simpanan-koperasi',            'judul' => 'Jenis Simpanan Koperasi: Pokok, Wajib, Sukarela & Berjangka',                'deskripsi' => 'Bedah tuntas 4 jenis simpanan koperasi.', 'estimasi_baca' => 7],
        ['slug' => 'koperasi-syariah-vs-konvensional',    'judul' => 'Koperasi Syariah vs Konvensional: 8 Perbedaan Penting',                    'deskripsi' => 'Bandingkan akad, prinsip pendapatan, pengawas, denda.', 'estimasi_baca' => 9],
        ['slug' => 'pembukuan-koperasi-digital',          'judul' => 'Pembukuan Koperasi Digital: Tinggalkan Excel, Pakai Software',              'deskripsi' => 'Migrasi dari Excel ke software — hemat 90% error.', 'estimasi_baca' => 6],
        ['slug' => 'rapat-anggota-tahunan-rat',           'judul' => 'Cara Menyelenggarakan Rapat Anggota Tahunan (RAT) Koperasi',                'deskripsi' => 'Persiapan, agenda, kuorum, dan dokumen RAT.', 'estimasi_baca' => 11],
        ['slug' => 'pinjaman-koperasi-tanpa-jaminan',     'judul' => 'Pinjaman Koperasi Tanpa Jaminan: Syarat, Plafon & Bunga',                  'deskripsi' => 'Cara dapat pinjaman koperasi tanpa agunan.', 'estimasi_baca' => 8],
        ['slug' => 'bunga-pinjaman-koperasi',             'judul' => 'Bunga Pinjaman Koperasi: Flat vs Efektif vs Anuitas — Mana Lebih Murah?',  'deskripsi' => 'Perbandingan 3 metode bunga pinjaman koperasi.', 'estimasi_baca' => 7],
        ['slug' => 'simpanan-berjangka-koperasi',         'judul' => 'Simpanan Berjangka Koperasi: Deposito dengan Bunga Hingga 12%',            'deskripsi' => 'Panduan simpanan berjangka koperasi — tenor, bunga, pajak.', 'estimasi_baca' => 6],
        ['slug' => 'koperasi-wanita',                     'judul' => 'Koperasi Wanita: Pemberdayaan Ekonomi Perempuan Lewat Koperasi',           'deskripsi' => 'Manfaat dan cara mendirikan koperasi khusus wanita.', 'estimasi_baca' => 7],
        ['slug' => 'koperasi-petani',                     'judul' => 'Koperasi Petani & Nelayan: Solusi Permodalan Sektor Primer',               'deskripsi' => 'Koperasi untuk petani, peternak, dan nelayan.', 'estimasi_baca' => 8],
        ['slug' => 'pajak-koperasi',                      'judul' => 'Pajak Koperasi: SHU, PPN, PPh21 & Cara Hitung yang Benar',                 'deskripsi' => 'Kewajiban pajak koperasi sesuai UU Cipta Kerja.', 'estimasi_baca' => 10],
        ['slug' => 'software-koperasi-gratis',            'judul' => '7 Software Koperasi Gratis Terbaik — Open Source & Trial',                 'deskripsi' => 'Daftar software koperasi gratis dan open source.', 'estimasi_baca' => 9],
        ['slug' => 'manajemen-risiko-koperasi',           'judul' => 'Manajemen Risiko Koperasi: Cara Mengelola Kredit Macet & Likuiditas',      'deskripsi' => 'Strategi mitigasi risiko kredit dan likuiditas.', 'estimasi_baca' => 8],
        ['slug' => 'aplikasi-koperasi-android',           'judul' => '5 Aplikasi Koperasi Android Terbaik — Mobile untuk Anggota & Pengurus',   'deskripsi' => 'Review aplikasi koperasi mobile Android.', 'estimasi_baca' => 6],
        ['slug' => 'migrasi-data-koperasi',               'judul' => 'Cara Migrasi Data Koperasi dari Excel ke Software — Step by Step',         'deskripsi' => 'Panduan migrasi data anggota, simpanan, pinjaman.', 'estimasi_baca' => 7],
        ['slug' => 'koperasi-sekolah',                    'judul' => 'Koperasi Sekolah: Manfaat, Cara Mendirikan & Contoh Program',             'deskripsi' => 'Panduan koperasi siswa di SD, SMP, SMA.', 'estimasi_baca' => 7],
        ['slug' => 'koperasi-mahasiswa',                  'judul' => 'Koperasi Mahasiswa: Wadah Bisnis & Belajar Manajemen di Kampus',           'deskripsi' => 'Cara mendirikan kopma di universitas.', 'estimasi_baca' => 6],
        ['slug' => 'dana-cadangan-koperasi',              'judul' => 'Dana Cadangan Koperasi: Aturan, Besaran & Penggunaan Sesuai UU',          'deskripsi' => 'Ketentuan dana cadangan minimal SHU.', 'estimasi_baca' => 5],
        ['slug' => 'kolektabilitas-pinjaman',            'judul' => 'Kolektabilitas Pinjaman Koperasi: 5 Level & Cara Menurunkannya',           'deskripsi' => 'Kolektabilitas OJK: Lancar s/d Macet.', 'estimasi_baca' => 6],
        ['slug' => 'pengawasan-koperasi',                 'judul' => 'Pengawasan Koperasi: Peran Pengawas, Dinas & OJK dalam Tata Kelola',      'deskripsi' => 'Mekanisme pengawasan internal & eksternal.', 'estimasi_baca' => 7],
        ['slug' => 'koperasi-milenial',                   'judul' => 'Koperasi Milenial: Digitalisasi & Inovasi untuk Generasi Muda',           'deskripsi' => 'Cara menarik anggota milenial ke koperasi.', 'estimasi_baca' => 6],
        ['slug' => 'npl-koperasi',                       'judul' => 'NPL Koperasi: Definisi, Rumus & Target Maksimal 5%',                      'deskripsi' => 'Cara menghitung & menekan NPL koperasi.', 'estimasi_baca' => 5],
    ],

    /*
    | Kalkulator — interactive tool untuk menarik traffic.
    */
    'kalkulator' => [
        ['slug' => 'cicilan-pinjaman',          'judul' => 'Kalkulator Cicilan Pinjaman Koperasi',               'deskripsi' => 'Hitung cicilan bulanan pinjaman: flat, efektif, atau anuitas.', 'inputs' => ['plafon', 'tenor', 'bunga', 'metode']],
        ['slug' => 'bagi-hasil-syariah',         'judul' => 'Kalkulator Bagi Hasil Syariah',                     'deskripsi' => 'Hitung bagi hasil mudharabah/musyarakah.', 'inputs' => ['modal', 'nisbah', 'proyeksi_untung']],
        ['slug' => 'shu-anggota',               'judul' => 'Kalkulator SHU per Anggota',                        'deskripsi' => 'Hitung SHU dari jasa modal & jasa usaha.', 'inputs' => ['simpanan_anggota', 'transaksi_anggota', 'shu_total', 'persen_jasa_modal']],
        ['slug' => 'simpanan-berjangka',         'judul' => 'Kalkulator Simpanan Berjangka Koperasi',            'deskripsi' => 'Proyeksi deposito koperasi di akhir tenor.', 'inputs' => ['nominal', 'tenor', 'bunga']],
        ['slug' => 'margin-murabahah',           'judul' => 'Kalkulator Margin Murabahah',                      'deskripsi' => 'Hitung margin murabahah & cicilan tetap bulanan.', 'inputs' => ['harga_pokok', 'margin_persen', 'tenor']],
        ['slug' => 'denda-keterlambatan',        'judul' => 'Kalkulator Denda Keterlambatan Angsuran',          'deskripsi' => 'Hitung denda telat bayar angsuran koperasi.', 'inputs' => ['angsuran', 'hari_telat', 'denda_persen']],
        ['slug' => 'pelunasan-dipercepat',       'judul' => 'Kalkulator Pelunasan Dipercepat Pinjaman',         'deskripsi' => 'Hitung sisa pokok + bunga berjalan saat pelunasan awal.', 'inputs' => ['plafon', 'tenor', 'bunga', 'angsuran_ke']],
        ['slug' => 'bunga-efektif',              'judul' => 'Kalkulator Bunga Efektif vs Flat — Bandingkan',    'deskripsi' => 'Bandingkan total bayar bunga flat vs efektif vs anuitas.', 'inputs' => ['plafon', 'tenor', 'bunga']],
        ['slug' => 'nisbah-bagi-hasil',          'judul' => 'Kalkulator Nisbah Bagi Hasil Syariah',             'deskripsi' => 'Hitung porsi bagi hasil shahibul maal vs mudharib.', 'inputs' => ['modal_koperasi', 'modal_anggota', 'proyeksi_untung']],
        ['slug' => 'simpanan-wajib-bulanan',     'judul' => 'Kalkulator Simpanan Wajib Bulanan',                'deskripsi' => 'Proyeksi total simpanan wajib dalam N bulan.', 'inputs' => ['setoran_per_bulan', 'jumlah_bulan', 'bunga']],
    ],

    /*
    | Competitor / aplikasi koperasi lain — untuk halaman alternatives & compare.
    */
    'competitors' => [
        ['slug' => 'siska',          'nama' => 'SISKA',            'tagline' => 'Sistem Informasi Simpan Pinjam'],
        ['slug' => 'usaid',          'nama' => 'USAID Koperasi',   'tagline' => 'Manajemen koperasi USAID'],
        ['slug' => 'koperasi-pintar','nama' => 'Koperasi Pintar',  'tagline' => 'Aplikasi koperasi cloud'],
        ['slug' => 'sicantik',       'nama' => 'SICANTIK',         'tagline' => 'Sistem Informasi Koperasi'],
        ['slug' => 'simk',           'nama' => 'SIMK',             'tagline' => 'Sistem Informasi Manajemen Koperasi'],
        ['slug' => 'koperasi-app',   'nama' => 'KoperasiApp',      'tagline' => 'SaaS koperasi multi-tenant'],
        ['slug' => 'ksp-sejahtera',  'nama' => 'KSP Sejahtera',    'tagline' => 'Software simpan pinjam'],
        ['slug' => 'simpan-pinjam',  'nama' => 'SimpanPinjam App', 'tagline' => 'Aplikasi simpan pinjam online'],
        ['slug' => 'koperasi-online', 'nama' => 'KoperasiOnline',   'tagline' => 'Platform koperasi digital'],
        ['slug' => 'mikrohub',       'nama' => 'MikroHub',         'tagline' => 'Sistem informasi keuangan mikro'],
        ['slug' => 'coop-manager',   'nama' => 'CoopManager',      'tagline' => 'Software manajemen koperasi'],
        ['slug' => 'ksp-syariah',    'nama' => 'KSP Syariah App', 'tagline' => 'Aplikasi koperasi syariah'],
        ['slug' => 'fincoop',        'nama' => 'FinCoop',         'tagline' => 'Financial cooperative system'],
        ['slug' => 'koperasiku',     'nama' => 'Koperasiku',      'tagline' => 'Aplikasi koperasi sederhana'],
        ['slug' => 'kospin-jasa',    'nama' => 'Kospin Jasa',     'tagline' => 'Software koperasi simpan pinjam'],
        ['slug' => 'mikrofin',       'nama' => 'MikroFin',        'tagline' => 'Microfinance management'],
        ['slug' => 'kopdarsys',      'nama' => 'KopdarSys',       'tagline' => 'Sistem informasi koperasi'],
        ['slug' => 'ksp-indonesia',  'nama' => 'KSP Indonesia',   'tagline' => 'Aplikasi koperasi nasional'],
        ['slug' => 'coopsoft',       'nama' => 'CoopSoft',        'tagline' => 'Cooperative management software'],
        ['slug' => 'koperasi-pro',   'nama' => 'KoperasiPro',     'tagline' => 'Software koperasi profesional'],
    ],

    /*
    | Brand info — di-render di setiap halaman pSEO.
    */
    'brand' => [
        'nama' => 'Koperasi App',
        'tagline' => 'Software Koperasi Modern: Konvensional & Syariah dalam 1 Platform',
        'fitur' => [
            '15+ modul lengkap — dari simpanan, pinjaman, akuntansi, hingga SHU otomatis',
            'Dual-mode Konvensional & Syariah — toggle 1 klik per produk',
            '12 akad syariah (Mudharabah, Musyarakah, Murabahah, Ijarah, IMBT, Salam, Istishna, Qardh, Rahn, Wakalah, Kafalah, Hawalah)',
            'Multi-tenant SaaS — 1 platform untuk banyak koperasi',
            'Filament Admin Panel modern + Portal Anggota mobile-friendly',
            'Laporan PSAK 27/ETAP otomatis (Neraca, Laba-Rugi, Arus Kas, SHU)',
            'License-locked installation — bisa standalone atau cloud SaaS',
        ],
        'harga_mulai' => 'Rp 299.000/bulan',
    ],

    /*
    | Source code marketing feature slugs.
    */
    'pseo-source-features' => [
        'akuntansi-otomatis', 'portal-anggota', 'mobile-android', 'laporan-keuangan',
        'pos-toko', 'multi-cabang', 'shu-otomatis', 'notifikasi-whatsapp', 'keamanan-data',
    ],

];
