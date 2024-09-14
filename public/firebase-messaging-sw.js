importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

// Initialize Firebase in the service worker with the same configuration
const firebaseConfig = {
  apiKey: "AIzaSyAiXnOQoNLOxLWEAw5h5JOTJ5Ad8Pcl6R8",
  authDomain: "pushnotifbfp.firebaseapp.com",
  projectId: "pushnotifbfp",
  storageBucket: "pushnotifbfp.appspot.com",
  messagingSenderId: "214092622073",
  appId: "1:214092622073:web:fbcbcb035161f7110c1a28",
  measurementId: "G-XMBH6JJ3M6"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Background message handler
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('Received background message ', payload);

  // Customize notification content based on the received payload
  const notificationTitle = 'EMERGENCY ALERT!!!';
  const notificationOptions = {
      body: payload.data.body || 'No additional information provided',
      icon: payload.data.icon || '/firebase-logo.png',
      image: payload.data.image || 'image.jpg',
      data: {
          click_action: payload.data.click_action || '/'
      }
  };

  // Display the notification
  return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Message handler to receive notification details from the site.php form
self.addEventListener('message', function(event) {
  const notificationPayload = event.data;
  console.log('Received notification details:', notificationPayload);

  // You can process the notification details here as needed
});