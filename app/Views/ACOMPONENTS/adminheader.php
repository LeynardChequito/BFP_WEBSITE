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

        .push-notif-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .push-notif-btn:hover {
            background-color: #0056b3;
        }

        .push-notif-btn:focus {
            outline: none;
        }

        hr {
            border: 0.5px solid #ddd;
            margin: 10px 0;
        }
        .fileProofContainer img,
            .fileProofContainer video {
                width: 150px;
                height: 100px;
                object-fit: cover;
            }
            
        .modal-body {
        max-height: 400px; /* Set the max height for the modal body */
        overflow-y: auto;  /* Enable vertical scrolling */
    }

    /* Adjust the size of images in the modal */
    .modal-body img {
        max-width: 100%; /* Make sure the image does not exceed the modal's width */
        height: auto;    /* Maintain the aspect ratio of the image */
        display: block;
        margin: 0 auto 10px; /* Center the image with some margin at the bottom */
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

    .modal-body {
        padding: 10px;
    }

    .modal-content {
        max-width: 600px; /* Max width of the modal content */
    }
    </style>
</head>

<body>

    <!-- Header section -->
    <div class="header desktop-header">
        <!-- Logo -->
        <img src="<?= base_url(); ?>/bfpcalapancity/public/images/Banner03_18Aug2018.png" alt="Logo" class="logo">
<!-- Notification dropdown -->
<div class="notification-dropdown position-relative" onclick="getRecentReports()">
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
        <i class="fas fa-bell notification-icon"></i>
        <span id="notification-counter" class="badge badge-danger badge-counter">0</span>
    </span>

    <!-- Dropdown content to display notifications -->
    <div class="dropdown-content">
        <h6 class="dropdown-header">Community Emergency Message</h6>
        <div id="notification-container"></div>
        <a class="dropdown-item text-center small text-gray-500" href="#">Show all notifications</a>
    </div>
</div>

<!-- Modal for new reports -->
<div class="modal" id="newReportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Community Reports</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <ul id="newReportsList" class="list-group">
                    <!-- New reports will be listed here -->
                </ul>
            </div>
        </div>
    </div>
</div>


        <!-- View Map button -->
        <a class="view-map-btn" href="<?= site_url('rescuemap') ?>">View Map</a>

        <!-- Philippine time -->
        <span id="philippineTime" class="philippine-time">Philippine Standard Time: <span id="current-time"></span></span>
    </div>

    <audio id="sirenSound" src="bfpcalapancity/public/alarm.mp3" preload="auto"></audio>
<?= view('EMERGENCYCALL/MapScript'); ?>
    
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
    const fcm = firebase.messaging();
    let mToken;

    // Fetch the current token
    fcm.getToken({
        vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
    }).then((currentToken) => {
        console.log('Token retrieved:', currentToken);
        mToken = currentToken;
    }).catch((error) => {
        console.error('Error retrieving token:', error);
    });

    // Handle incoming messages
    fcm.onMessage((payload) => {
        console.log('onMessage: ', payload);
        let notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        notifications.push({
            title: payload.notification.title,
            time: new Date().toLocaleTimeString()
        });
        localStorage.setItem('notifications', JSON.stringify(notifications));

        // Update the notification counter and dropdown
        updateNotificationCounter();
        updateNotificationDropdown();
    });

    // Function to update the notification counter
    function updateNotificationCounter() {
        const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        const counterElement = document.getElementById('notification-counter');
        counterElement.textContent = notifications.length;
    }

  // Function to update the notification dropdown content
function updateNotificationDropdown() {
    const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const notificationContainer = document.getElementById('notification-container');
    notificationContainer.innerHTML = '';

    notifications.forEach(notification => {
        notificationContainer.innerHTML += `
            <a class="dropdown-item d-flex align-items-center" href="#" onclick="openReportModal(${notification.reportId})">
                <div class="mr-3 notification-item">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div class="notification-details">
                    <div class="small text-gray-500 notification-time">${notification.time}</div>
                    <span class="font-weight-bold notification-title">${notification.title}</span>
                </div>
            </a>
            <hr />
        `;
    });
}


    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateNotificationCounter(); // Show notification count on load
        updateNotificationDropdown(); // Show notification dropdown content on load
    });

    // Modal controls
    const modal = document.getElementById("newReportModal");
    const span = document.getElementsByClassName("close")[0];

    $(".notification-dropdown").on("click", function() {
        modal.style.display = "block";
    });

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Time display
    function updateTime() {
        document.getElementById("current-time").textContent = new Date().toLocaleTimeString();
        setTimeout(updateTime, 1000);
    }

    // Initialize time update
    updateTime();
</script>
</body>

</html>
