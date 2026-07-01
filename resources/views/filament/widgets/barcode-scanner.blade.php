<div class="p-4 bg-white rounded-2xl border border-stone-200" x-data="{ scanning: false, result: '', stream: null }">
    <h3 class="font-extrabold text-base text-stone-800 mb-3">📷 Barcode Scanner</h3>

    <div x-show="!scanning">
        <button @click="
            scanning = true;
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(s => { stream = s; $refs.video.srcObject = s; $refs.video.play(); });
            if ('BarcodeDetector' in window) { detectBarcode(); }
        " class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition">
            🔍 Scan Barcode
        </button>
        <input type="text" x-model="result" placeholder="Atau ketik manual..."
               class="mt-3 w-full px-4 py-2 rounded-xl border border-stone-200 focus:border-emerald-400 text-sm">
    </div>

    <div x-show="scanning" class="relative">
        <video x-ref="video" class="w-full rounded-xl" autoplay playsinline></video>
        <div id="barcode-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div style="width: 280px; height: 180px; border: 3px solid #10b981; border-radius: 12px; box-shadow: 0 0 0 9999px rgba(0,0,0,0.4);"></div>
        </div>
        <button @click="scanning = false; if(stream) stream.getTracks().forEach(t => t.stop()); stream = null;"
                class="mt-3 px-4 py-2 bg-stone-200 hover:bg-stone-300 rounded-xl font-semibold text-sm transition">
            Tutup Scanner
        </button>
        <div x-show="result" class="mt-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
            <span class="font-bold text-emerald-700">Terbaca:</span>
            <span x-text="result" class="font-mono text-sm"></span>
        </div>
    </div>

    <script>
        function detectBarcode() {
            const detector = new BarcodeDetector({ formats: ['ean_13', 'ean_8', 'code_128', 'qr_code'] });
            setInterval(async () => {
                if (!document.querySelector('[x-ref="video"]')) return;
                try {
                    const barcodes = await detector.detect(document.querySelector('[x-ref="video"]'));
                    if (barcodes.length > 0) {
                        const el = document.querySelector('[x-data]');
                        el.__x.$data.result = barcodes[0].rawValue;
                    }
                } catch(e) {}
            }, 500);
        }
    </script>
</div>
