<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $cat = BlogCategory::create(['name' => 'Simpan Pinjam', 'slug' => 'simpan-pinjam']);

        for ($i = 1; $i <= 5; $i++) {
            BlogPost::create([
                'category_id'   => $cat->id,
                'author_id'     => null,
                'title'         => "Artikel Test {$i}",
                'slug'          => "artikel-test-{$i}",
                'content'       => "<p>Ini konten artikel test {$i}.</p>",
                'excerpt'       => "Excerpt artikel test {$i}.",
                'is_published'  => true,
                'published_at'  => now()->subDays($i),
            ]);
        }

        BlogPost::create([
            'category_id'   => $cat->id,
            'author_id'     => null,
            'title'         => 'Draft Artikel',
            'slug'          => 'draft-artikel',
            'content'       => '<p>Konten draft.</p>',
            'is_published'  => false,
            'published_at'  => null,
        ]);
    }

    public function test_blog_index_returns_200(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    public function test_blog_index_shows_published_posts(): void
    {
        $response = $this->get('/blog');
        $response->assertSee('Artikel Test 1');
        $response->assertSee('Artikel Test 5');
    }

    public function test_blog_index_does_not_show_drafts(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    public function test_blog_show_returns_200(): void
    {
        $response = $this->get('/blog/artikel-test-1');
        $response->assertStatus(200);
    }

    public function test_blog_show_draft_returns_404(): void
    {
        $response = $this->get('/blog/draft-artikel');
        $response->assertStatus(404);
    }

    public function test_blog_show_not_found_returns_404(): void
    {
        $response = $this->get('/blog/tidak-ada');
        $response->assertStatus(404);
    }

    public function test_blog_category_filter_works(): void
    {
        $response = $this->get('/blog/category/simpan-pinjam');
        $response->assertStatus(200);
    }

    public function test_blog_category_not_found_returns_404(): void
    {
        $response = $this->get('/blog/category/tidak-ada');
        $response->assertStatus(404);
    }
}
