<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    /* ------------------------------------------------------------------ */
    /*  Sitemap Engine                                                     */
    /* ------------------------------------------------------------------ */

    private function buildAllUrls(): array
    {
        $base = rtrim(config('app.url'), '/');
        $urls = [];

        $add = function ($url, $freq, $pri) use (&$urls) {
            $urls[] = ['loc' => $url, 'changefreq' => $freq, 'priority' => $pri];
        };

        // Static
        $add("{$base}/", 'weekly', '1.0');
        $add("{$base}/docs", 'monthly', '0.9');
        $add("{$base}/demo", 'monthly', '0.8');
        $add("{$base}/simulasi-pinjaman", 'monthly', '0.8');

        // Core PSEO
        foreach (config('pseo.kota', []) as $v) {
            $add("{$base}/aplikasi-koperasi/{$v['slug']}", 'weekly', '0.7');
        }
        foreach (config('pseo.jenis', []) as $v) {
            $add("{$base}/jenis-koperasi/{$v['slug']}", 'monthly', '0.8');
        }
        foreach (config('pseo.akad', []) as $v) {
            $add("{$base}/akad-syariah/{$v['slug']}", 'monthly', '0.7');
        }
        foreach (config('pseo.panduan', []) as $v) {
            $add("{$base}/panduan/{$v['slug']}", 'monthly', '0.7');
        }
        foreach (config('pseo.kalkulator', []) as $v) {
            $add("{$base}/kalkulator/{$v['slug']}", 'monthly', '0.6');
        }

        // Alternatives & Compare
        $brandSlug = 'koperasi-app';
        foreach (config('pseo.competitors', []) as $v) {
            if ($v['slug'] === $brandSlug) continue;
            $add("{$base}/alternatif-{$v['slug']}", 'monthly', '0.6');
            $add("{$base}/bandingkan/{$brandSlug}-vs-{$v['slug']}", 'monthly', '0.6');
        }

        // Combo: kota × jenis
        foreach (config('pseo.kota', []) as $kota) {
            foreach (config('pseo.jenis', []) as $jenis) {
                $add("{$base}/aplikasi-koperasi/{$kota['slug']}/{$jenis['slug']}", 'monthly', '0.5');
                $add("{$base}/jenis-koperasi/{$jenis['slug']}/di-{$kota['slug']}", 'monthly', '0.5');
                $add("{$base}/{$kota['slug']}/koperasi-{$jenis['slug']}", 'monthly', '0.5');
            }
            foreach (config('pseo.akad', []) as $akad) {
                $add("{$base}/akad-syariah/{$akad['slug']}/di/{$kota['slug']}", 'monthly', '0.5');
            }
        }

        // Blog
        $add("{$base}/blog", 'weekly', '0.8');
        foreach (\App\Models\BlogCategory::all() as $cat) {
            $add("{$base}/blog/category/{$cat->slug}", 'weekly', '0.6');
        }
        foreach (\App\Models\BlogPost::where('is_published', true)->where('published_at', '<=', now())->get() as $post) {
            $add("{$base}/blog/{$post->slug}", 'monthly', '0.7');
        }

        // Combo: panduan × kota
        foreach (config('pseo.panduan', []) as $panduan) {
            foreach (config('pseo.kota', []) as $kota) {
                $add("{$base}/panduan/{$panduan['slug']}/di-{$kota['slug']}", 'monthly', '0.5');
            }
        }

        // Source Code Marketing PSEO (30% volume)
        $add("{$base}/beli-aplikasi-koperasi", 'weekly', '0.9');
        foreach (config('pseo.kota', []) as $v) {
            $add("{$base}/source-code-koperasi-{$v['slug']}", 'monthly', '0.6');
        }
        foreach (config('pseo.jenis', []) as $v) {
            $add("{$base}/beli-aplikasi-koperasi-{$v['slug']}", 'monthly', '0.7');
        }
        foreach (config('pseo.pseo-source-features', []) as $f) {
            $add("{$base}/aplikasi-koperasi-{$f}", 'monthly', '0.6');
        }
        foreach (array_keys(\App\Http\Controllers\SourceCodeSeoController::crossSellApps()) as $app) {
            $add("{$base}/beli-aplikasi-{$app}", 'monthly', '0.5');
        }
        foreach (config('pseo.kota', []) as $kota) {
            foreach (config('pseo.jenis', []) as $jenis) {
                $add("{$base}/source-code-koperasi-{$kota['slug']}-{$jenis['slug']}", 'monthly', '0.5');
            }
        }

        // Kecamatan PSEO (7K+ kecamatan)
        $kecFile = config_path('pseo-kecamatan.php');
        if (file_exists($kecFile)) {
            $kecData = include $kecFile;
            $kecamatan = $kecData['kecamatan'] ?? [];
            $kecCount = min(count($kecamatan), 2000); // limit to avoid timeout
            foreach (array_slice($kecamatan, 0, $kecCount) as $kec) {
                $add("{$base}/aplikasi-koperasi/kecamatan/{$kec['slug']}", 'monthly', '0.5');
            }
            foreach (array_slice($kecamatan, 0, min(500, $kecCount)) as $kec) {
                foreach (config('pseo.jenis', []) as $jenis) {
                    $add("{$base}/aplikasi-koperasi/kecamatan/{$kec['slug']}/{$jenis['slug']}", 'monthly', '0.4');
                }
            }
            foreach (array_slice($kecamatan, 0, min(200, $kecCount)) as $kec) {
                foreach (collect(config('pseo.jenis', []))->take(3) as $jenis) {
                    foreach (collect(config('pseo.akad', []))->take(4) as $akad) {
                        $add("{$base}/aplikasi-koperasi/kecamatan/{$kec['slug']}/{$jenis['slug']}/{$akad['slug']}", 'monthly', '0.4');
                    }
                }
            }
        }

        // Kota × Jenis × Akad 3-way combo
        foreach (collect(config('pseo.kota', []))->take(100) as $kota) {
            foreach (collect(config('pseo.jenis', []))->take(4) as $jenis) {
                foreach (config('pseo.akad', []) as $akad) {
                    $add("{$base}/{$kota['slug']}/koperasi-{$jenis['slug']}-{$akad['slug']}", 'monthly', '0.4');
                }
            }
        }

        return $urls;
    }

    private function renderSitemapXml(array $urls): string
    {
        $now = now()->toIso8601String();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n    <loc>{$u['loc']}</loc>\n    <lastmod>{$now}</lastmod>\n    <changefreq>{$u['changefreq']}</changefreq>\n    <priority>{$u['priority']}</priority>\n  </url>\n";
        }
        $xml .= '</urlset>';
        return $xml;
    }

    private function renderSitemapIndex(array $chunks, int $total): string
    {
        $now = now()->toIso8601String();
        $base = rtrim(config('app.url'), '/');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($chunks as $i => $chunk) {
            $n = $i + 1;
            $xml .= "  <sitemap>\n    <loc>{$base}/sitemap{$n}.xml</loc>\n    <lastmod>{$now}</lastmod>\n  </sitemap>\n";
        }
        $xml .= '</sitemapindex>';
        return $xml;
    }

    /* ------------------------------------------------------------------ */
    /*  Sitemap Routes                                                     */
    /* ------------------------------------------------------------------ */

    public function sitemap()
    {
        $cacheKey = 'sitemap-index';
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) {
            return response($cached, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
        }

        @ini_set('memory_limit', '256M');
        $allUrls = $this->buildAllUrls();
        $chunks = array_chunk($allUrls, 35000);

        $xml = $this->renderSitemapIndex($chunks, count($allUrls));
        \Illuminate\Support\Facades\Cache::put($cacheKey, $xml, now()->addHours(24));

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function sitemapChunk(string $id)
    {
        $n = (int) $id;
        $cacheKey = "sitemap-chunk-{$n}";
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) {
            return response($cached, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
        }

        @ini_set('memory_limit', '512M');
        $allUrls = $this->buildAllUrls();
        $chunks = array_chunk($allUrls, 35000);
        $index = $n - 1;

        if (!isset($chunks[$index])) {
            abort(404);
        }

        $xml = $this->renderSitemapXml($chunks[$index]);
        \Illuminate\Support\Facades\Cache::put($cacheKey, $xml, now()->addHours(12));

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /* ------------------------------------------------------------------ */
    /*  Robots.txt                                                         */
    /* ------------------------------------------------------------------ */

    public function robots()
    {
        $base = rtrim(config('app.url'), '/');
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Allow: /docs',
            'Allow: /marketing/',
            'Allow: /aplikasi-koperasi/',
            'Allow: /jenis-koperasi/',
            'Allow: /akad-syariah/',
            'Allow: /panduan/',
            'Allow: /kalkulator/',
            'Allow: /alternatif-',
            'Allow: /bandingkan/',
            'Allow: /simulasi-pinjaman',
            'Allow: /daftar',
            'Disallow: /admin',
            'Disallow: /portal',
            'Disallow: /activation',
            'Disallow: /api',
            'Disallow: /laporan',
            'Disallow: /__pair',
            '',
            "Sitemap: {$base}/sitemap.xml",
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }
}
