<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->where('is_published', true)->where('published_at', '<=', now())])->get();
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->paginate(12);
        $recentPosts = BlogPost::with('category')->published()->latest('published_at')->limit(5)->get();
        $totalPosts = BlogPost::published()->count();

        $seoTitle = 'Blog Koperasi — Artikel, Panduan & Berita Koperasi Indonesia';
        $seoDescription = 'Artikel, panduan, dan berita seputar koperasi Indonesia — simpan pinjam, syariah, akuntansi, SHU, dan digitalisasi koperasi.';

        $jsonLd = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'Blog',
            'name'     => 'Blog KoperasiApp',
            'url'      => url('/blog'),
            'description' => $seoDescription,
            'blogPost' => $posts->map(fn ($p) => [
                '@type' => 'BlogPosting',
                'headline' => $p->title,
                'url' => url('/blog/' . $p->slug),
                'datePublished' => optional($p->published_at)->toIso8601String(),
                'dateModified' => $p->updated_at->toIso8601String(),
            ])->toArray(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('blog.index', compact(
            'posts', 'categories', 'recentPosts', 'totalPosts', 'seoTitle', 'seoDescription', 'jsonLd'
        ));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with(['category', 'author'])->where('slug', $slug)->published()->firstOrFail();
        $categories = BlogCategory::all();
        $recentPosts = BlogPost::with('category')->published()->where('id', '!=', $post->id)->latest('published_at')->limit(5)->get();
        $relatedPosts = BlogPost::with('category')->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id)->orWhere(fn ($q) => $q->whereNull('category_id')))
            ->latest('published_at')
            ->limit(3)
            ->get();

        $seoTitle = $post->meta_title ?: $post->title;
        $seoDescription = $post->meta_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 155));

        $jsonLd = json_encode([
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $post->title,
            'description'   => strip_tags($post->excerpt ?? ''),
            'image'         => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified'  => $post->updated_at->toIso8601String(),
            'author'        => ['@type' => 'Person', 'name' => $post->author?->name ?? 'KoperasiApp'],
            'publisher'     => ['@type' => 'Organization', 'name' => 'KoperasiApp'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('blog.show', compact(
            'post', 'categories', 'recentPosts', 'relatedPosts', 'seoTitle', 'seoDescription', 'jsonLd'
        ));
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->where('is_published', true)->where('published_at', '<=', now())])->get();
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->byCategory($slug)
            ->latest('published_at')
            ->paginate(12);
        $recentPosts = BlogPost::with('category')->published()->latest('published_at')->limit(5)->get();
        $totalPosts = BlogPost::published()->byCategory($slug)->count();

        $seoTitle = "Kategori: {$category->name} — Blog KoperasiApp";
        $seoDescription = $category->description ?: "Artikel seputar {$category->name} — koperasi Indonesia.";

        $jsonLd = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'Blog',
            'name'     => "Blog KoperasiApp — {$category->name}",
            'url'      => url('/blog/category/' . $slug),
            'description' => $seoDescription,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('blog.index', compact(
            'posts', 'categories', 'recentPosts', 'totalPosts', 'seoTitle', 'seoDescription', 'jsonLd'
        ));
    }
}
