<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP WEBSITE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #EF3340;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            max-width: 430px;
            height: auto;
        }

        .navigation-bar {
            display: flex;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #fff;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #FFD100;
        }

        .philippine-time {
            color: #fff;
            font-size: 14px;
            margin-left: auto;
            margin-right: 20px;
        }

        .notification-dropdown {
            position: relative;
            display: inline-block;
            color: #fff;
            margin-right: 20px;
        }

        .notification-dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
            padding: 10px;
            border-radius: 8px;
        }

        .dropdown-content a {
            color: #333;
            display: block;
            padding: 10px 0;
            transition: color 0.3s, background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f5f5f5;
            color: #EF3340;
        }

        .notification-icon {
            font-size: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .notification-item {
            display: flex;
            align-items: center;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .notification-details {
            margin-left: 10px;
        }

        .notification-time {
            color: #EF3340;
        }

        .notification-title {
            color: #333;
        }

        .dropdown-item {
            background-color: #E5F2FF;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .notification {
            margin-bottom: 10px;
        }

        .view-map-btn {
            background-color: blue;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            margin-right: 10px;
            margin-left: 580px;
        }

        .view-map-btn:hover {
            background-color: lightblue;
            color: black;
        }
    </style>
</head>

<body>

    <!-- Header section -->
    <div class="header desktop-header">
        <img src="<?= base_url(); ?>/bfpcalapancity/public/images/Banner03_18Aug2018.png" alt="Logo" class="logo">

        <!-- Notification dropdown -->
        <div class="notification-dropdown position-relative">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                <i class="fas fa-bell notification-icon"></i>
                <span id="notification-counter" class="badge badge-danger badge-counter">0</span>
            </span>
            <div class="dropdown-content">
                <h6 class="dropdown-header">Community Emergency Message</h6>
                <div class="dropdown-separator"></div>
                <div id="notification-container"></div>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show all notifications</a>
            </div>
        </div>

        <!-- View Map button -->
        <a class="view-map-btn" href="<?= site_url('rescuemap') ?>">View Map</a>

        <!-- Philippine time -->
        <span id="philippineTime" class="philippine-time">Philippine Standard Time: <span id="current-time"></span></span>
    </div>

    <!-- Modal for notifications -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Notifications</h2>
            <div id="notificationContent"></div>
        </div>
    </div>

    <!-- External scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <!-- JavaScript code for handling notifications and updating time -->
    <script type="module">
        // Get the modal element
        const modal = document.getElementById('notificationModal');

        // Function to get current time
        function getCurrentTime() {
            return new Date().toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
        }

        // Function to update time every second
        function updateTime() {
            document.getElementById("current-time").textContent = getCurrentTime();
            setTimeout(updateTime, 1000);
        }

        // Initialize Firebase
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
        let mToken;

        // Retrieve FCM token
        messaging.getToken({
            vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
        }).then((currentToken) => {
            if (currentToken) {
                console.log('Token retrieved:', currentToken);
                mToken = currentToken;
            } else {
                console.log('No registration token available.');
            }
        }).catch((error) => {
            console.error('Error retrieving token:', error);
        });

// Handle incoming messages (in the foreground)
messaging.onMessage((payload) => {
    console.log('Message received: ', payload);
    addNotificationToDropdown(payload.notification.title, payload.notification.body);
    triggerNotification(payload.notification.title, payload.notification.body); // Trigger a browser notification
});


function addNotificationToDropdown(title, body) {
    console.log('Adding notification to dropdown:', title, body); // Debugging statement
    let count = localStorage.getItem("notification-count") || 0;
    count = parseInt(count) + 1;
    localStorage.setItem('notification-count', count);

    document.getElementById('notification-counter').textContent = count;

    const notificationContainer = document.getElementById('notification-container');
    const notificationHTML = `
        <div class="dropdown-separator"></div>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3 notification-item">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div class="notification-details">
                <div class="small text-gray-500 notification-time">${new Date().toLocaleTimeString()}</div>
                <span class="font-weight-bold notification-title">${title}</span>
                <p>${body}</p>
            </div>
        </a>
    `;
    notificationContainer.insertAdjacentHTML('beforeend', notificationHTML);
}

function triggerNotification(title, body) {
    if (Notification.permission === "granted") {
        new Notification(title, { body: body });
    } else {
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                new Notification(title, { body: body });
            }
        });
    }
}

        if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/public/firebase-messaging-sw.js')
            .then(function(registration) {
                console.log('Service Worker registered with scope:', registration.scope);
            }).catch(function(err) {
                console.log('Service Worker registration failed:', err);
            });
    }

    messaging.requestPermission()
    .then(() => {
        console.log('Notification permission granted.');
        return messaging.getToken();
    })
    .then((currentToken) => {
        if (currentToken) {
            console.log('Token retrieved:', currentToken);
            mToken = currentToken;
        } else {
            console.log('No registration token available.');
        }
    })
    .catch((error) => {
        console.error('Error retrieving token:', error);
    });

        // Open modal on notification click
        $(".notification-dropdown").on("click", function() {
            modal.style.display = "block";
        });

        // Close modal when clicked outside the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        // Start updating time
        updateTime();
    </script>
</body>

</html>
