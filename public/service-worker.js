/**
 * KoperasiApp Service Worker — basic offline shell + cache-first untuk asset, network-first untuk halaman
 * Version harus di-bump setiap deploy biar SW lama auto-evicted
 */
const CACHE_VERSION = 'koperasi-v1';
const PRECACHE_URLS = [
    '/portal',
    '/portal/login',
    '/manifest.webmanifest',
    '/offline.html',
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_VERSION).then((cache) => cache.addAll(PRECACHE_URLS).catch(() => null))
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_VERSION).map((k) => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== location.origin) return;
    if (url.pathname.startsWith('/admin')) return;
    if (url.pathname.startsWith('/livewire')) return;

    // HTML — network first (always fresh), fallback ke cache → offline page
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((res) => {
                    const clone = res.clone();
                    caches.open(CACHE_VERSION).then((c) => c.put(request, clone));
                    return res;
                })
                .catch(() =>
                    caches.match(request).then((cached) => cached || caches.match('/offline.html'))
                )
        );
        return;
    }

    // Static assets — cache first
    event.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;
            return fetch(request).then((res) => {
                if (!res || res.status !== 200) return res;
                const clone = res.clone();
                caches.open(CACHE_VERSION).then((c) => c.put(request, clone));
                return res;
            });
        })
    );
});
