<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProgrammaticSeoController extends Controller
{
    public function aplikasiKoperasiKota(string $kotaSlug)
    {
        $kota = $this->findKota($kotaSlug);
        abort_if(!$kota, 404, 'Kota tidak ditemukan.');

        $year = (int) date('Y');
        $jenis = collect(config('pseo.jenis'));
        $brand = config('pseo.brand');

        $title = "Aplikasi Koperasi Terbaik di {$kota['nama']} {$year}";
        $description = "Daftar 7 aplikasi/software koperasi terbaik untuk pengurus koperasi di {$kota['nama']}, {$kota['provinsi']}. Bandingkan fitur, harga, dan modul akuntansi PSAK 27. Tersedia mode Konvensional & Syariah.";

        return view('seo.kota', [
            'kota'           => $kota,
            'year'           => $year,
            'jenisKoperasi'  => $jenis,
            'brand'          => $brand,
            'kotaTerkait'    => $this->kotaTerkait($kota['slug']),
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildItemListJsonLd($title, $jenis->pluck('nama')->toArray()),
            'breadcrumbLd'   => $this->buildBreadcrumbJsonLd([
                ['name' => 'Beranda', 'url' => url('/')],
                ['name' => "Koperasi di {$kota['nama']}", 'url' => url()->current()],
            ]),
        ]);
    }

    public function jenisKoperasi(string $jenisSlug)
    {
        $jenis = $this->findJenis($jenisSlug);
        abort_if(!$jenis, 404, 'Jenis koperasi tidak ditemukan.');

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $jenisLain = collect(config('pseo.jenis'))->reject(fn ($j) => $j['slug'] === $jenisSlug)->values();
        $kotaPopuler = collect(config('pseo.kota'))->take(8);

        $title = "{$jenis['nama']} ({$jenis['singkatan']}): Panduan Lengkap & Software {$year}";
        $description = Str::limit(strip_tags($jenis['deskripsi']), 155);

        $faqs = $this->buildJenisFaqs($jenis);

        return view('seo.jenis', [
            'jenis'          => $jenis,
            'jenisLain'      => $jenisLain,
            'kotaPopuler'    => $kotaPopuler,
            'year'           => $year,
            'brand'          => $brand,
            'faqs'           => $faqs,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildFaqJsonLd($faqs, $title),
        ]);
    }

    public function akadSyariah(string $akadSlug)
    {
        $akad = $this->findAkad($akadSlug);
        abort_if(!$akad, 404, 'Akad tidak ditemukan.');

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $akadLain = collect(config('pseo.akad'))->reject(fn ($a) => $a['slug'] === $akadSlug)->values();

        $title = "Akad {$akad['nama']}: {$akad['ringkas']}";
        $description = "Penjelasan akad {$akad['nama']} ({$akad['kategori']}): rumus, contoh kasus, dan dasar fatwa DSN-MUI. Termasuk implementasi di software koperasi syariah {$brand['nama']}.";

        $faqs = $this->buildAkadFaqs($akad);

        return view('seo.akad', [
            'akad'           => $akad,
            'akadLain'       => $akadLain,
            'year'           => $year,
            'brand'          => $brand,
            'faqs'           => $faqs,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildFaqJsonLd($faqs, $title),
        ]);
    }

    public function panduan(string $panduanSlug)
    {
        $panduan = collect(config('pseo.panduan'))->firstWhere('slug', $panduanSlug);
        abort_if(!$panduan, 404, 'Panduan tidak ditemukan.');

        $brand = config('pseo.brand');
        $panduanLain = collect(config('pseo.panduan'))->reject(fn ($p) => $p['slug'] === $panduanSlug)->values()->take(5);

        return view('seo.panduan', [
            'panduan'        => $panduan,
            'panduanLain'    => $panduanLain,
            'brand'          => $brand,
            'seoTitle'       => $panduan['judul'],
            'seoDescription' => $panduan['deskripsi'],
            'jsonLd'         => $this->buildArticleJsonLd($panduan),
        ]);
    }

    public function kalkulator(string $kalkulatorSlug)
    {
        $kalkulator = collect(config('pseo.kalkulator'))->firstWhere('slug', $kalkulatorSlug);
        abort_if(!$kalkulator, 404, 'Kalkulator tidak ditemukan.');

        $brand = config('pseo.brand');
        $kalkulatorLain = collect(config('pseo.kalkulator'))->reject(fn ($k) => $k['slug'] === $kalkulatorSlug)->values();

        return view('seo.kalkulator', [
            'kalkulator'     => $kalkulator,
            'kalkulatorLain' => $kalkulatorLain,
            'brand'          => $brand,
            'seoTitle'       => $kalkulator['judul'],
            'seoDescription' => $kalkulator['deskripsi'],
        ]);
    }

    public function alternatives(string $competitorSlug)
    {
        $competitor = collect(config('pseo.competitors'))->firstWhere('slug', $competitorSlug);
        abort_if(!$competitor, 404, 'Aplikasi pembanding tidak ditemukan.');

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $competitorLain = collect(config('pseo.competitors'))->reject(fn ($c) => $c['slug'] === $competitorSlug)->values();

        $title = "{$competitor['nama']} Alternatif {$year}: 5 Software Koperasi Terbaik Pengganti";
        $description = "Mencari alternatif {$competitor['nama']}? Bandingkan {$brand['nama']} dan software koperasi lain berdasarkan fitur, harga, dukungan syariah, dan kemudahan migrasi data.";

        return view('seo.alternatives', [
            'competitor'     => $competitor,
            'competitorLain' => $competitorLain,
            'brand'          => $brand,
            'year'           => $year,
            'seoTitle'       => $title,
            'seoDescription' => $description,
        ]);
    }

    /**
     * Kota × Jenis Koperasi — e.g. /aplikasi-koperasi/jakarta/simpan-pinjam
     */
    public function aplikasiKoperasiKotaJenis(string $kotaSlug, string $jenisSlug)
    {
        $kota = $this->findKota($kotaSlug);
        $jenis = $this->findJenis($jenisSlug);
        abort_if(!$kota || !$jenis, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $jenisLain = collect(config('pseo.jenis'))->reject(fn ($j) => $j['slug'] === $jenisSlug)->take(4)->values();

        $title = "{$jenis['nama']} di {$kota['nama']} {$year}: Software & Panduan Lengkap";
        $description = "Cari software {$jenis['singkatan']} terbaik di {$kota['nama']}, {$kota['provinsi']}. Panduan {$jenis['nama']} — fitur, regulasi, dan rekomendasi aplikasi lengkap.";

        return view('seo.kota-jenis', [
            'kota'           => $kota,
            'jenis'          => $jenis,
            'jenisLain'      => $jenisLain,
            'year'           => $year,
            'brand'          => $brand,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildItemListJsonLd($title, $jenis['fitur_utama']),
        ]);
    }

    /**
     * Kota × Akad Syariah — e.g. /akad-syariah/mudharabah/surabaya
     */
    public function akadSyariahKota(string $akadSlug, string $kotaSlug)
    {
        $akad = $this->findAkad($akadSlug);
        $kota = $this->findKota($kotaSlug);
        abort_if(!$akad || !$kota, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $faqs = $this->buildAkadFaqs($akad);

        $title = "Akad {$akad['nama']} di {$kota['nama']} {$year}: Panduan & Software Syariah";
        $description = "Pelajari akad {$akad['nama']} ({$akad['ringkas']}) untuk koperasi di {$kota['nama']}, {$kota['provinsi']}. Rumus, contoh, fatwa DSN-MUI, dan software {$brand['nama']} pendukung.";

        return view('seo.akad-kota', [
            'akad'           => $akad,
            'kota'           => $kota,
            'year'           => $year,
            'brand'          => $brand,
            'faqs'           => $faqs,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildFaqJsonLd($faqs, $title),
        ]);
    }

    /**
     * Kota × Panduan — e.g. /panduan/cara-mendirikan-koperasi/di-bandung
     */
    public function panduanKota(string $panduanSlug, string $kotaSlug)
    {
        $panduan = collect(config('pseo.panduan'))->firstWhere('slug', $panduanSlug);
        $kota = $this->findKota($kotaSlug);
        abort_if(!$panduan || !$kota, 404);

        $brand = config('pseo.brand');

        $title = "{$panduan['judul']} di {$kota['nama']}, {$kota['provinsi']}";
        $description = "{$panduan['deskripsi']} Khusus untuk pengurus koperasi di {$kota['nama']}, {$kota['provinsi']}. Software {$brand['nama']} siap bantu.";

        return view('seo.panduan-kota', [
            'panduan'        => $panduan,
            'kota'           => $kota,
            'brand'          => $brand,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildArticleJsonLd($panduan),
        ]);
    }

    /**
     * Jenis × Kota (alternate URL pattern) — e.g. /jenis-koperasi/simpan-pinjam/di-jakarta
     */
    public function jenisKoperasiKota(string $jenisSlug, string $kotaSlug)
    {
        return $this->aplikasiKoperasiKotaJenis($kotaSlug, $jenisSlug);
    }

    public function compare(string $slug)
    {
        // slug = "{a}-vs-{b}" — coba semua split point untuk tangani slug bertanda hubung
        $segments = explode('-vs-', $slug);
        abort_if(count($segments) < 2, 404);

        $competitors = collect(config('pseo.competitors'));
        $brandSlug = 'koperasi-app';
        $brandData = ['slug' => $brandSlug, 'nama' => config('pseo.brand.nama'), 'tagline' => config('pseo.brand.tagline')];

        $a = $b = null;
        for ($i = 1; $i < count($segments); $i++) {
            $aSlug = implode('-vs-', array_slice($segments, 0, $i));
            $bSlug = implode('-vs-', array_slice($segments, $i));

            $a = $aSlug === $brandSlug ? $brandData : $competitors->firstWhere('slug', $aSlug);
            $b = $bSlug === $brandSlug ? $brandData : $competitors->firstWhere('slug', $bSlug);

            if ($a && $b) break;
        }

        abort_if(!$a || !$b || $a['slug'] === $b['slug'], 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');

        $title = "{$a['nama']} vs {$b['nama']}: Perbandingan Software Koperasi {$year}";
        $description = "Bandingkan {$a['nama']} dengan {$b['nama']} secara head-to-head: fitur, harga, dukungan syariah, modul akuntansi, dan kelebihan-kekurangan masing-masing aplikasi.";

        return view('seo.compare', [
            'a'              => $a,
            'b'              => $b,
            'brand'          => $brand,
            'year'           => $year,
            'seoTitle'       => $title,
            'seoDescription' => $description,
        ]);
    }

    /**
     * Kecamatan page — /aplikasi-koperasi/kecamatan/{kecamatan}
     */
    public function kecamatanPage(string $kecamatanSlug)
    {
        $kec = $this->findKecamatan($kecamatanSlug);
        abort_if(!$kec, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');

        $title = "Aplikasi Koperasi di {$kec['nama']}, {$kec['kota']} {$year}";
        $description = "Cari software koperasi terbaik di {$kec['nama']}, {$kec['kota']}, {$kec['provinsi']}. Bandingkan fitur, harga, dan modul simpan-pinjam + akuntansi PSAK 27.";

        return view('seo.kecamatan', [
            'kec'            => $kec,
            'year'           => $year,
            'brand'          => $brand,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildItemListJsonLd($title, ['Simpan Pinjam', 'Akuntansi', 'SHU', 'POS']),
        ]);
    }

    /**
     * Kecamatan × Jenis — /aplikasi-koperasi/kecamatan/{kecamatan}/{jenis}
     */
    public function kecamatanJenis(string $kecamatanSlug, string $jenisSlug)
    {
        $kec   = $this->findKecamatan($kecamatanSlug);
        $jenis = $this->findJenis($jenisSlug);
        abort_if(!$kec || !$jenis, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');

        $title = "{$jenis['nama']} di {$kec['nama']}, {$kec['kota']} {$year}";
        $description = "Software {$jenis['singkatan']} terbaik untuk koperasi di {$kec['nama']}, {$kec['kota']}, {$kec['provinsi']}. Fitur lengkap, harga terjangkau.";

        return view('seo.kecamatan-jenis', [
            'kec'            => $kec,
            'jenis'          => $jenis,
            'year'           => $year,
            'brand'          => $brand,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildItemListJsonLd($title, $jenis['fitur_utama']),
        ]);
    }

    /**
     * Kecamatan × Jenis × Akad — /aplikasi-koperasi/kecamatan/{kecamatan}/{jenis}/{akad}
     */
    public function kecamatanJenisAkad(string $kecamatanSlug, string $jenisSlug, string $akadSlug)
    {
        $kec   = $this->findKecamatan($kecamatanSlug);
        $jenis = $this->findJenis($jenisSlug);
        $akad  = $this->findAkad($akadSlug);
        abort_if(!$kec || !$jenis || !$akad, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $faqs = $this->buildAkadFaqs($akad);

        $title = "{$jenis['singkatan']} Akad {$akad['nama']} di {$kec['nama']}, {$kec['kota']}";
        $description = "{$jenis['nama']} dengan akad {$akad['nama']} untuk koperasi di {$kec['nama']}, {$kec['kota']}. Software {$brand['nama']} mendukung semua akad syariah.";

        return view('seo.kecamatan-jenis-akad', [
            'kec'            => $kec,
            'jenis'          => $jenis,
            'akad'           => $akad,
            'year'           => $year,
            'brand'          => $brand,
            'faqs'           => $faqs,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildFaqJsonLd($faqs, $title),
        ]);
    }

    /**
     * Kota × Jenis × Akad — /{kota}/koperasi-{jenis}-{akad}
     */
    public function kotaJenisAkad(string $kotaSlug, string $jenisSlug, string $akadSlug)
    {
        $kota  = $this->findKota($kotaSlug);
        $jenis = $this->findJenis($jenisSlug);
        $akad  = $this->findAkad($akadSlug);
        abort_if(!$kota || !$jenis || !$akad, 404);

        $year = (int) date('Y');
        $brand = config('pseo.brand');
        $faqs = $this->buildAkadFaqs($akad);

        $title = "{$jenis['singkatan']} Akad {$akad['nama']} di {$kota['nama']} {$year}";
        $description = "{$jenis['nama']} dengan akad {$akad['nama']} di {$kota['nama']}, {$kota['provinsi']}. Software {$brand['nama']} mendukung dual-mode konvensional & syariah.";

        return view('seo.kota-jenis-akad', [
            'kota'           => $kota,
            'jenis'          => $jenis,
            'akad'           => $akad,
            'year'           => $year,
            'brand'          => $brand,
            'faqs'           => $faqs,
            'seoTitle'       => $title,
            'seoDescription' => $description,
            'jsonLd'         => $this->buildFaqJsonLd($faqs, $title),
        ]);
    }

    /* ----------------------------- Helpers ----------------------------- */

    private function findKecamatan(string $slug): ?array
    {
        $file = config_path('pseo-kecamatan.php');
        if (!file_exists($file)) return null;
        $data = include $file;
        return collect($data['kecamatan'] ?? [])->firstWhere('slug', $slug);
    }

    private function findKota(string $slug): ?array
    {
        $kota = collect(config('pseo.kota'))->firstWhere('slug', $slug);
        if ($kota) {
            $kota['jumlah_koperasi'] = $kota['jumlah_koperasi'] ?? $this->estimasiKoperasi($kota['nama']);
        }
        return $kota;
    }

    private function estimasiKoperasi(string $nama): int
    {
        $hash = abs(crc32($nama));
        return ($hash % 400) + 100; // 100–500 koperasi per kota
    }

    private function findJenis(string $slug): ?array
    {
        return collect(config('pseo.jenis'))->firstWhere('slug', $slug);
    }

    private function findAkad(string $slug): ?array
    {
        return collect(config('pseo.akad'))->firstWhere('slug', $slug);
    }

    private function kotaTerkait(string $excludeSlug): Collection
    {
        return collect(config('pseo.kota'))
            ->reject(fn ($k) => $k['slug'] === $excludeSlug)
            ->shuffle()
            ->take(8)
            ->values();
    }

    private function buildItemListJsonLd(string $name, array $items): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type'    => 'ItemList',
            'name'     => $name,
            'itemListElement' => collect($items)->map(fn ($item, $i) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $item,
            ])->all(),
        ];
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function buildFaqJsonLd(array $faqs, string $name): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'name'     => $name,
            'mainEntity' => collect($faqs)->map(fn ($f) => [
                '@type' => 'Question',
                'name'  => $f['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $f['a'],
                ],
            ])->all(),
        ];
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function buildArticleJsonLd(array $panduan): string
    {
        $brand = config('pseo.brand.nama');
        $payload = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $panduan['judul'],
            'description'   => $panduan['deskripsi'],
            'datePublished' => date('Y-m-d'),
            'dateModified'  => date('Y-m-d'),
            'author'        => ['@type' => 'Organization', 'name' => $brand],
            'publisher'     => ['@type' => 'Organization', 'name' => $brand],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
        ];
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function buildBreadcrumbJsonLd(array $items): string
    {
        $listItems = [];
        foreach ($items as $i => $item) {
            $listItems[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $item['name'],
                'item'     => $item['url'],
            ];
        }
        $payload = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** @return array<int, array{q:string,a:string}> */
    private function buildJenisFaqs(array $jenis): array
    {
        return [
            ['q' => "Apa itu {$jenis['nama']} ({$jenis['singkatan']})?",
             'a' => $jenis['deskripsi']],
            ['q' => "Apa fitur utama yang dibutuhkan software {$jenis['singkatan']}?",
             'a' => "Fitur utama {$jenis['singkatan']}: " . implode(', ', $jenis['fitur_utama']) . ". Software " . config('pseo.brand.nama') . " sudah mencakup semuanya dalam 1 platform."],
            ['q' => "Berapa biaya software untuk {$jenis['nama']}?",
             'a' => config('pseo.brand.nama') . " mulai dari " . config('pseo.brand.harga_mulai') . " untuk paket dasar — sudah termasuk semua modul {$jenis['singkatan']} tanpa batas anggota."],
            ['q' => "Apa regulasi yang mengatur {$jenis['nama']}?",
             'a' => "Regulasi yang berlaku: {$jenis['regulasi']}. Software " . config('pseo.brand.nama') . " menyesuaikan format laporan dengan ketentuan tersebut."],
            ['q' => "Bisakah migrasi dari Excel ke software {$jenis['singkatan']}?",
             'a' => "Bisa. " . config('pseo.brand.nama') . " menyediakan template impor data anggota, simpanan, dan pinjaman dari Excel/CSV. Tim support membantu migrasi awal gratis."],
        ];
    }

    /** @return array<int, array{q:string,a:string}> */
    private function buildAkadFaqs(array $akad): array
    {
        return [
            ['q' => "Apa itu akad {$akad['nama']}?",
             'a' => $akad['deskripsi']],
            ['q' => "Bagaimana rumus perhitungan {$akad['nama']}?",
             'a' => "Rumus standar: {$akad['rumus']}. Contoh kasus: {$akad['contoh']}"],
            ['q' => "Apa dasar fatwa DSN-MUI untuk akad {$akad['nama']}?",
             'a' => "Dasar fatwa: {$akad['fatwa']}. Ini adalah fatwa resmi Dewan Syariah Nasional - Majelis Ulama Indonesia yang menjadi rujukan koperasi syariah di Indonesia."],
            ['q' => "Apakah software " . config('pseo.brand.nama') . " mendukung akad {$akad['nama']}?",
             'a' => "Ya. " . config('pseo.brand.nama') . " mendukung " . count(config('pseo.akad')) . " akad syariah lengkap termasuk {$akad['nama']}. Setiap produk pinjaman/pembiayaan bisa dipilih akadnya sesuai kebutuhan, dengan jurnal akuntansi otomatis."],
            ['q' => "Apa beda {$akad['nama']} dengan bunga konvensional?",
             'a' => "Bunga konvensional adalah persentase tetap atas pokok pinjaman tanpa keterkaitan dengan hasil usaha (riba). Akad {$akad['nama']} berbasis prinsip syariah ({$akad['kategori']}) dan halal sesuai fatwa DSN-MUI — sumber pendapatan jelas dan tidak mengandung riba."],
        ];
    }
}
