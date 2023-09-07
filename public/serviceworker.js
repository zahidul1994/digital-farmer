var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/',
    '/offline',
    '/backend/assets/css/nucleo-icons.css',
    '/backend/assets/css/nucleo-svg.css',
    'backend/assets/js/frontawesomekit.js',
    '/backend/assets/css/argon-dashboard.css?v=2.0.5',
    '/backend/assets/select2/css/select2.min.css',
    '/backend/assets/toastr.js/latest/css/toastr.min.css',
    '/backend/assets/js/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
    '/backend/assets/js/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
    '/lightbox/css/lightbox.css',
    '/backend/assets/js/jquery-3.6.3.min.js',
    '/backend/assets/js/core/popper.min.js',
    '/backend/assets/js/core/bootstrap.min.js',
    '/backend/assets/js/plugins/perfect-scrollbar.min.js',
    '/backend/assets/js/plugins/smooth-scrollbar.min.js',
    '/backend/assets/js/plugins/dragula/dragula.min.js',
    '/backend/assets/js/plugins/jkanban/jkanban.js',
    '/backend/assets/js/argon-dashboard.min.js?v=2.0.5',
    '/backend/assets/js/livejs/buttons.js',
    '/backend/assets/js/ckeditor-jquery.js',
    '/backend/assets/css/toastr.min.css',
    '/backend/assets/js/plugins/datatables/jquery.dataTables.min.js',
    '/backend/assets/js/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    '/backend/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js',
    '/backend/assets/js/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
    '/lightbox/js/lightbox.js',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
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