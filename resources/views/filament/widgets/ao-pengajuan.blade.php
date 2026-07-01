<div class="fi-wi-ao-pengajuan">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 ring-1 ring-amber-200 dark:ring-amber-700">
            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Total Pengajuan</p>
            <p class="mt-1 text-2xl font-extrabold text-amber-700 dark:text-amber-300">{{ $totalPengajuan }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-4 ring-1 ring-blue-200 dark:ring-blue-700">
            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Nilai Total</p>
            <p class="mt-1 text-2xl font-extrabold text-blue-700 dark:text-blue-300">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl p-4 ring-1 ring-emerald-200 dark:ring-emerald-700">
            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Disetujui Bulan Ini</p>
            <p class="mt-1 text-2xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $disetujuiBulanIni }}</p>
        </div>
    </div>

    @if($pengajuan->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-xl ring-1 ring-gray-200 dark:ring-gray-700 shadow-sm overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pengajuan Menunggu Approval</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/30 text-left">
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Anggota</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Produk</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Plafon</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs">Tenor</th>
                        <th class="px-4 py-2 font-semibold text-gray-600 dark:text-gray-400 text-xs hidden sm:table-cell">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($pengajuan as $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-2.5 font-medium text-gray-900 dark:text-white">{{ $p->anggota?->nama ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $p->produk?->nama ?? '-' }}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-700 dark:text-gray-300">Rp {{ number_format($p->plafon, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">{{ $p->tenor }} bln</td>
                        <td class="px-4 py-2.5 text-gray-400 dark:text-gray-500 text-xs hidden sm:table-cell">{{ $p->created_at->translatedFormat('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl ring-1 ring-gray-200 dark:ring-gray-700 p-8 text-center">
        <p class="text-gray-400 dark:text-gray-500">Tidak ada pengajuan yang menunggu approval.</p>
    </div>
    @endif
</div>
