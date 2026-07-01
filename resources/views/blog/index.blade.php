@extends('pseo._layout', [
    'title' => $seoTitle ?? 'Blog — KoperasiApp',
    'description' => $seoDescription ?? 'Artikel, panduan, dan berita seputar koperasi Indonesia — simpan pinjam, syariah, akuntansi, SHU, dan digitalisasi koperasi.',
    'canonical' => url('/blog'),
    'jsonLd' => $jsonLd ?? null,
    'breadcrumbs' => [['label' => 'Beranda', 'url' => url('/')], ['label' => 'Blog', 'url' => url('/blog')]],
    'heroH1' => 'Blog Koperasi',
    'heroSub' => 'Artikel, panduan, dan berita seputar koperasi Indonesia',
])

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- Main Content --}}
    <div class="flex-1">
        @if($posts->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-stone-200">
                <div class="text-5xl mb-4 opacity-30">📝</div>
                <h3 class="text-xl font-bold text-stone-700 mb-2">Belum ada artikel</h3>
                <p class="text-stone-500">Artikel blog akan muncul di sini setelah dipublikasikan.</p>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl border border-stone-200 overflow-hidden hover:shadow-lg card-lift flex flex-col sm:flex-row">
                        @if($post->featured_image)
                            <div class="sm:w-72 shrink-0">
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-48 sm:h-full object-cover"
                                     loading="lazy">
                            </div>
                        @endif
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full mb-2">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <a href="{{ url('/blog/' . $post->slug) }}" class="block group">
                                    <h2 class="text-xl font-bold text-stone-900 group-hover:text-emerald-600 transition-colors mb-2">
                                        {{ $post->title }}
                                    </h2>
                                </a>
                                <p class="text-stone-600 text-sm leading-relaxed line-clamp-2">
                                    {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 160) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 mt-4 text-xs text-stone-400">
                                <span>{{ \Carbon\Carbon::parse($post->published_at ?? $post->created_at)->format('d F Y') }}</span>
                                @if($post->author)
                                    <span class="text-stone-300">·</span>
                                    <span>{{ $post->author->name }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Sidebar --}}
    <aside class="lg:w-80 shrink-0">
        <div class="sticky top-24 space-y-6">
            {{-- Categories --}}
            <div class="bg-white rounded-2xl border border-stone-200 p-5">
                <h4 class="font-bold text-stone-800 mb-4">Kategori</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/blog') }}"
                           class="flex items-center justify-between text-sm py-1.5 px-2 rounded-lg hover:bg-emerald-50 text-stone-600 hover:text-emerald-700 transition-colors {{ !request('category') ? 'bg-emerald-50 text-emerald-700 font-semibold' : '' }}">
                            Semua
                            <span class="text-xs text-stone-400">{{ $totalPosts ?? $posts->total() }}</span>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ url('/blog/category/' . $cat->slug) }}"
                               class="flex items-center justify-between text-sm py-1.5 px-2 rounded-lg hover:bg-emerald-50 text-stone-600 hover:text-emerald-700 transition-colors {{ request('category') === $cat->slug ? 'bg-emerald-50 text-emerald-700 font-semibold' : '' }}">
                                {{ $cat->name }}
                                <span class="text-xs text-stone-400">{{ $cat->posts_count ?? $cat->posts()->where('is_published', true)->where('published_at', '<=', now())->count() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Recent Posts --}}
            @if($recentPosts->isNotEmpty())
                <div class="bg-white rounded-2xl border border-stone-200 p-5">
                    <h4 class="font-bold text-stone-800 mb-4">Artikel Terbaru</h4>
                    <ul class="space-y-3">
                        @foreach($recentPosts->take(5) as $rp)
                            <li>
                                <a href="{{ url('/blog/' . $rp->slug) }}"
                                   class="text-sm text-stone-700 hover:text-emerald-600 transition-colors line-clamp-2">
                                    {{ $rp->title }}
                                </a>
                                <div class="text-xs text-stone-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($rp->published_at ?? $rp->created_at)->format('d M Y') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Source Code CTA --}}
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white">
                <h4 class="font-bold mb-2">Butuh Software Koperasi?</h4>
                <p class="text-emerald-100 text-sm leading-relaxed mb-4">
                    Source code aplikasi koperasi lengkap — konvensional & syariah. Siap pakai, bisa direbrand.
                </p>
                <a href="https://wa.me/6281296052010?text=Halo%2C+saya+tertarik+dengan+source+code+aplikasi+koperasi"
                   target="_blank"
                   class="block text-center bg-white text-emerald-700 font-semibold py-2.5 px-4 rounded-xl text-sm hover:bg-emerald-50 transition-colors">
                    WhatsApp Sekarang
                </a>
            </div>
        </div>
    </aside>
</div>
@endsection
