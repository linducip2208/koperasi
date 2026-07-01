<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\View\View;

class SourceCodeSeoController extends Controller
{
    /**
     * Landing page: "Beli Source Code Aplikasi Koperasi"
     */
    public function landing(): View
    {
        $brand = config('pseo.brand');
        $apps = $this->crossSellApps();
        $faqs = $this->sourceCodeFaqs();

        return view('seo.source-code-landing', [
            'brand'          => $brand,
            'apps'           => $apps,
            'faqs'           => $faqs,
            'seoTitle'       => 'Beli Source Code Aplikasi Koperasi — Konvensional & Syariah | WA 0812-9605-2010',
            'seoDescription' => 'Beli source code aplikasi koperasi lengkap. Konvensional & Syariah, multi-tenant ready, Filament admin panel, portal anggota. Instalasi 10 menit. WA 0812-9605-2010.',
            'canonical'      => url('/beli-aplikasi-koperasi'),
            'jsonLd'         => $this->productJsonLd(),
        ]);
    }

    /**
     * Per kota: "Jual Source Code Aplikasi Koperasi di {kota}"
     */
    public function kota(string $kotaSlug): View
    {
        $kota = collect(config('pseo.kota'))->firstWhere('slug', $kotaSlug);
        abort_if(!$kota, 404);

        $brand = config('pseo.brand');

        return view('seo.source-code-kota', [
            'kota'           => $kota,
            'brand'          => $brand,
            'seoTitle'       => "Jual Source Code Aplikasi Koperasi di {$kota['nama']} — Siap Pakai | WA 0812-9605-2010",
            'seoDescription' => "Beli source code aplikasi koperasi untuk {$kota['nama']}. Instalasi 10 menit, bisa direbrand. Konvensional & Syariah lengkap. WA 0812-9605-2010.",
            'canonical'      => url("/source-code-koperasi-{$kota['slug']}"),
        ]);
    }

    /**
     * Per jenis koperasi: "Beli Aplikasi Koperasi Simpan Pinjam"
     */
    public function jenis(string $jenisSlug): View
    {
        $jenis = collect(config('pseo.jenis'))->firstWhere('slug', $jenisSlug);
        abort_if(!$jenis, 404);

        $brand = config('pseo.brand');

        return view('seo.source-code-jenis', [
            'jenis'          => $jenis,
            'brand'          => $brand,
            'seoTitle'       => "Beli Aplikasi {$jenis['nama']} ({$jenis['singkatan']}) — Source Code | WA 0812-9605-2010",
            'seoDescription' => "Source code aplikasi {$jenis['nama']} siap pakai. {$jenis['tagline']}. Instalasi 10 menit, support 1 tahun. WA 0812-9605-2010.",
            'canonical'      => url("/beli-aplikasi-koperasi-{$jenis['slug']}"),
        ]);
    }

    /**
     * Per fitur aplikasi: "Aplikasi Koperasi dengan {fitur}"
     */
    public function fitur(string $fitur): View
    {
        $features = [
            'akuntansi-otomatis'    => ['nama' => 'Akuntansi Otomatis', 'desc' => 'Jurnal otomatis setiap transaksi, laporan PSAK 27'],
            'portal-anggota'        => ['nama' => 'Portal Anggota', 'desc' => 'Anggota cek saldo & pinjaman via HP'],
            'mobile-android'        => ['nama' => 'Mobile Android', 'desc' => 'Aplikasi mobile untuk anggota koperasi'],
            'laporan-keuangan'      => ['nama' => 'Laporan Keuangan', 'desc' => 'Neraca, Laba Rugi, Arus Kas otomatis'],
            'pos-toko'              => ['nama' => 'POS / Toko', 'desc' => 'Point of Sale untuk unit pertokoan koperasi'],
            'multi-cabang'          => ['nama' => 'Multi Cabang', 'desc' => 'Kelola banyak cabang dalam 1 dashboard'],
            'shu-otomatis'          => ['nama' => 'SHU Otomatis', 'desc' => 'Hitung & distribusikan SHU per anggota'],
            'notifikasi-whatsapp'   => ['nama' => 'Notifikasi WhatsApp', 'desc' => 'Konfirmasi setoran & reminder cicilan via WA'],
            'keamanan-data'         => ['nama' => 'Keamanan Data', 'desc' => 'Enkripsi AES-256, backup otomatis harian'],
        ];

        abort_unless(isset($features[$fitur]), 404);
        $f = $features[$fitur];
        $brand = config('pseo.brand');

        return view('seo.source-code-fitur', [
            'fitur'          => $f,
            'fiturSlug'      => $fitur,
            'brand'          => $brand,
            'seoTitle'       => "Aplikasi Koperasi dengan {$f['nama']} — Source Code | WA 0812-9605-2010",
            'seoDescription' => "{$f['desc']}. Source code aplikasi koperasi lengkap, siap pakai. WA 0812-9605-2010.",
            'canonical'      => url("/aplikasi-koperasi-{$fitur}"),
        ]);
    }

