<div class="p-4 bg-white rounded-2xl border border-stone-200">
    <h3 class="font-extrabold text-base text-stone-800 mb-4">📅 Agenda & Reminder</h3>

    @php $todayEvents = $this->getTodayEvents(); @endphp
    @if(!empty($todayEvents))
        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
            @foreach($todayEvents as $e)<p class="text-sm font-semibold text-amber-800">{{ $e }}</p>@endforeach
        </div>
    @endif

    <div class="space-y-2">
        @foreach($this->getEvents() as $e)
            <div class="flex items-center gap-3 p-2 rounded-xl {{ $e['kategori'] === 'birthday' ? 'bg-pink-50' : ($e['kategori'] === 'rat' ? 'bg-blue-50' : 'bg-rose-50') }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm {{ $e['kategori'] === 'birthday' ? 'bg-pink-200 text-pink-800' : ($e['kategori'] === 'rat' ? 'bg-blue-200 text-blue-800' : 'bg-rose-200 text-rose-800') }}">
                    {{ $e['hari'] ?? '!' }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-stone-800">{{ $e['judul'] }}</div>
                    <div class="text-xs text-stone-500">{{ $e['tanggal'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
