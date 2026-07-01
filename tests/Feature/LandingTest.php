<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Koperasi');
    }

    public function test_docs_page_returns_200(): void
    {
        $response = $this->get('/docs');
        $response->assertStatus(200);
    }

    public function test_robots_txt_returns_correct_content_type(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Disallow: /admin');
    }

    public function test_sitemap_index_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    // ─── PSEO Routes ───

    public function test_pseo_kota_page_returns_200(): void
    {
        $response = $this->get('/aplikasi-koperasi/jakarta-pusat');
        $response->assertStatus(200);
        $response->assertSee('Jakarta Pusat');
    }

    public function test_pseo_kota_page_not_found(): void
    {
        $response = $this->get('/aplikasi-koperasi/kota-tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_jenis_page_returns_200(): void
    {
        $response = $this->get('/jenis-koperasi/simpan-pinjam');
        $response->assertStatus(200);
        $response->assertSee('Simpan Pinjam');
    }

    public function test_pseo_jenis_page_not_found(): void
    {
        $response = $this->get('/jenis-koperasi/jenis-tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_akad_page_returns_200(): void
    {
        $response = $this->get('/akad-syariah/murabahah');
        $response->assertStatus(200);
        $response->assertSee('Murabahah');
    }

    public function test_pseo_akad_page_not_found(): void
    {
        $response = $this->get('/akad-syariah/akad-tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_panduan_page_returns_200(): void
    {
        $response = $this->get('/panduan/cara-mendirikan-koperasi');
        $response->assertStatus(200);
        $response->assertSee('Mendirikan Koperasi');
    }

    public function test_pseo_panduan_page_not_found(): void
    {
        $response = $this->get('/panduan/tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_kalkulator_page_returns_200(): void
    {
        $response = $this->get('/kalkulator/cicilan-pinjaman');
        $response->assertStatus(200);
        $response->assertSee('Cicilan');
    }

    public function test_pseo_kalkulator_page_not_found(): void
    {
        $response = $this->get('/kalkulator/tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_alternatives_page_returns_200(): void
    {
        $response = $this->get('/alternatif-siska');
        $response->assertStatus(200);
    }

    public function test_pseo_alternatives_page_not_found(): void
    {
        $response = $this->get('/alternatif-tidak-ada');
        $response->assertStatus(404);
    }

    public function test_pseo_compare_page_returns_200(): void
    {
        $response = $this->get('/bandingkan/koperasi-app-vs-siska');
        $response->assertStatus(200);
    }

    public function test_pseo_compare_invalid_slug_returns_404(): void
    {
        $response = $this->get('/bandingkan/a-vs-b');
        $response->assertStatus(404);
    }

    public function test_pseo_kota_jenis_combo_page_returns_200(): void
    {
        $response = $this->get('/aplikasi-koperasi/jakarta-pusat/simpan-pinjam');
        $response->assertStatus(200);
    }

    public function test_simulasi_pinjaman_page_returns_200(): void
    {
        $response = $this->get('/simulasi-pinjaman');
        $response->assertStatus(200);
    }
}
