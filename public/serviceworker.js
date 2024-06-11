var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    // '/offline',
    // '/css/app.css',
    // '/js/app.js',
    '/pwa-img/rorex-72-72.png',
    '/pwa-img/rorex-96-96.png',
    // '/pwa-img/rorex-128x128.png',
    '/pwa-img/rorex-144-144.png',
    // '/pwa-img/rorex-152x152.png',
    '/pwa-img/rorex-192-192.png',
    // '/pwa-img/rorex-384x384.png',
    '/pwa-img/rorex-512-512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});