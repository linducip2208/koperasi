@if(!empty($faqs))
<section class="my-16">
    <h2 class="text-3xl md:text-4xl font-extrabold mb-2 text-slate-900">Pertanyaan yang Sering Ditanyakan</h2>
    <p class="text-slate-600 mb-8">Jawaban atas pertanyaan paling umum dari calon pengguna {{ config('pseo.brand.nama') }}.</p>
    <div class="space-y-3">
        @foreach($faqs as $i => $faq)
            <details class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-indigo-300 transition" @if($i === 0) open @endif>
                <summary class="flex items-center justify-between cursor-pointer p-5 list-none">
                    <span class="font-semibold text-slate-900 pr-4">{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 text-slate-400 transition group-open:rotate-180 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-5 text-slate-600 leading-relaxed">{!! nl2br(e($faq['a'])) !!}</div>
            </details>
        @endforeach
    </div>
</section>
@endif
