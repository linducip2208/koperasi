<?php

namespace App\Services\Seo;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    protected string $key;
    protected string $keyLocation;
    protected array $searchEngines = [
        'https://www.bing.com/indexnow',
        'https://yandex.com/indexnow',
        'https://search.seznam.cz/indexnow',
        'https://indexnow.naver.com/indexnow',
    ];

    public function __construct()
    {
        $this->key = trim((string) @file_get_contents(public_path('indexnow-key.txt')));
        $this->keyLocation = config('app.url') . '/indexnow-key.txt';
    }

    public function submit(array $urls): array
    {
        $results = [];
        $payload = [
            'host'        => parse_url(config('app.url'), PHP_URL_HOST),
            'key'         => $this->key,
            'keyLocation' => $this->keyLocation,
            'urlList'     => array_values($urls),
        ];

        foreach ($this->searchEngines as $engine) {
            try {
                $response = Http::timeout(15)->post($engine, $payload);
                $results[$engine] = [
                    'status'  => $response->status(),
                    'success' => $response->successful(),
                ];
                Log::info("IndexNow: submitted " . count($urls) . " URLs to {$engine}", [
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $e) {
                $results[$engine] = ['status' => 0, 'success' => false, 'error' => $e->getMessage()];
                Log::warning("IndexNow: failed to submit to {$engine}: " . $e->getMessage());
            }
        }

        return $results;
    }

    public function submitAll(): array
    {
        $allUrls = $this->buildAllUrls();
        $results = [];

        foreach (array_chunk($allUrls, 5000) as $chunk) {
            $results[] = $this->submit($chunk);
            sleep(1);
        }

        return $results;
    }

    public function submitSingle(string $url): array
    {
        return $this->submit([$url]);
    }

    public function submitNewOnly(array $urls): array
    {
        $cacheKey = 'indexnow_last_submit';
        $submitted = Cache::get($cacheKey, []);

        $newUrls = array_diff($urls, $submitted);
        if (empty($newUrls)) {
            return [];
        }

        $results = $this->submit($newUrls);

        $merged = array_slice(array_unique(array_merge($submitted, $newUrls)), -50000);
        Cache::put($cacheKey, $merged, now()->addYear());

        return $results;
    }

    public static function generateKey(): string
    {
        $key = bin2hex(random_bytes(32));
        file_put_contents(public_path('indexnow-key.txt'), $key);
        return $key;
    }

    protected function buildAllUrls(): array
    {
        $urls = [];
        $base = rtrim(config('app.url'), '/');

        $urls[] = $base . '/';
        $urls[] = $base . '/docs';
        $urls[] = $base . '/demo';
        $urls[] = $base . '/blog';

        $categories = \App\Models\BlogCategory::all();
        foreach ($categories as $cat) {
            $urls[] = $base . '/blog/category/' . $cat->slug;
        }

        $posts = \App\Models\BlogPost::where('is_published', true)
            ->where('published_at', '<=', now())
            ->get();
        foreach ($posts as $post) {
            $urls[] = $base . '/blog/' . $post->slug;
        }

        $cities = collect(config('pseo.kota'))->take(100);
        foreach ($cities as $kota) {
            $urls[] = $base . '/aplikasi-koperasi/' . $kota['slug'];
            foreach (collect(config('pseo.jenis'))->take(3) as $jenis) {
                $urls[] = $base . '/aplikasi-koperasi/' . $kota['slug'] . '/' . $jenis['slug'];
            }
        }

        foreach (config('pseo.jenis') as $jenis) {
            $urls[] = $base . '/jenis-koperasi/' . $jenis['slug'];
        }

        foreach (config('pseo.akad') as $akad) {
            $urls[] = $base . '/akad-syariah/' . $akad['slug'];
        }

        foreach (config('pseo.panduan') as $panduan) {
            $urls[] = $base . '/panduan/' . $panduan['slug'];
        }

        foreach (config('pseo.kalkulator') as $kalkulator) {
            $urls[] = $base . '/kalkulator/' . $kalkulator['slug'];
        }

        foreach (config('pseo.competitors') as $comp) {
            $urls[] = $base . '/alternatif-' . $comp['slug'];
        }

        return $urls;
    }
}
