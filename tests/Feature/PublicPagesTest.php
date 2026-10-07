<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_publik_valid_200(): void
    {
        $this->withoutExceptionHandling();
        $this->get('/aplikasi-koperasi/jakarta-barat')->assertOk();
        $this->get('/jenis-koperasi/syariah/di-jakarta-barat')->assertOk();
        $this->get('/akad-syariah/mudharabah')->assertOk();
        $this->get('/demo')->assertOk();
        $this->get('/blog/feed.xml')->assertOk();
    }

    public function test_slug_invalid_404_bukan_500(): void
    {
        $this->get('/aplikasi-koperasi/jakarta')->assertNotFound();
        $this->get('/jenis-koperasi/syariah/di-jakarta')->assertNotFound();
    }
}
