const CACHE_NAME = 'rentas-pwa-v3';

// Only precache static, immutable assets (NEVER dynamic HTML pages that contain CSRF tokens)
const PRECACHE_ASSETS = [
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
                console.log('[PWA SW] Pre-caching core static assets...');
                return Promise.allSettled(
                    PRECACHE_ASSETS.map(url => cache.add(url).catch(err => {
                        console.warn(`[PWA SW] Failed to cache ${url}:`, err);
                    }))
                );
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Service Worker and clean old caches (including v1 and v2 with old HTML)
self.addEventListener('activate', event => {
    console.log('[PWA SW] Activated v3');
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

    // Do NOT cache livewire updates, file uploads, backups or imports
    if (
        url.pathname.startsWith('/livewire') || 
        url.pathname.includes('/database/backup') || 
        url.pathname.includes('/import/template')
    ) {
        return;
    }

    // Static assets (icons, manifest, fonts, images, build CSS/JS) -> Cache First, fallback to Network
    if (
        url.pathname.startsWith('/icons/') || 
        url.pathname.endsWith('.png') || 
        url.pathname.endsWith('.jpg') || 
        url.pathname.endsWith('.jpeg') || 
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

    // Navigation requests (HTML pages) -> ALWAYS Network. NEVER cache dynamic HTML pages to prevent CSRF 419 token mismatch!
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => {
                return new Response(
                    `<!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Sin Conexión - Control de Rentas</title>
                        <style>
                            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #0f172a; color: #f8fafc; text-align: center; padding: 20px; box-sizing: border-box; }
                            .box { background: #1e293b; padding: 32px 24px; border-radius: 24px; max-width: 380px; width: 100%; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
                            .icon { font-size: 40px; margin-bottom: 12px; }
                            h2 { margin: 0 0 8px; font-size: 20px; font-weight: 700; color: #fff; }
                            p { margin: 0 0 24px; font-size: 13px; color: #94a3b8; line-height: 1.5; }
                            button { background: #4f46e5; color: #fff; border: none; padding: 12px 24px; border-radius: 14px; font-weight: 600; font-size: 14px; cursor: pointer; width: 100%; transition: background 0.2s; }
                            button:hover { background: #4338ca; }
                        </style>
                    </head>
                    <body>
                        <div class="box">
                            <div class="icon">📡</div>
                            <h2>Sin conexión a internet</h2>
                            <p>No se pudo conectar con el servidor. Revisa tu conexión Wi-Fi o datos móviles y reintenta.</p>
                            <button onclick="window.location.reload()">Reintentar</button>
                        </div>
                    </body>
                    </html>`,
                    { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                );
            })
        );
        return;
    }

    // Default for other requests
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});
