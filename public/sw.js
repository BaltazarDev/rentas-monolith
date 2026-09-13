const CACHE_NAME = 'rentas-pwa-v2';

const PRECACHE_ASSETS = [
    '/',
    '/login',
    '/site.webmanifest',
    '/favicon.png',
    '/apple-touch-icon.png',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/icon-maskable-192x192.png'
];

// Install Service Worker and precache essential shell assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[PWA SW] Pre-caching core assets...');
                // Use Promise.allSettled so that if a single route fails, the rest still cache
                return Promise.allSettled(
                    PRECACHE_ASSETS.map(url => cache.add(url).catch(err => {
                        console.warn(`[PWA SW] Failed to cache ${url}:`, err);
                    }))
                );
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Service Worker and clean old caches
self.addEventListener('activate', event => {
    console.log('[PWA SW] Activated');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log('[PWA SW] Removing old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Strategy
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);

    // Only process requests from the same origin
    if (url.origin !== self.location.origin) {
        return;
    }

    // NEVER cache non-GET requests (POST, PUT, DELETE, etc.)
    if (request.method !== 'GET') {
        return;
    }

    // Do NOT cache livewire updates, file uploads or database downloads
    if (url.pathname.startsWith('/livewire') || url.pathname.includes('/database/backup') || url.pathname.includes('/import/template')) {
        return;
    }

    // Static assets (icons, manifest, fonts, images) -> Cache First, fallback to Network
    if (
        url.pathname.startsWith('/icons/') || 
        url.pathname.endsWith('.png') || 
        url.pathname.endsWith('.jpg') || 
        url.pathname.endsWith('.svg') || 
        url.pathname.endsWith('.ico') || 
        url.pathname.endsWith('.webmanifest') ||
        url.pathname.startsWith('/build/')
    ) {
        event.respondWith(
            caches.match(request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then(networkResponse => {
                    if (networkResponse && networkResponse.status === 200) {
                        const clone = networkResponse.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // Navigation requests (HTML pages) -> Network First, fallback to Cache or /login
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(networkResponse => {
                    if (networkResponse && networkResponse.status === 200) {
                        const clone = networkResponse.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Fallback to cached login or dashboard if offline
                    return (await caches.match('/')) || (await caches.match('/login'));
                })
        );
        return;
    }

    // Default for other GET requests
    event.respondWith(
        fetch(request)
            .then(response => {
                if (response && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                }
                return response;
            })
            .catch(() => caches.match(request))
    );
});
