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
            margin-left: auto;
            color: #fff;
            font-size: 14px;
        }

        .notification-dropdown {
            position: relative;
            display: inline-block;
            color: #fff;
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
    </style>
</head>

<body>

    <!-- Header section -->
    <div class="header desktop-header">
        <!-- Logo -->
        <img src="<?= base_url(); ?>images/Banner03_18Aug2018.png" alt="Logo" class="logo">

        <!-- Notification dropdown -->
        <div class="notification-dropdown position-relative">
            <!-- Notification icon and counter -->
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                <i class="fas fa-bell notification-icon"></i>
                <span id="notification-counter" class="badge badge-danger badge-counter">0</span>
            </span>
            <!-- Dropdown content to display notifications -->
            <div class="dropdown-content">
                <h6 class="dropdown-header">Community Emergency Message</h6>
                <div class="dropdown-separator"></div>
                <div id="notification-container"></div>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show all notifications</a>
            </div>
        </div>

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
        const firebaseConfig = {
        apiKey: "AIzaSyAiXnOQoNLOxLWEAw5h5JOTJ5Ad8Pcl6R8",
        authDomain: "pushnotifbfp.firebaseapp.com",
        projectId: "pushnotifbfp",
        storageBucket: "pushnotifbfp.appspot.com",
        messagingSenderId: "214092622073",
        appId: "1:214092622073:web:fbcbcb035161f7110c1a28",
        measurementId: "G-XMBH6JJ3M6"
        };

        // Function to get current time
        function getCurrentTime() {
            return new Date().toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true });
        }

        // Function to update time every second
        function updateTime() {
            document.getElementById("current-time").textContent = getCurrentTime();
            setTimeout(updateTime, 1000); // Update time every second
        }

        // Initialize Firebase and update time
        firebase.initializeApp(firebaseConfig);
        const fcm = firebase.messaging();
        let mToken;

        // Get Firebase token
        fcm.getToken({
            vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
        }).then((currentToken) => {
            console.log('Token retrieved:', currentToken);
            mToken = currentToken;
        }).catch((error) => {
            console.error('Error retrieving token:', error);
        });

        // Handle incoming messages
        fcm.onMessage((data) => {
            console.log('onMessage: ', data);
            let count = localStorage.getItem("notification-count");
            if (count) {
                localStorage.setItem('notification-count', parseInt(count) + 1);
            } else {
                localStorage.setItem('notification-count', 1);
            }

            $('#notification-counter').text(localStorage.getItem("notification-count"));
            $('#notification-container').append(
                `<div class="dropdown-separator"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3 notification-item">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i> 
                        </div>
                    </div>
                    <div class="notification-details">
                        <div class="small text-gray-500 notification-time">${getCurrentTime()}</div>
                        <span class="font-weight-bold notification-title">${data.notification.title}</span>
                    </div>
                </a>`
            );

            $('#notificationContent').append(
                `<div class="notification">
                    <div class="notification-time">${getCurrentTime()}</div>
                    <div class="notification-title">${data.notification.title}</div>
                    <div class="notification-body">${data.notification.body}</div>
                </div>`
            );
        });

        // Open modal on notification click
        $(".notification-dropdown").on("click", function() {
            modal.style.display = "block";
        });

        // Close modal when close button clicked
        const span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        };

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
