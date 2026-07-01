<div class="fi-wi-kolektor-tagihan">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
        <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-xl p-4 ring-1 ring-red-200 dark:ring-red-700">
            <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">Tagihan Hari Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-red-700 dark:text-red-300">{{ $jumlahTagihan }} anggota</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 ring-1 ring-amber-200 dark:ring-amber-700">
            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Total Ditagih</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700 dark:text-amber-300">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl p-4 ring-1 ring-rose-200 dark:ring-rose-700">
            <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Tunggakan</p>
            <p class="mt-1 text-2xl font-extrabold text-rose-700 dark:text-rose-300">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20 rounded-xl p-4 ring-1 ring-blue-200 dark:ring-blue-700">
            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Minggu Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-blue-700 dark:text-blue-300">{{ $mingguIni }} jatuh tempo</p>
        </div>
    </div>

    @if($tagihanHariIni->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl ring-1 ring-gray-200 dark:ring-gray-700 shadow-sm overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Jadwal Penagihan Hari Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/30 text-left">
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Anggota</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Pinjaman</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Angsuran Ke</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Total</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($tagihanHariIni as $j)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-2.5 font-medium text-gray-900 dark:text-white">{{ $j->pinjaman?->anggota?->nama ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $j->pinjaman?->produk?->nama ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-gray-600 dark:text-gray-300">{{ $j->angsuran_ke }}</td>
                        <td class="px-4 py-2.5 font-mono font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($j->total_angsuran, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $j->status === 'tertunggak' ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' }}">
                                {{ ucwords(str_replace('_', ' ', $j->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl ring-1 ring-gray-200 dark:ring-gray-700 p-8 text-center">
        <p class="text-gray-400 dark:text-gray-500">Tidak ada tagihan untuk hari ini.</p>
    </div>
    @endif
</div>
