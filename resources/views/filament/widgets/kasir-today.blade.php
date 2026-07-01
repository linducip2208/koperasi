<div class="fi-wi-kasir-today">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
        @php
        $cards = [
            ['label' => 'Transaksi Hari Ini', 'value' => $transaksiHarian, 'color' => 'emerald'],
            ['label' => 'Setoran', 'value' => 'Rp ' . number_format($totalSetoran, 0, ',', '.'), 'color' => 'blue'],
            ['label' => 'Penarikan', 'value' => 'Rp ' . number_format($totalPenarikan, 0, ',', '.'), 'color' => 'amber'],
            ['label' => 'Pembayaran Pinjaman', 'value' => 'Rp ' . number_format($pembayaranHarian, 0, ',', '.'), 'color' => 'cyan'],
            ['label' => 'Penjualan POS', 'value' => 'Rp ' . number_format($penjualanHarian, 0, ',', '.'), 'color' => 'violet'],
            ['label' => 'Kas Masuk', 'value' => 'Rp ' . number_format($kasMasuk, 0, ',', '.'), 'color' => 'green'],
            ['label' => 'Kas Keluar', 'value' => 'Rp ' . number_format($kasKeluar, 0, ',', '.'), 'color' => 'rose'],
            ['label' => 'Selisih Kas', 'value' => 'Rp ' . number_format(($kasMasuk - $kasKeluar), 0, ',', '.'), 'color' => 'indigo'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 ring-1 ring-gray-200 dark:ring-gray-700 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $card['label'] }}</p>
            <p class="mt-1.5 text-lg font-extrabold text-gray-900 dark:text-white">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>
</div>