    /**
     * Combo: kota × jenis for source code marketing
     */
    public function kotaJenis(string $kotaSlug, string $jenisSlug): View
    {
        $kota  = collect(config('pseo.kota'))->firstWhere('slug', $kotaSlug);
        $jenis = collect(config('pseo.jenis'))->firstWhere('slug', $jenisSlug);
        abort_if(!$kota || !$jenis, 404);

        $brand = config('pseo.brand');

        return view('seo.source-code-kota-jenis', [
            'kota'           => $kota,
            'jenis'          => $jenis,
            'brand'          => $brand,
            'seoTitle'       => "Beli Aplikasi {$jenis['singkatan']} di {$kota['nama']} — Source Code | WA 0812-9605-2010",
            'seoDescription' => "Source code aplikasi {$jenis['nama']} untuk koperasi di {$kota['nama']}, {$kota['provinsi']}. Siap pakai, support penuh. WA 0812-9605-2010.",
            'canonical'      => url("/source-code-koperasi-{$kota['slug']}-{$jenis['slug']}"),
        ]);
    }

    /**
     * Cross-sell: aplikasi lain
     */
    public function crossSell(string $app): View
    {
        $apps = $this->crossSellApps();
        abort_unless(isset($apps[$app]), 404);

        $data = $apps[$app];
        $brand = config('pseo.brand');

        return view('seo.source-code-cross-sell', [
            'app'            => $data,
            'appSlug'        => $app,
            'brand'          => $brand,
            'seoTitle'       => "Beli Source Code Aplikasi {$data['nama']} — Siap Pakai | WA 0812-9605-2010",
            'seoDescription' => "{$data['desc']} Source code lengkap, instalasi mudah, support penuh. WA 0812-9605-2010.",
            'canonical'      => url("/beli-aplikasi-{$app}"),
        ]);
    }

    /* ----------------------------- Helpers ----------------------------- */

    public static function crossSellApps(): array
    {
        return [
            'hotel' => [
                'nama' => 'Hotel & Resort',
                'slug' => 'hotel',
                'desc' => 'Software manajemen hotel lengkap: booking engine, front desk, housekeeping, channel manager.',
                'icon' => '🏨',
            ],
            'laundry' => [
                'nama' => 'Laundry',
                'slug' => 'laundry',
                'desc' => 'Aplikasi laundry: tracking order, timbangan digital, membership, laporan harian.',
                'icon' => '👕',
            ],
            'inventory' => [
                'nama' => 'Inventory & Gudang',
                'slug' => 'inventory',
                'desc' => 'Manajemen stok multi-gudang, barcode, FIFO/LIFO, reorder otomatis.',
                'icon' => '📦',
            ],
            'foodscan' => [
                'nama' => 'FoodScan AI',
                'slug' => 'foodscan',
                'desc' => 'AI scanner makanan: deteksi kalori, alergen, cocok untuk restoran & katering.',
                'icon' => '🍜',
            ],
            'klinik' => [
                'nama' => 'Klinik & Apotek',
                'slug' => 'klinik',
                'desc' => 'Sistem informasi klinik: antrian, rekam medis, apotek, billing BPJS.',
                'icon' => '🏥',
            ],
            'sekolah' => [
                'nama' => 'Sekolah',
                'slug' => 'sekolah',
                'desc' => 'Sistem akademik: rapor digital, absensi, perpustakaan, pembayaran SPP.',
                'icon' => '🏫',
            ],
            'travel' => [
                'nama' => 'Travel & Umroh',
                'slug' => 'travel',
                'desc' => 'Aplikasi travel: paket tour, manifest pesawat, visa, booking hotel.',
                'icon' => '✈️',
            ],
            'event' => [
                'nama' => 'Event Organizer',
                'slug' => 'event',
                'desc' => 'Manajemen event: ticketing, check-in QR, vendor management, sponsorship.',
                'icon' => '🎪',
            ],
        ];
    }

    private function sourceCodeFaqs(): array
    {
        return [
            ['q' => 'Apakah saya dapat source code lengkap?',
             'a' => 'Ya. Anda mendapatkan 100% source code (bukan SaaS/berlangganan). Bisa dimodifikasi, direbrand, dihosting sendiri, bahkan dijual kembali.'],
            ['q' => 'Berapa harga source code aplikasi koperasi?',
             'a' => 'Harga mulai dari Rp 3.000.000 untuk paket regular (1 domain). Tersedia juga paket whitelabel dengan hak jual kembali. Hubungi WA 0812-9605-2010 untuk detail.'],
            ['q' => 'Apakah bisa request custom fitur?',
             'a' => 'Bisa. Kami sediakan layanan kustomisasi sesuai kebutuhan koperasi Anda. Biaya tergantung kompleksitas fitur.'],
            ['q' => 'Apakah ada demo yang bisa dicoba?',
             'a' => 'Ya, kunjungi halaman demo untuk mencoba semua fitur. Akun demo tersedia untuk admin, manager, kasir, dan AO.'],
            ['q' => 'Teknologi apa yang digunakan?',
             'a' => 'Laravel 12 + Filament 3 admin panel + MySQL. Frontend pakai TailwindCSS, portal anggota PWA-ready. Full-stack modern.'],
            ['q' => 'Apakah termasuk update?',
             'a' => 'Semua paket termasuk 1 tahun update gratis. Update mencakup bug fix, security patch, dan fitur baru minor.'],
        ];
    }

    private function productJsonLd(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => 'Source Code Aplikasi Koperasi',
            'description' => 'Software koperasi lengkap konvensional & syariah. 15+ modul, multi-tenant ready.',
            'brand'    => ['@type' => 'Brand', 'name' => 'KoperasiApp'],
            'offers'   => [
                '@type'         => 'Offer',
                'price'         => '3000000',
                'priceCurrency' => 'IDR',
                'availability'  => 'https://schema.org/InStock',
                'url'           => url('/beli-aplikasi-koperasi'),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
