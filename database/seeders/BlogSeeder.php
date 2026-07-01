<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::first()?->id ?? 1;

        $categories = [
            ['name' => 'Simpan Pinjam', 'slug' => 'simpan-pinjam', 'description' => 'Artikel seputar simpanan dan pinjaman koperasi.'],
            ['name' => 'Syariah', 'slug' => 'syariah', 'description' => 'Panduan koperasi syariah dan akad-akad Islam.'],
            ['name' => 'Akuntansi', 'slug' => 'akuntansi', 'description' => 'Akuntansi koperasi PSAK 27, laporan keuangan, dan SHU.'],
            ['name' => 'Digitalisasi', 'slug' => 'digitalisasi', 'description' => 'Tips digitalisasi koperasi, software, dan teknologi.'],
            ['name' => 'Regulasi', 'slug' => 'regulasi', 'description' => 'Peraturan, undang-undang, dan kebijakan koperasi terbaru.'],
            ['name' => 'Manajemen', 'slug' => 'manajemen', 'description' => 'Manajemen koperasi, SDM, dan tata kelola.'],
        ];

        foreach ($categories as $cat) {
            BlogCategory::create($cat);
        }

        $catIds = BlogCategory::pluck('id', 'slug')->toArray();

        $posts = [
            [
                'category_id' => $catIds['simpan-pinjam'],
                'title' => 'Cara Menghitung Bunga Pinjaman Koperasi: Flat vs Efektif vs Anuitas',
                'slug' => 'cara-menghitung-bunga-pinjaman-koperasi',
                'excerpt' => 'Membandingkan 3 metode perhitungan bunga pinjaman koperasi — flat, efektif, dan anuitas — lengkap dengan contoh kasus dan kalkulator.',
                'content' => '<h2>Pendahuluan</h2><p>Salah satu pertanyaan paling umum dari anggota koperasi adalah: "Berapa bunga pinjaman saya?" Jawabannya tergantung pada <strong>metode perhitungan bunga</strong> yang digunakan koperasi. Ada 3 metode utama: flat, efektif, dan anuitas.</p><h2>1. Bunga Flat</h2><p>Bunga dihitung dari pokok pinjaman awal dan angsuran tetap setiap bulan. Rumus: <code>Bunga per bulan = (Pokok × Suku Bunga × Tenor) / Tenor</code>.</p><p><strong>Contoh:</strong> Pinjaman Rp 10.000.000, bunga 12% per tahun, tenor 12 bulan. Angsuran pokok: Rp 833.333/bulan. Bunga: Rp 100.000/bulan. Total bayar: Rp 11.200.000.</p><h2>2. Bunga Efektif</h2><p>Bunga dihitung dari sisa pokok yang belum dibayar. Semakin lama, semakin kecil bunga yang dibayar. Rumus: <code>Bunga = Sisa Pokok × (Suku Bunga / 12)</code>.</p><p><strong>Contoh:</strong> Pinjaman sama, bunga efektif 12%. Bulan 1: bunga Rp 100.000. Bulan 12: bunga Rp 8.333. Total bayar: Rp 10.650.000 — lebih murah daripada flat!</p><h2>3. Anuitas</h2><p>Total angsuran tetap (pokok + bunga), tapi komposisi berubah: porsi bunga menurun, porsi pokok meningkat. Rumus: <code>Angsuran = Pokok × (Bunga × (1+Bunga)^Tenor) / ((1+Bunga)^Tenor - 1)</code>.</p><h2>Kesimpulan</h2><p>Untuk pinjaman jangka pendek (< 1 tahun), perbedaan kecil. Untuk jangka panjang, bunga efektif lebih menguntungkan anggota. Gunakan kalkulator di KoperasiApp untuk simulasi otomatis.</p>',
            ],
            [
                'category_id' => $catIds['syariah'],
                'title' => 'Akad Murabahah: Panduan Lengkap untuk Koperasi Syariah',
                'slug' => 'akad-murabahah-panduan-lengkap',
                'excerpt' => 'Memahami akad murabahah — jual beli dengan margin — dari definisi, fatwa DSN-MUI, rumus perhitungan, hingga contoh implementasi di koperasi syariah.',
                'content' => '<h2>Apa Itu Murabahah?</h2><p>Murabahah adalah akad jual-beli di mana penjual (koperasi) menyebutkan harga pokok pembelian dan margin keuntungan yang disepakati. Ini adalah <strong>akad paling populer</strong> di koperasi syariah Indonesia — sekitar 70% pembiayaan menggunakan murabahah.</p><h2>Dasar Fatwa</h2><p>Fatwa DSN-MUI No. 04/DSN-MUI/IV/2000 menetapkan bahwa murabahah harus memenuhi syarat: (1) barang halal, (2) harga pokok diketahui pembeli, (3) margin disepakati kedua pihak, (4) penjual harus memiliki barang sebelum menjual.</p><h2>Rumus Perhitungan</h2><p><code>Total Pembayaran = Harga Pokok + Margin</code></p><p><code>Cicilan per Bulan = Total Pembayaran / Tenor</code></p><p>Contoh: Anggota ingin beli laptop Rp 8.000.000. Koperasi membeli laptop, menjual ke anggota dengan harga Rp 9.600.000 (margin 20%). Cicilan 12 bulan = Rp 800.000/bulan.</p><h2>Perbedaan dengan Bunga Konvensional</h2><p>Bunga bersifat <em>floating</em> (bisa naik-turun) dan tidak terkait barang riil. Margin murabahah bersifat <em>fixed</em> (tetap) dan harus ada transaksi jual-beli barang nyata. Ini yang membuat murabahah halal secara syariah.</p>',
            ],
            [
                'category_id' => $catIds['akuntansi'],
                'title' => 'PSAK 27 Akuntansi Koperasi: Yang Wajib Diketahui Pengurus',
                'slug' => 'psak-27-akuntansi-koperasi-pengurus',
                'excerpt' => 'Penjelasan lengkap PSAK 27 tentang akuntansi perkoperasian — dari komponen laporan keuangan, perlakuan simpanan, hingga pencatatan SHU.',
                'content' => '<h2>Mengapa PSAK 27 Penting?</h2><p>PSAK 27 (Pernyataan Standar Akuntansi Keuangan No. 27) adalah standar akuntansi khusus untuk koperasi di Indonesia. PSAK 27 mengatur bagaimana koperasi mencatat dan melaporkan transaksi keuangannya.</p><h2>5 Laporan Keuangan Wajib</h2><p>1. <strong>Neraca</strong> — posisi aset, liabilitas, dan ekuitas<br>2. <strong>Laporan SHU</strong> — perhitungan sisa hasil usaha<br>3. <strong>Laporan Arus Kas</strong> — kas masuk dan keluar<br>4. <strong>Laporan Perubahan Ekuitas</strong> — mutasi modal anggota<br>5. <strong>CaLK</strong> — catatan atas laporan keuangan</p><h2>Perlakuan Simpanan</h2><p>Simpanan Pokok dan Simpanan Wajib dicatat sebagai <strong>ekuitas</strong> (modal anggota). Simpanan Sukarela dan Berjangka dicatat sebagai <strong>liabilitas</strong> (kewajiban).</p><h2>Perhitungan SHU</h2><p>SHU dibagi berdasarkan: (1) Jasa modal — proporsional dengan simpanan anggota, (2) Jasa usaha — proporsional dengan transaksi anggota. Minimal 25% SHU harus dialokasikan ke dana cadangan.</p>',
            ],
            [
                'category_id' => $catIds['digitalisasi'],
                'title' => 'Migrasi Data Koperasi dari Excel ke Software: Step by Step',
                'slug' => 'migrasi-data-koperasi-excel-software',
                'excerpt' => 'Panduan langkah demi langkah migrasi data anggota, simpanan, dan pinjaman dari spreadsheet Excel ke software koperasi modern.',
                'content' => '<h2>Kenapa Harus Migrasi?</h2><p>80% koperasi di Indonesia masih pakai Excel. Masalahnya: (1) human error tinggi, (2) tidak bisa multi-user, (3) backup tidak otomatis, (4) tidak bisa akses mobile. Migrasi ke software koperasi = <strong>hemat 90% error dan 50% waktu</strong>.</p><h2>Langkah 1: Persiapan Data</h2><p>Bersihkan data Excel Anda. Pastikan: tidak ada duplikat NIK, nomor anggota unik, nama konsisten (tanpa singkatan), saldo simpanan cocok dengan buku besar. Export ke format CSV (UTF-8 encoding).</p><h2>Langkah 2: Import Anggota</h2><p>KoperasiApp menyediakan template CSV. Download template, paste data dari Excel Anda, upload. Sistem akan validasi otomatis — duplikat, NIK invalid, data wajib kosong.</p><h2>Langkah 3: Import Simpanan</h2><p>Export simpanan per anggota: nomor anggota, jenis simpanan, saldo saat ini. Upload ke sistem. Jurnal pembuka akan dibuat otomatis.</p><h2>Langkah 4: Import Pinjaman Aktif</h2><p>Export pinjaman yang masih berjalan: nomor anggota, pokok, bunga/margin, angsuran ke-, sisa pokok. Sistem generate jadwal angsuran otomatis.</p><h2>Langkah 5: Verifikasi</h2><p>Setelah import, jalankan laporan Neraca dan bandingkan dengan Excel Anda. Pastikan total aset = total liabilitas + ekuitas.</p>',
            ],
            [
                'category_id' => $catIds['regulasi'],
                'title' => 'Syarat Mendirikan Koperasi 2026: Panduan Lengkap dari Akta hingga NIK',
                'slug' => 'syarat-mendirikan-koperasi-2026',
                'excerpt' => 'Langkah-langkah mendirikan koperasi di Indonesia — syarat pendiri, dokumen yang dibutuhkan, proses notaris, hingga NIK dari Kemenkop.',
                'content' => '<h2>Syarat Pendiri</h2><p>Berdasarkan UU No. 25 Tahun 1992, koperasi primer minimal didirikan oleh <strong>20 orang</strong>. Koperasi sekunder minimal <strong>3 badan hukum koperasi</strong>.</p><h2>Dokumen yang Dibutuhkan</h2><p>1. Fotokopi KTP seluruh pendiri<br>2. Surat keterangan domisili<br>3. NPWP atas nama koperasi<br>4. Akta pendirian dari notaris<br>5. Surat pengesahan dari Kemenkop<br>6. NIK (Nomor Induk Koperasi)</p><h2>Proses Pendirian</h2><p><strong>Hari 1-3:</strong> Rapat pembentukan, susun AD/ART<br><strong>Hari 4-7:</strong> Akta notaris, daftar online ke SISMINBHKOP<br><strong>Hari 8-14:</strong> Verifikasi Kemenkop, terbit NIK<br><strong>Total:</strong> ~2 minggu</p><h2>Biaya Pendirian</h2><p>Notaris: Rp 2-5 juta. Pengesahan Kemenkop: gratis. NPWP: gratis. Total budget: Rp 3-7 juta.</p>',
            ],
            [
                'category_id' => $catIds['manajemen'],
                'title' => '5 Strategi Menurunkan NPL Koperasi di Bawah 3%',
                'slug' => 'strategi-menurunkan-npl-koperasi',
                'excerpt' => 'Non-Performing Loan adalah momok koperasi. Pelajari 5 strategi efektif menurunkan NPL dari 10%+ ke bawah 3%.',
                'content' => '<h2>Apa Itu NPL?</h2><p>NPL (Non-Performing Loan) adalah rasio kredit bermasalah terhadap total pinjaman. OJK menetapkan batas maksimal NPL koperasi di <strong>5%</strong>. Di atas itu, koperasi masuk pengawasan ketat.</p><h2>Strategi 1: Perbaiki Proses Analisa Kredit</h2><p>Jangan hanya andalkan rekomendasi pengurus. Gunakan <strong>5C analysis</strong>: Character, Capacity, Capital, Collateral, Condition. KoperasiApp punya scoring otomatis berdasarkan data anggota.</p><h2>Strategi 2: Reminder Otomatis</h2><p>Kirim WhatsApp reminder H-3 dan H-1 jatuh tempo. Banyak anggota lupa, bukan sengaja telat. KoperasiApp kirim otomatis.</p><h2>Strategi 3: Kolektabilitas Harian</h2><p>Update kolektabilitas setiap hari — jangan tunggu akhir bulan. Semakin cepat deteksi, semakin mudah penanganan.</p><h2>Strategi 4: Restrukturisasi Fleksibel</h2><p>Anggota yang kesulitan bayar jangan langsung di-blacklist. Tawarkan restrukturisasi: perpanjang tenor, grace period, atau konversi akad.</p><h2>Strategi 5: Dana Cadangan Memadai</h2><p>Sisihkan 1-3% dari total pinjaman sebagai dana cadangan NPL. Ini bantalan keuangan saat terjadi macet.</p>',
            ],
            [
                'category_id' => $catIds['syariah'],
                'title' => 'Perbedaan Koperasi Syariah vs Konvensional: 8 Hal yang Membedakan',
                'slug' => 'perbedaan-koperasi-syariah-konvensional',
                'excerpt' => 'Bandingkan koperasi syariah dengan konvensional dari sisi akad, pendapatan, pengawas, perlakuan denda, hingga pembagian SHU.',
                'content' => '<h2>1. Sumber Pendapatan</h2><p><strong>Konvensional:</strong> Bunga pinjaman (riba).<br><strong>Syariah:</strong> Margin murabahah, bagi hasil mudharabah, ujrah ijarah.</p><h2>2. Akad</h2><p>Konvensional pakai perjanjian utang-piutang. Syariah pakai akad: murabahah, mudharabah, musyarakah, ijarah, qardh, dll.</p><h2>3. Pengawas</h2><p>Syariah wajib punya <strong>DPS (Dewan Pengawas Syariah)</strong> selain pengawas biasa. DPS memastikan operasional sesuai fatwa DSN-MUI.</p><h2>4. Denda Keterlambatan</h2><p>Konvensional: denda masuk pendapatan koperasi. Syariah: denda harus disalurkan ke dana kebajikan (tidak boleh jadi pendapatan).</p><h2>5. Simpanan</h2><p>Konvensional: semua jenis simpanan dapat bunga. Syariah: simpanan pakai akad wadiah (titipan tanpa bunga) atau mudharabah (bagi hasil).</p><h2>6. Pembagian SHU</h2><p>Konvensional: SHU dibagi sesuai jasa modal + jasa usaha. Syariah: sama, tapi harus dipastikan tidak ada unsur riba.</p><h2>7. Sumber Dana</h2><p>Konvensional bisa dari bank konvensional. Syariah hanya dari bank syariah atau dana halal.</p><h2>8. Bisnis yang Dibiayai</h2><p>Syariah tidak boleh membiayai usaha haram: alkohol, judi, babi, dll.</p>',
            ],
            [
                'category_id' => $catIds['akuntansi'],
                'title' => 'Cara Menghitung SHU Koperasi: Rumus, Contoh, dan Kasus Nyata',
                'slug' => 'cara-menghitung-shu-koperasi-rumus-contoh',
                'excerpt' => 'Pelajari rumus SHU per anggota, alokasi dana cadangan, jasa modal, jasa usaha, dan contoh perhitungan lengkap dengan Excel.',
                'content' => '<h2>Komponen SHU</h2><p>SHU (Sisa Hasil Usaha) adalah laba koperasi setelah dikurangi biaya operasional. SHU berasal dari: (1) pendapatan jasa pinjaman, (2) pendapatan toko, (3) pendapatan unit usaha lain.</p><h2>Alokasi SHU</h2><p>Berdasarkan UU No. 25/1992:<br>- Dana cadangan: minimal 25%<br>- Jasa anggota (modal + usaha): maksimal 40%<br>- Dana pengurus: maksimal 10%<br>- Dana karyawan: maksimal 5%<br>- Dana pendidikan: minimal 5%<br>- Dana sosial: minimal 5%<br>- Dana pembangunan daerah: sesuai AD/ART</p><h2>Rumus SHU per Anggota</h2><p><code>SHU Anggota A = (Simpanan A / Total Simpanan) × SHU Jasa Modal + (Transaksi A / Total Transaksi) × SHU Jasa Usaha</code></p><h2>Contoh Nyata</h2><p>Total SHU: Rp 100.000.000<br>SHU Jasa Modal (25%): Rp 25.000.000<br>SHU Jasa Usaha (15%): Rp 15.000.000<br>Anggota A: simpanan Rp 5.000.000 dari total Rp 250.000.000, transaksi Rp 10.000.000 dari total Rp 500.000.000<br>SHU A = (5jt/250jt × 25jt) + (10jt/500jt × 15jt) = Rp 500.000 + Rp 300.000 = <strong>Rp 800.000</strong></p>',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create(array_merge($post, [
                'author_id' => $authorId,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'meta_title' => $post['title'],
                'meta_description' => $post['excerpt'],
            ]));
        }
    }
}
