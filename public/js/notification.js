// notification.js
function subscribeUser() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.ready.then(function (registration) {
            const vapidPublicKey = 'BOL7Dq2AA2B8FSLSob9xORIr8_tEsj0C3OWUl44EXjUj_1hSZuuK1y7mByCmpB4nlo51QYddLt7snR03Gs53x_0';
            const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

            registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedVapidKey,
            }).then(function (subscription) {
                console.log('User subscribed:', subscription);

                // Send subscription details to the server
                fetch('/save-subscription', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(subscription), // Send JSON payload
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Subscription saved successfully:', data);
                })
                .catch(error => {
                    console.error('Failed to save subscription:', error);
                });
            }).catch(function (error) {
                console.error('Subscription failed:', error);
            });
        });
    } else {
        console.error('Push messaging is not supported in this browser.');
    }
}

// Utility function to convert VAPID key
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
