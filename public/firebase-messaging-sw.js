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
self.addEventListener('message', (event) => {
  // Ensure the event handler properly responds to the message
  event.waitUntil(
      // Asynchronous task, like fetching data or handling a notification
      fetch(event.data.url).then(response => {
          // Do something with the response
          return response.json();
      }).then(data => {
          // Use the data or send a response
          console.log('Data fetched:', data);
      }).catch(error => {
          console.error('Error:', error);
      })
  );
});
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  // Perform async task
  new Promise((resolve, reject) => {
      // Some asynchronous operation like a fetch
      fetch(request.url).then(response => resolve(response)).catch(err => reject(err));
  }).then(response => {
      sendResponse(response);
  }).catch(error => {
      sendResponse({ error: error.message });
  });

  // Return true to indicate asynchronous response
  return true;
});
