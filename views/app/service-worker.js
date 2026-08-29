import { syncReceipts, logAction } from '/digital-receipts/src/db-core.js';

const CACHE_NAME = 'pwa-cache-v7';
const urlsToCache = [
  '<?= \yii\helpers\Url::to(['app/index']) ?>',
  '<?= \yii\helpers\Url::to(['app/js']) ?>',
  '/css/app.css',
  '/digital-receipts/styles/style.css',
  '/digital-receipts/src/app.js',
  '/digital-receipts/src/router.js',
  '/digital-receipts/src/components/login.js',
  '/digital-receipts/src/components/types.js',
  '/digital-receipts/src/components/issue.js',
  '/digital-receipts/src/components/list.js',
  '/digital-receipts/src/components/qrcode.js',
  '/digital-receipts/src/components/logs.js',
  '/digital-receipts/src/store.js',
  '/digital-receipts/src/db-core.js',
  '/digital-receipts/src/components/base.js',
  '/images/app-icon-192.png',
  '/images/app-icon-512.png',
  '/images/app-badge-upload-failed-72.png',
  '/images/app-badge-upload-ok-72.png',
  '/js/qrcode.min.js',
];

<?php // jq -r '.log.entries[] | .request.url'  < log.har ?>

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});

self.addEventListener('sync', event => {
    logAction('SW: sync request received');
    if (event.tag === 'sync-receipts') {
        logAction('SW: syncing receipts...');
        event.waitUntil(
            syncReceipts()
            .then(result => {
                logAction('SW: receipts synced', result);
                let bodyTextAdded = "<?=Yii::t('app', 'The receipts ({number}) you issued while offline have been sent to the server. Open the app to see the list.')?>";
                let bodyTextFailed = "<?=Yii::t('app', 'Some receipts ({number}) you issued while offline could not be sent to the server when back online. Try from the app.')?>";
                if (result.added > 0) {
                    self.registration.showNotification("<?= Yii::t('app', 'Receipts uploaded') ?>", {
                          body: bodyTextAdded.replace(/{number}/, result.added),
                          icon: "/images/app-icon-192.png",
                          badge: "/images/app-badge-upload-ok-72.png",
                          tag: "document-sync-complete",
                          data: {
                            url: "app"
                          }
                        });
                    };
                if (result.failed > 0) {
                    self.registration.showNotification("<?= Yii::t('app', 'Receipts not uploaded') ?>", {
                          body: bodyTextFailed.replace(/{number}/, result.failed),
                          icon: "/images/app-icon-192.png",
                          badge: "/images/app-badge-upload-failed-72.png",
                          tag: "document-sync-complete",
                          data: {
                            url: "app"
                          }
                        });
                    };
                    
            })
                .catch(error => {
                    logAction('SW: error in syncing receipts', error);
                }
            )
        )
    }
});

self.addEventListener('notificationclick', event => {
    logAction('SW: handling notification click');
    // Close the notification pop-up
    // event.notification.close();

    // Get the relative URL stored in event.notification.data
    const relativeUrl = event.notification.data?.url || '/';

    // Resolve to an absolute URL
    const targetUrl = new URL(relativeUrl, self.location.origin).href;

    event.waitUntil(
        // Check all existing window/tab clients controlled by this service worker
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(windowClients => {
            // If the app is already open in a tab, focus it and navigate to the target URL
            for (let client of windowClients) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            // If the tab/app isn't open, open a new window to the target URL
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

console.log("service worker successfully loaded");
