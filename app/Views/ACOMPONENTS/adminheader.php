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

    <!-- Audio element for the alarm sound -->
    <audio id="sirenSound" src="bfpcalapancity/public/45secs_alarm.mp3" preload="auto"></audio>

    <!-- External scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <!-- JavaScript code for handling notifications and updating time -->
<script type="module">
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

        // Request permission for notifications
        messaging.requestPermission().then(function () {
            console.log('Notification permission granted.');
            return messaging.getToken({ vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfo' }); // Replace 'YOUR_VAPID_KEY' with the VAPID key
        }).then(function (token) {
            console.log('FCM Token:', token);
            
            // Send token to the server
            fetch('/save-token', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token: token })
            });
        }).catch(function (err) {
            console.log('Unable to get permission to notify.', err);
        });

        // Handle incoming messages when the page is open
        messaging.onMessage(function(payload) {
            console.log('Message received. ', payload);
            const notificationOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon || '/firebase-logo.png',
                image: payload.notification.image || '/firebase-logo.png',
            };
            displayNotification(payload.notification.title, payload.notification.body, payload.data.timestamp);
        })
// Helper function to calculate 'time ago'
function timeAgo(timeStamp) {
            const now = new Date();
            const secondsPast = (now.getTime() - new Date(timeStamp).getTime()) / 1000;

            if (secondsPast < 60) {
                return Math.floor(secondsPast) + ' seconds ago';
            } else if (secondsPast < 3600) {
                return Math.floor(secondsPast / 60) + ' minutes ago';
            } else if (secondsPast < 86400) {
                return Math.floor(secondsPast / 3600) + ' hours ago';
            } else {
                return Math.floor(secondsPast / 86400) + ' days ago';
            }
        }
        let currentReportCount = 0;

        async function fetchLatestReports() {
            try {
                const response = await fetch('/community-report/latest-reports');
                const reports = await response.json();

                if (reports.length > currentReportCount) {
                    playAlarm(); // Play alarm if there are new reports
                }

                currentReportCount = reports.length;
                displayNotifications(reports);
            } catch (error) {
                console.error('Error fetching latest reports:', error);
            }
        }

        function playAlarm() {
        const alarmSound = document.getElementById('alarmSound');
        if (alarmSound) {
            alarmSound.play().catch(error => {
                console.error('Error playing sound:', error);
            });
        } else {
            console.error('Audio element not found.');
        }
    }
        function getRelativeTime(timestamp) {
            const now = new Date();
            const timeDifference = Math.floor((now - new Date(timestamp)) / 1000);

            if (timeDifference < 60) return `${timeDifference} seconds ago`;
            if (timeDifference < 3600) return `${Math.floor(timeDifference / 60)} minutes ago`;
            if (timeDifference < 86400) return `${Math.floor(timeDifference / 3600)} hours ago`;
            return `${Math.floor(timeDifference / 86400)} days ago`;
        }

        // Display notification
        function displayNotification(fullName, fileproof, timestamp) {
            const notificationTime = timeAgo(timestamp);
            const notificationHTML = `
                <div class="notification">
                    <strong>${fullName}</strong> submitted a report. 
                    <br>Proof: <a href="/community_report/${fileproof}" target="_blank">${fileproof}</a>
                    <br><em>${notificationTime}</em>
                </div>
            `;
            document.getElementById('notification-container').insertAdjacentHTML('beforeend', notificationHTML);
        }

        document.addEventListener('DOMContentLoaded', function () {
            fetchLatestReports();
            setInterval(fetchLatestReports, 60000); // Check every minute
        });

        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                console.log("Notification permission granted.");
            } else {
                console.log("Notification permission denied.");
            }
        });
    </script>
</body>

</html>