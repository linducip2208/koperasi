<div class="pos-kasir" x-data="{ searchOpen: false }" class="flex gap-4 h-full">

    {{-- Left: Product Grid --}}
    <div class="flex-1 flex flex-col gap-4">
        {{-- Search --}}
        <div class="relative" @click.away="searchOpen = false">
            <input type="text" wire:model.live.debounce.250ms="searchQuery"
                   @focus="searchOpen = true"
                   placeholder="🔍 Cari barang (nama / barcode)..."
                   class="w-full text-lg px-5 py-4 rounded-2xl border-2 border-stone-200 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition">

            {{-- Search Results Dropdown --}}
            <div x-show="searchOpen && $wire.searchResults.length > 0"
                 class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl border border-stone-200 shadow-2xl z-50 max-h-80 overflow-y-auto">
                @foreach($searchResults as $item)
                    <button wire:click="addToCart({{ $item['id'] }})"
                            @click="searchOpen = false"
                            class="w-full text-left px-5 py-3 hover:bg-emerald-50 flex justify-between items-center border-b border-stone-100 last:border-0">
                        <div>
                            <div class="font-semibold text-stone-800">{{ $item['nama'] }}</div>
                            <div class="text-xs text-stone-400">Stok: {{ $item['stok'] }}</div>
                        </div>
                        <div class="text-emerald-700 font-bold text-lg">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Quick Product Grid --}}
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 flex-1 content-start">
            @foreach(\App\Models\TokoBarang::where('aktif', true)->orderBy('nama')->limit(30)->get() as $b)
                <button wire:click="addToCart({{ $b->id }})"
                        class="pos-product-btn bg-white rounded-2xl border-2 border-stone-100 hover:border-emerald-300 p-4 text-center transition-all hover:shadow-lg hover:-translate-y-0.5">
                    <div class="text-3xl mb-2">📦</div>
                    <div class="font-semibold text-sm text-stone-800 leading-tight line-clamp-2">{{ $b->nama }}</div>
                    <div class="text-emerald-700 font-bold text-sm mt-1">Rp {{ number_format($b->harga_jual_umum ?? 0, 0, ',', '.') }}</div>
                    @if(($b->stok ?? 0) < 10)
                        <div class="text-rose-500 text-xs mt-1">Stok: {{ $b->stok ?? 0 }}</div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Right: Cart Panel --}}
    <div class="w-96 shrink-0 bg-white rounded-2xl border-2 border-stone-200 flex flex-col max-h-[calc(100vh-8rem)]">
        <div class="p-5 border-b border-stone-200">
            <h2 class="text-lg font-extrabold text-stone-800">🧾 Keranjang</h2>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $key => $item)
                <div class="bg-stone-50 rounded-xl p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 pr-2">
                            <div class="font-semibold text-sm text-stone-800">{{ $item['nama'] }}</div>
                            <div class="text-xs text-stone-500">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                        </div>
                        <button wire:click="removeItem('{{ $key }}')" class="text-rose-400 hover:text-rose-600 text-lg leading-none">&times;</button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] - 1 }})"
                                class="w-8 h-8 rounded-lg bg-stone-200 hover:bg-stone-300 font-bold text-stone-600 transition">−</button>
                        <span class="w-10 text-center font-bold text-stone-800">{{ $item['qty'] }}</span>
                        <button wire:click="updateQty('{{ $key }}', {{ $item['qty'] + 1 }})"
                                class="w-8 h-8 rounded-lg bg-emerald-100 hover:bg-emerald-200 font-bold text-emerald-700 transition">+</button>
                        <span class="ml-auto font-bold text-emerald-700">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-stone-400">
                    <div class="text-5xl mb-3 opacity-30">🛒</div>
                    <p class="text-sm">Keranjang kosong</p>
                    <p class="text-xs mt-1">Cari & klik barang untuk memulai</p>
                </div>
            @endforelse
        </div>

        {{-- Payment Panel --}}
        <div class="border-t border-stone-200 p-5 space-y-3">
            <div class="flex justify-between items-center text-lg">
                <span class="text-stone-500">Total</span>
                <span class="text-2xl font-extrabold text-stone-900">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex gap-2">
                <button wire:click="$set('data.metode_bayar', 'cash')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold transition {{ ($data['metode_bayar'] ?? 'cash') === 'cash' ? 'bg-emerald-600 text-white' : 'bg-stone-100 text-stone-600' }}">Tunai</button>
                <button wire:click="$set('data.metode_bayar', 'transfer')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold transition {{ ($data['metode_bayar'] ?? '') === 'transfer' ? 'bg-emerald-600 text-white' : 'bg-stone-100 text-stone-600' }}">Transfer</button>
                <button wire:click="$set('data.metode_bayar', 'qris')"
                        class="flex-1 py-2.5 rounded-xl text-sm font-bold transition {{ ($data['metode_bayar'] ?? '') === 'qris' ? 'bg-emerald-600 text-white' : 'bg-stone-100 text-stone-600' }}">QRIS</button>
            </div>

            @if(($data['metode_bayar'] ?? 'cash') === 'cash')
                <div>
                    <label class="text-xs text-stone-500 mb-1 block">Uang Diterima</label>
                    <input type="number" wire:model.live="data.bayar" wire:keydown.enter="processPayment"
                           class="w-full text-xl font-bold px-4 py-3 rounded-xl border-2 border-stone-200 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition"
                           placeholder="0">
                    @if(($data['bayar'] ?? 0) > 0)
                        <div class="mt-2 text-sm text-stone-500">
                            Kembalian: <span class="font-bold text-emerald-700">Rp {{ number_format(max(0, ($data['bayar'] ?? 0) - $cartTotal), 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <button wire:click="processPayment" @if($cartTotal <= 0) disabled @endif
                    class="w-full py-4 rounded-xl text-white font-extrabold text-lg transition-all transform active:scale-95
                           @if($cartTotal > 0) bg-gradient-to-r from-emerald-600 to-teal-600 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:-translate-y-0.5
                           @else bg-stone-300 cursor-not-allowed @endif">
                💰 BAYAR SEKARANG
            </button>

            <button wire:click="clearCart"
                    class="w-full py-2.5 rounded-xl text-stone-500 font-semibold text-sm hover:bg-stone-100 transition">
                🗑 Kosongkan Keranjang
            </button>
        </div>
    </div>
</div>

<style>
    .pos-kasir { min-height: calc(100vh - 10rem); }
    .pos-product-btn { cursor: pointer; min-height: 120px; }
    @media (max-width: 1024px) {
        .pos-kasir { flex-direction: column; }
        .pos-kasir > div:last-child { width: 100% !important; max-height: 60vh; }
    }
</style>
