@extends('pseo._layout', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => url('/blog/' . $post->slug),
    'jsonLd' => $jsonLd,
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => url('/')],
        ['label' => 'Blog', 'url' => url('/blog')],
        ['label' => $post->title, 'url' => url()->current()],
    ],
    'heroH1' => null,
    'heroSub' => null,
])

@section('content')
<article class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
    @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}"
             alt="{{ $post->title }}"
             class="w-full h-64 sm:h-96 object-cover"
             loading="eager">
    @endif

    <div class="p-6 sm:p-8 lg:p-12">
        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            @if($post->category)
                <a href="{{ url('/blog/category/' . $post->category->slug) }}"
                   class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full hover:bg-emerald-200 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif
            <span class="text-sm text-stone-400">
                {{ \Carbon\Carbon::parse($post->published_at ?? $post->created_at)->format('d F Y') }}
            </span>
            @if($post->author)
                <span class="text-stone-300">·</span>
                <span class="text-sm text-stone-400">Oleh {{ $post->author->name }}</span>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="text-3xl sm:text-4xl font-bold text-stone-900 mb-8 leading-tight">
            {{ $post->title }}
        </h1>

        {{-- Content --}}
        <div class="prose prose-stone prose-lg max-w-none
                    prose-headings:text-stone-900 prose-headings:font-bold
                    prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
                    prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                    prose-p:text-stone-700 prose-p:leading-relaxed
                    prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-xl prose-img:shadow-md
                    prose-blockquote:border-emerald-500 prose-blockquote:bg-emerald-50/50 prose-blockquote:rounded-r-xl prose-blockquote:py-1 prose-blockquote:px-4
                    prose-code:bg-stone-100 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm
                    prose-pre:bg-stone-900 prose-pre:text-stone-100
                    prose-li:text-stone-700
                    prose-strong:text-stone-900">
            {!! $post->content !!}
        </div>

        {{-- Share Buttons --}}
        <div class="mt-10 pt-8 border-t border-stone-200">
            <h4 class="font-semibold text-stone-700 mb-3">Bagikan artikel ini:</h4>
            <div class="flex gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2 bg-[#1877F2] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2 bg-stone-900 text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                    Twitter
                </a>
                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                   target="_blank" rel="noopener"
                   class="px-4 py-2 bg-[#25D366] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</article>

{{-- Related Posts --}}
@if($relatedPosts->isNotEmpty())
    <div class="mt-10">
        <h3 class="text-2xl font-bold text-stone-900 mb-6">Artikel Terkait</h3>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedPosts as $rp)
                <article class="bg-white rounded-2xl border border-stone-200 overflow-hidden hover:shadow-md card-lift">
                    @if($rp->featured_image)
                        <img src="{{ asset('storage/' . $rp->featured_image) }}"
                             alt="{{ $rp->title }}"
                             class="w-full h-44 object-cover"
                             loading="lazy">
                    @endif
                    <div class="p-5">
                        @if($rp->category)
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full mb-2">
                                {{ $rp->category->name }}
                            </span>
                        @endif
                        <a href="{{ url('/blog/' . $rp->slug) }}" class="block group">
                            <h4 class="font-semibold text-stone-900 group-hover:text-emerald-600 transition-colors line-clamp-2">
                                {{ $rp->title }}
                            </h4>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif

{{-- Source Code CTA --}}
<div class="mt-10 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-8 text-center text-white">
    <h3 class="text-2xl font-bold mb-3">Ingin Punya Aplikasi Koperasi Sendiri?</h3>
    <p class="text-emerald-100 mb-6 max-w-xl mx-auto">
        Source code lengkap — tinggal install, rebrand, dan jalankan. Konvensional & syariah dalam 1 platform.
    </p>
    <div class="flex justify-center gap-3">
        <a href="{{ url('/docs') }}"
           class="px-6 py-3 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-emerald-50 transition-colors">
            Lihat Dokumentasi
        </a>
        <a href="https://wa.me/6281296052010?text=Halo%2C+saya+ingin+tanya+source+code+aplikasi+koperasi"
           target="_blank"
           class="px-6 py-3 bg-emerald-800 text-white font-semibold rounded-xl hover:bg-emerald-900 transition-colors">
            WhatsApp
        </a>
    </div>
</div>
@endsection
