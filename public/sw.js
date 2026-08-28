const CACHE_NAME = 'rentas-cache-v1';
const ASSETS_TO_CACHE = [
    '/favicon.png',
    '/site.webmanifest',
    '/login'
];

// Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('Service Worker: Caching files...');
            return cache.addAll(ASSETS_TO_CACHE);
        }).then(() => self.skipWaiting())
    );
});

// Activate Service Worker
self.addEventListener('activate', event => {
    console.log('Service Worker: Activado');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        console.log('Service Worker: Limpiando caché antigua');
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Fetch events
self.addEventListener('fetch', event => {
    // Avoid caching non-HTTP/HTTPS requests (like chrome-extension:// or livewire socket)
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Dynamic strategy: Network First, falling back to cache if offline
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // If it is a successful response, clone and store it in cache
                if (response.status === 200 && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Offline fallback
                return caches.match(event.request).then(cachedResponse => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // If offline and request is for login page or main page
                    if (event.request.mode === 'navigate') {
                        return caches.match('/login');
                    }
                });
            })
    );
});
