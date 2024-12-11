// public\service-worker.js
self.addEventListener('push', function (event) {
    const data = event.data.json();

    const options = {
        body: data.body,
        icon: '/public/design/logo.png', // Adjust path to correct location
        vibrate: [200, 100, 200], // Optional: Add vibration pattern
        actions: [
            { action: 'view', title: 'View' },
            { action: 'close', title: 'Close' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    if (event.action === 'view') {
        clients.openWindow('https://bfpcalapancity.online/admin-home'); // Replace with your admin dashboard path
    }
});
