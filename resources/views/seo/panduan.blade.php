@extends('seo._layout')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">

    <nav class="text-sm text-slate-500 mb-6 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="#" class="hover:text-indigo-600">Panduan</a>
        <span>/</span>
        <span class="text-slate-700 truncate">{{ $panduan['judul'] }}</span>
    </nav>

    <header class="mb-10">
        <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">Panduan · {{ $panduan['estimasi_baca'] }} menit baca</span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight text-slate-900">{{ $panduan['judul'] }}</h1>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">{{ $panduan['deskripsi'] }}</p>
    </header>

    <article class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 shadow-sm">
        <div class="prose-custom max-w-none">
            @switch($panduan['slug'])
                @case('cara-mendirikan-koperasi')
                    <p>Mendirikan koperasi di Indonesia tidak serumit yang dibayangkan, tapi tetap butuh urutan langkah yang tepat agar koperasi memiliki status badan hukum yang sah dan bisa langsung beroperasi. Berikut tahapan lengkapnya berdasarkan UU No. 25 Tahun 1992 dan pembaruan regulasi terbaru dari Kemenkop UKM.</p>
                    <h2>1. Rapat Pendirian dengan Minimal 20 Orang</h2>
                    <p>Kumpulkan minimal 20 orang calon anggota yang sepakat mendirikan koperasi. Adakan rapat pendirian dengan agenda: nama koperasi, tujuan & jenis usaha, AD/ART (Anggaran Dasar/Rumah Tangga), modal awal (simpanan pokok), dan susunan pengurus pertama. Buat berita acara rapat ditandatangani semua peserta.</p>
                    <h2>2. Persiapan Dokumen Akta</h2>
                    <p>Siapkan: berita acara rapat pendirian, draft AD/ART, daftar nama 20 pendiri lengkap dengan KTP & NPWP, neraca awal, dan surat pernyataan modal sendiri. Khusus untuk koperasi syariah (KSPPS), tambahkan susunan Dewan Pengawas Syariah dengan minimal 1 anggota bersertifikat DSN-MUI.</p>
                    <h2>3. Akta Notaris Pendirian</h2>
                    <p>Datang ke notaris yang ditunjuk Kemenkop UKM (daftar resmi tersedia di kemenkopukm.go.id). Notaris akan mengangkat akta pendirian koperasi berdasarkan dokumen yang Anda siapkan. Biaya notaris berkisar Rp 1,5–3 juta tergantung kompleksitas AD/ART.</p>
                    <h2>4. Pengesahan Badan Hukum di Kemenkumham</h2>
                    <p>Notaris akan submit ke sistem AHU online (Administrasi Hukum Umum) Kemenkumham. Setelah disetujui (biasanya 7–14 hari kerja), keluar SK Pengesahan Badan Hukum Koperasi dengan nomor SK resmi. Sejak ini, koperasi sah secara hukum.</p>
                    <h2>5. NPWP, NIB, dan Izin Usaha</h2>
                    <p>Daftarkan NPWP koperasi ke kantor pajak setempat, lalu daftarkan NIB (Nomor Induk Berusaha) di OSS-RBA (oss.go.id). Untuk KSP/KSPPS, tambahkan izin usaha simpan pinjam dari Kementerian Koperasi UKM. Dokumen ini wajib agar bisa membuka rekening koperasi atas nama badan hukum.</p>
                    <h2>6. Setup Operasional</h2>
                    <p>Setelah administrasi selesai: buka rekening koperasi, sediakan kantor (boleh rumah pengurus), pasang software pengelolaan koperasi seperti {{ $brand['nama'] }} sejak hari pertama beroperasi — biar tidak menumpuk pekerjaan rekap manual yang harus dimigrasi nanti. Idealnya software sudah running sebelum simpanan anggota pertama masuk.</p>
                    @break

                @case('akuntansi-koperasi-psak-27')
                    <p>PSAK 27 (Akuntansi Koperasi) adalah standar akuntansi khusus yang berlaku untuk koperasi di Indonesia. Berbeda dengan PSAK umum (untuk PT/CV), PSAK 27 mengakomodasi karakter unik koperasi: simpanan anggota sebagai modal sendiri, distribusi SHU, dan pencatatan jasa modal vs jasa usaha.</p>
                    <h2>Akun Khas Koperasi yang Wajib Ada</h2>
                    <p>Setiap koperasi harus memiliki akun-akun khusus berikut di chart of accounts (CoA):</p>
                    <ul>
                        <li><strong>Simpanan Pokok</strong> — disetor sekali saat masuk anggota, tidak dapat ditarik (modal permanen)</li>
                        <li><strong>Simpanan Wajib</strong> — disetor rutin (bulanan), tidak dapat ditarik selama jadi anggota</li>
                        <li><strong>Simpanan Sukarela</strong> — bersifat tabungan, dapat ditarik kapan saja</li>
                        <li><strong>Cadangan Koperasi</strong> — alokasi dari SHU untuk keperluan ekspansi & risiko</li>
                        <li><strong>SHU Belum Dibagi</strong> — sisa hasil usaha akhir tahun sebelum pembagian RAT</li>
                        <li><strong>Pendapatan Jasa Bunga / Bagi Hasil</strong> — dari pinjaman/pembiayaan</li>
                    </ul>
                    <h2>Jurnal Standar Transaksi Koperasi</h2>
                    <h3>1. Setoran Simpanan Pokok</h3>
                    <p>Dr. Kas Rp X / Cr. Simpanan Pokok Anggota Rp X — anggota baru menyetor.</p>
                    <h3>2. Pencairan Pinjaman</h3>
                    <p>Dr. Piutang Pinjaman Anggota Rp X / Cr. Kas Rp X — koperasi mengeluarkan dana ke anggota.</p>
                    <h3>3. Pembayaran Cicilan + Bunga</h3>
                    <p>Dr. Kas Rp Y / Cr. Piutang Pinjaman Rp Y1 / Cr. Pendapatan Bunga Rp Y2 — anggota bayar cicilan, dipisah pokok dan bunga.</p>
                    <h3>4. Distribusi SHU</h3>
                    <p>Dr. SHU Belum Dibagi Rp Z / Cr. Hutang ke Anggota (jasa modal) Rp Z1 / Cr. Hutang ke Anggota (jasa usaha) Rp Z2 / Cr. Cadangan Rp Z3 — di RAT akhir tahun.</p>
                    <h2>Format Laporan Keuangan PSAK 27</h2>
                    <p>Setiap akhir periode (bulanan/tahunan), koperasi wajib menerbitkan: <strong>Neraca, Laporan Sisa Hasil Usaha (PHU/SHU), Laporan Arus Kas, Laporan Perubahan Ekuitas Anggota, dan Catatan atas Laporan Keuangan</strong>. Software {{ $brand['nama'] }} otomatis generate kelima laporan ini real-time tanpa coding rumus, dengan format sudah sesuai PSAK 27.</p>
                    @break

                @case('laporan-keuangan-koperasi')
                    <p>Berdasarkan PSAK 27 dan Permenkop UKM, ada 5 laporan keuangan yang wajib dibuat koperasi setiap akhir periode pelaporan (bulanan untuk internal, tahunan untuk RAT dan setoran ke Dinas Koperasi).</p>
                    <h2>1. Neraca (Laporan Posisi Keuangan)</h2>
                    <p>Snapshot aset, kewajiban, dan ekuitas pada satu titik waktu. Khas koperasi: bagian ekuitas dirinci sebagai Simpanan Pokok, Simpanan Wajib, Cadangan, dan SHU Belum Dibagi. Tidak ada "modal saham" seperti PT.</p>
                    <h2>2. Laporan Sisa Hasil Usaha (PHU/SHU)</h2>
                    <p>Sama dengan Laba/Rugi di PT, tapi disebut "Sisa Hasil Usaha" karena prinsip koperasi bukan profit-maximizing. Format: Pendapatan Operasional (bunga/bagi hasil) − Beban Operasional − Beban Administratif = SHU.</p>
                    <h2>3. Laporan Arus Kas</h2>
                    <p>Memetakan keluar-masuk kas dari 3 aktivitas: Operasional (cicilan masuk, gaji keluar), Investasi (beli aset tetap), dan Pendanaan (setoran simpanan, pinjaman dari induk). Wajib pakai metode langsung untuk koperasi.</p>
                    <h2>4. Laporan Perubahan Ekuitas Anggota</h2>
                    <p>Khas koperasi — memetakan pergerakan setiap pos ekuitas: simpanan pokok masuk dari anggota baru, simpanan wajib akumulasi bulanan, cadangan dari alokasi SHU, dan distribusi SHU ke anggota.</p>
                    <h2>5. Catatan atas Laporan Keuangan (CALK)</h2>
                    <p>Penjelasan naratif yang melengkapi keempat laporan di atas: kebijakan akuntansi, rincian akun-akun penting, kontrak material, dan kontingensi. Di RAT, CALK dibaca paragraf demi paragraf agar anggota paham.</p>
                    <h2>Otomatisasi dengan Software</h2>
                    <p>Manual menyusun 5 laporan ini bisa makan 1 minggu kerja keras menjelang RAT. Dengan {{ $brand['nama'] }}, kelima laporan tersedia real-time dalam menu Laporan — pengurus tinggal pilih periode, klik export, dan PDF siap dibagikan.</p>
                    @break

                @case('menghitung-shu-koperasi')
                    <p>Sisa Hasil Usaha (SHU) adalah surplus operasional koperasi setelah dikurangi semua beban. Pembagian SHU diatur AD/ART dan UU No. 25/1992 — tidak boleh dibagikan rata, harus proporsional terhadap kontribusi anggota berupa <strong>jasa modal</strong> (simpanan) dan <strong>jasa usaha</strong> (transaksi).</p>
                    <h2>Rumus SHU per Anggota</h2>
                    <p>Standar yang umum dipakai (sesuai contoh AD/ART Kemenkop):</p>
                    <ul>
                        <li>SHU Anggota = SHU Jasa Modal + SHU Jasa Usaha</li>
                        <li>SHU Jasa Modal = (Simpanan Anggota / Total Simpanan) × Pos Jasa Modal</li>
                        <li>SHU Jasa Usaha = (Transaksi Anggota / Total Transaksi) × Pos Jasa Usaha</li>
                    </ul>
                    <h2>Persentase Pembagian Standar AD/ART</h2>
                    <p>Total SHU biasanya dipecah menjadi:</p>
                    <ul>
                        <li>Cadangan Koperasi: 25%</li>
                        <li>Jasa Modal Anggota: 25%</li>
                        <li>Jasa Usaha Anggota: 25%</li>
                        <li>Dana Pengurus & Pengawas: 10%</li>
                        <li>Dana Pendidikan: 5%</li>
                        <li>Dana Sosial: 5%</li>
                        <li>Dana Pembangunan Daerah Kerja: 5%</li>
                    </ul>
                    <h2>Contoh Kasus Lengkap</h2>
                    <p>Koperasi A punya SHU Rp 100 juta. Anggota Pak Budi punya simpanan Rp 5jt (dari total simpanan Rp 500jt) dan transaksi Rp 3jt (dari total transaksi Rp 100jt).</p>
                    <ul>
                        <li>Pos Jasa Modal: 25% × Rp 100jt = Rp 25jt</li>
                        <li>Pos Jasa Usaha: 25% × Rp 100jt = Rp 25jt</li>
                        <li>SHU Jasa Modal Pak Budi = (5/500) × 25jt = Rp 250.000</li>
                        <li>SHU Jasa Usaha Pak Budi = (3/100) × 25jt = Rp 750.000</li>
                        <li><strong>Total SHU Pak Budi = Rp 1.000.000</strong></li>
                    </ul>
                    <p>Pakai <a href="{{ route('seo.kalkulator', 'shu-anggota') }}" class="text-indigo-600 font-semibold underline">kalkulator SHU</a> untuk hitung otomatis. Atau lebih praktis: {{ $brand['nama'] }} hitung SHU 1.000 anggota dalam 5 detik dan langsung kirim slip ke WhatsApp masing-masing.</p>
                    @break

                @case('jenis-simpanan-koperasi')
                    <p>Koperasi mengenal 4 jenis simpanan utama, masing-masing dengan karakter dan perlakuan akuntansi yang berbeda. Pengurus koperasi wajib memahami perbedaan ini agar tidak salah catat dan tidak salah berkomunikasi dengan anggota.</p>
                    <h2>1. Simpanan Pokok</h2>
                    <p>Disetor sekali saat anggota baru bergabung — biasanya Rp 100.000 sampai Rp 1 juta tergantung skala koperasi. Sifatnya <strong>permanen</strong>: tidak dapat ditarik selama menjadi anggota. Hanya bisa dikembalikan saat berhenti dari koperasi (mengundurkan diri/keluar). Simpanan pokok adalah modal dasar dan jadi indikator utama jumlah anggota.</p>
                    <h2>2. Simpanan Wajib</h2>
                    <p>Disetor rutin (umumnya bulanan) selama menjadi anggota — misal Rp 50.000/bulan. Sama seperti simpanan pokok, sifatnya <strong>tidak dapat ditarik</strong> selama keanggotaan masih aktif. Akumulasinya jadi indikator komitmen anggota dan dipakai sebagai dasar perhitungan jasa modal SHU.</p>
                    <h2>3. Simpanan Sukarela</h2>
                    <p>Bersifat <strong>tabungan</strong> — bisa disetor dan ditarik kapan saja. Anggota menyetor sesuai keinginan, dan boleh narik sewaktu-waktu (mirip rekening tabungan bank). Beberapa koperasi memberi bunga/bagi hasil untuk simpanan sukarela. Akun ini dicatat sebagai <strong>kewajiban (utang)</strong> koperasi ke anggota, bukan ekuitas.</p>
                    <h2>4. Simpanan Berjangka (Deposito Koperasi)</h2>
                    <p>Anggota menyetor dengan tenor tetap (3, 6, 12 bulan) dan dapat bunga/bagi hasil yang lebih tinggi. Tidak bisa ditarik sebelum jatuh tempo (atau kena penalty). Mirip deposito bank. Untuk koperasi syariah, namanya menjadi "Simpanan Mudharabah Berjangka" dengan akad mudharabah dan nisbah disepakati di awal.</p>
                    <h2>Tabel Ringkas Perbedaan</h2>
                    <ul>
                        <li><strong>Pokok</strong>: 1× saat masuk, tidak dapat ditarik, ekuitas, tidak ada bunga</li>
                        <li><strong>Wajib</strong>: rutin bulanan, tidak dapat ditarik, ekuitas, dasar jasa modal SHU</li>
                        <li><strong>Sukarela</strong>: bebas, dapat ditarik kapan saja, kewajiban, ada bunga rendah</li>
                        <li><strong>Berjangka</strong>: tenor tetap, tidak dapat ditarik dini, kewajiban, bunga tertinggi</li>
                    </ul>
                    <p>Software {{ $brand['nama'] }} memisahkan keempat jenis ini dalam modul terpisah — pengurus bisa setting parameter masing-masing (minimum setoran, biaya admin, bunga/nisbah) tanpa perlu bantuan teknis.</p>
                    @break

                @case('koperasi-syariah-vs-konvensional')
                    <p>Koperasi syariah dan konvensional sama-sama lembaga keuangan kooperatif berbasis anggota, tapi punya perbedaan fundamental dalam prinsip operasional. Berikut 8 perbedaan paling penting yang harus dipahami sebelum memilih jenis koperasi yang akan didirikan atau diikuti.</p>
                    <h2>1. Sumber Pendapatan</h2>
                    <p><strong>Konvensional:</strong> bunga atas pinjaman (persentase tetap atas pokok). <strong>Syariah:</strong> margin (jual-beli), bagi hasil (kerjasama), atau ujrah (sewa) — sesuai akad yang dipakai.</p>
                    <h2>2. Akad Transaksi</h2>
                    <p><strong>Konvensional:</strong> akad utang-piutang (pinjam meminjam). <strong>Syariah:</strong> 10 jenis akad spesifik — mudharabah, musyarakah, murabahah, ijarah, salam, istishna, qardh, wakalah, kafalah, hawalah — masing-masing untuk konteks transaksi yang berbeda.</p>
                    <h2>3. Risiko Kerugian</h2>
                    <p><strong>Konvensional:</strong> risiko 100% di anggota peminjam — bunga tetap dibayar walau usaha rugi. <strong>Syariah (akad bagi hasil):</strong> risiko ditanggung bersama sesuai porsi modal — kalau usaha rugi, koperasi juga ikut menanggung.</p>
                    <h2>4. Struktur Pengawas</h2>
                    <p><strong>Konvensional:</strong> hanya Pengawas Manajemen. <strong>Syariah:</strong> ada tambahan Dewan Pengawas Syariah (DPS) bersertifikat DSN-MUI yang bertugas memastikan operasional sesuai prinsip syariah.</p>
                    <h2>5. Perlakuan Denda Keterlambatan</h2>
                    <p><strong>Konvensional:</strong> denda menambah pendapatan koperasi. <strong>Syariah:</strong> denda tidak boleh jadi pendapatan — masuk ke "Dana Kebajikan" untuk disalurkan ke amal/zakat.</p>
                    <h2>6. Produk Tabungan</h2>
                    <p><strong>Konvensional:</strong> simpanan dapat bunga rendah. <strong>Syariah:</strong> simpanan wadiah (titipan tanpa imbal) atau simpanan mudharabah (bagi hasil bila koperasi untung).</p>
                    <h2>7. Target Anggota</h2>
                    <p><strong>Konvensional:</strong> umum, semua kalangan. <strong>Syariah:</strong> bisa umum tapi banyak diadopsi komunitas muslim, pondok pesantren, dan instansi yang ingin layanan halal.</p>
                    <h2>8. Pelaporan</h2>
                    <p><strong>Konvensional:</strong> laporan keuangan PSAK 27 standar. <strong>Syariah:</strong> tambahan Laporan Sumber & Penggunaan Dana Zakat dan Dana Kebajikan, audit kepatuhan syariah oleh DPS.</p>
                    <p>Untuk koperasi yang ingin <strong>melayani kedua segmen anggota</strong>, {{ $brand['nama'] }} mendukung mode dual-syariah/konvensional dalam 1 instalasi — tinggal toggle per produk.</p>
                    @break

                @case('pembukuan-koperasi-digital')
                    <p>Pembukuan manual via buku tulis atau Excel masih dipakai mayoritas koperasi mikro di Indonesia. Tapi seiring jumlah anggota dan transaksi membesar, pendekatan ini cepat patah — error rate tinggi, tutup buku lama, dan rentan saat audit.</p>
                    <h2>Masalah Pembukuan Manual / Excel</h2>
                    <ul>
                        <li><strong>Error rumus.</strong> Cell broken karena formula di-overwrite, sum range salah, atau referensi cell hilang saat insert baris baru.</li>
                        <li><strong>Tidak ada audit trail.</strong> Tidak tahu siapa edit apa kapan — masalah besar saat ada selisih.</li>
                        <li><strong>File berat & lambat.</strong> Excel 50MB dengan 10.000 baris transaksi mulai stuck; gampang corrupt.</li>
                        <li><strong>Tidak multi-user.</strong> 2 orang edit bersamaan = file conflict, salah satu version hilang.</li>
                        <li><strong>Tutup buku lama.</strong> Rekap manual ke neraca dan laba-rugi memakan 3–5 hari kerja.</li>
                    </ul>
                    <h2>Manfaat Migrasi ke Software Koperasi</h2>
                    <ul>
                        <li><strong>Error turun 90%.</strong> Sistem auto-jurnal — tidak ada celah salah ketik akun atau salah debet/kredit.</li>
                        <li><strong>Tutup buku 30 menit.</strong> Neraca, laba-rugi, arus kas otomatis ter-update real-time.</li>
                        <li><strong>Multi-user dengan role.</strong> Kasir, akunting, manager — masing-masing punya akses sesuai job desc.</li>
                        <li><strong>Backup otomatis cloud.</strong> Tidak takut file hilang, laptop rusak, atau virus.</li>
                        <li><strong>Anggota bisa cek dari HP.</strong> Portal Anggota mengurangi beban admin yang biasanya kebanjiran pertanyaan saldo.</li>
                    </ul>
                    <h2>Berapa Biaya Software Koperasi?</h2>
                    <p>Mitos lama: software koperasi mahal, butuh server fisik, butuh IT staff. Realita 2026: software cloud SaaS seperti {{ $brand['nama'] }} mulai {{ $brand['harga_mulai'] }} — sudah include hosting, backup, update fitur, dan support. Tidak butuh server, tidak butuh IT staff, anggota bisa setup sendiri dalam 1 hari.</p>
                    <h2>Cara Migrasi dari Excel ke Software</h2>
                    <ol class="list-decimal pl-6">
                        <li>Tutup buku Excel di tanggal cut-off yang dipilih.</li>
                        <li>Export data anggota, simpanan, dan pinjaman ke CSV.</li>
                        <li>Import ke software via template impor (1-klik).</li>
                        <li>Verifikasi saldo per anggota cocok dengan Excel.</li>
                        <li>Set tanggal opening balance & mulai transaksi baru di software.</li>
                    </ol>
                    <p>Tim support {{ $brand['nama'] }} membantu migrasi gratis untuk pelanggan baru — termasuk import data 1.000+ anggota dalam 1 sesi remote.</p>
                    @break

                @case('rapat-anggota-tahunan-rat')
                    <p>Rapat Anggota Tahunan (RAT) adalah forum tertinggi koperasi yang wajib digelar setiap tahun. Di sini laporan keuangan disahkan, SHU dibagikan, dan rencana kerja tahun berjalan ditetapkan. Berikut panduan lengkap menyelenggarakan RAT yang lancar dan compliant.</p>
                    <h2>Persiapan Pra-RAT (1–2 Bulan Sebelum)</h2>
                    <ul>
                        <li>Tutup buku tahunan dan finalisasi laporan keuangan</li>
                        <li>Audit internal oleh Pengawas — verifikasi kewajaran laporan</li>
                        <li>Hitung distribusi SHU per anggota (jasa modal + jasa usaha)</li>
                        <li>Susun laporan pertanggungjawaban pengurus</li>
                        <li>Susun draft rencana kerja & RAPB tahun berjalan</li>
                    </ul>
                    <h2>Undangan & Kuorum</h2>
                    <p>Undangan RAT dikirim minimal 14 hari sebelum tanggal RAT, lengkap dengan agenda, lampiran ringkasan laporan keuangan, dan tata cara hadir (offline/online). Kuorum sah RAT sesuai AD/ART — umumnya 50% + 1 dari total anggota (atau 2/3 untuk RAT yang mengubah AD/ART).</p>
                    <h2>Agenda RAT Standar</h2>
                    <ol class="list-decimal pl-6">
                        <li>Pembukaan & laporan kuorum</li>
                        <li>Laporan pertanggungjawaban pengurus</li>
                        <li>Laporan pengawas</li>
                        <li>Tanya jawab anggota</li>
                        <li>Pengesahan laporan keuangan</li>
                        <li>Pengesahan distribusi SHU</li>
                        <li>Pengesahan rencana kerja & RAPB</li>
                        <li>Pemilihan pengurus baru (bila masa jabatan berakhir)</li>
                        <li>Penutup</li>
                    </ol>
                    <h2>Pengambilan Keputusan</h2>
                    <p>Keputusan RAT diambil dengan musyawarah-mufakat. Jika tidak tercapai, melalui voting — 1 anggota = 1 suara (terlepas dari besar simpanan). Ini prinsip demokrasi koperasi yang membedakannya dari PT.</p>
                    <h2>Dokumen Wajib</h2>
                    <ul>
                        <li>Berita Acara RAT (ditandatangani pimpinan rapat & sekretaris)</li>
                        <li>Daftar hadir anggota</li>
                        <li>Laporan keuangan yang disahkan</li>
                        <li>Rincian distribusi SHU per anggota</li>
                        <li>Rencana kerja & RAPB tahun berjalan</li>
                        <li>SK kepengurusan (bila ada pemilihan)</li>
                    </ul>
                    <p>Software {{ $brand['nama'] }} menyediakan modul "Persiapan RAT" dengan satu klik export semua dokumen di atas — pengurus tinggal print, distribusikan, dan fokus presentasi.</p>
                    @break

                @default
                    <p>Konten panduan ini sedang disiapkan tim editor {{ $brand['nama'] }}. Sementara itu, lihat panduan terkait di bawah.</p>
            @endswitch
        </div>
    </article>

    @include('seo._cta')

    <section class="my-16">
        <h2 class="text-3xl font-extrabold mb-2 text-slate-900">Panduan Koperasi Lainnya</h2>
        <div class="grid md:grid-cols-2 gap-5">
            @foreach($panduanLain as $p)
                <a href="{{ route('seo.panduan', $p['slug']) }}" class="card-hover bg-white rounded-2xl border border-slate-200 p-6 block">
                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded mb-3">{{ $p['estimasi_baca'] }} menit baca</span>
                    <h3 class="font-extrabold text-base mb-2 text-slate-900">{{ $p['judul'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $p['deskripsi'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
