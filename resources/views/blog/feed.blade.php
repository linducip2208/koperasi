{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{{ config('app.name') }} Blog</title>
    <link>{{ url('/blog') }}</link>
    <description>Artikel, panduan, dan berita seputar koperasi Indonesia</description>
    <language>id</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    <atom:link href="{{ url('/blog/feed.xml') }}" rel="self" type="application/rss+xml"/>
    @foreach($posts as $post)
    <item>
        <title>{{ $post->title }}</title>
        <link>{{ url('/blog/' . $post->slug) }}</link>
        <guid>{{ url('/blog/' . $post->slug) }}</guid>
        <pubDate>{{ ($post->published_at ?? $post->created_at)->toRssString() }}</pubDate>
        <description><![CDATA[{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 200) }}]]></description>
        @if($post->category)<category>{{ $post->category->name }}</category>@endif
        <author>{{ $post->author?->email ?? 'admin@koperasi.test' }} ({{ $post->author?->name ?? config('app.name') }})</author>
    </item>
    @endforeach
</channel>
</rss>
