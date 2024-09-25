<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Body Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
        }

        /* Headerbar Style */
        .headerbar {
            background-color: #EF3340;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Logo Styling */
        .headerbar .logo {
            max-width: 160px;
            height: auto;
            display: flex;
            align-items: center;
        }

        /* Notification Container */
        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: flex-start;
            background-color: white;
        }

        .notification-item img,
        .notification-item video {
            max-width: 80px;
            max-height: 80px;
            margin-right: 15px;
            border-radius: 5px;
            object-fit: cover;
        }

        .notification-item h4 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .notification-item p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #555;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        /* Badge Counter */
        .badge-counter {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            padding: 5px 8px;
            border-radius: 50%;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="headerbar">
        <img src="<?= base_url(); ?>/bfpcalapancity/public/images/Banner03_18Aug2018.png" alt="Logo" class="logo">

        <!-- Notification Dropdown -->
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell notification-icon"></i>
                <span id="notification-counter" class="badge-counter">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <div id="notificationContainer">
                    
                </div>
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

    <!-- Firebase and Push Notification -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyAiXnOQoNLOxLWEAw5h5JOTJ5Ad8Pcl6R8",
            authDomain: "pushnotifbfp.firebaseapp.com",
            projectId: "pushnotifbfp",
            storageBucket: "pushnotifbfp.appspot.com",
            messagingSenderId: "214092622073",
            appId: "1:214092622073:web:fbcbcb035161f7110c1a28",
            measurementId: "G-XMBH6JJ3M6"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                messaging.getToken({
                    vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log('Token retrieved:', currentToken);
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.error('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });

        messaging.onMessage((payload) => {
            console.log('Message received: ', payload);
            const notificationContainer = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.classList.add('notification-item');
            notification.innerHTML = `<h4>${payload.notification.title}</h4><p>${payload.notification.body}</p>`;
            notificationContainer.appendChild(notification);
        });

// Fetch the latest reports and display them in the notification list
function fetchLatestReports() {
    fetch("https://bfpcalapancity.online/getLatestReports")
        .then(response => response.json())
        .then(data => {
            const notificationContainer = document.getElementById('notificationContainer');
            const notificationCounter = document.getElementById('notification-counter');
            notificationContainer.innerHTML = ''; // Clear the container

            // Update the notification counter
            notificationCounter.innerText = data.length;

            if (data.length === 0) {
                notificationContainer.innerHTML = '<p class="text-center text-muted">No new notifications</p>';
            }

            // Loop through the reports and display each
            data.forEach(report => {
                const notification = document.createElement('div');
                notification.classList.add('notification-item');
                notification.setAttribute('data-communityreport-id', report.communityreport_id); // Store communityreport_id
                
                // Check if the fileproof is an image or a video
                let mediaContent = '';
                const fileExtension = report.fileproof.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    mediaContent = `<img src="${report.fileproof}" alt="File Proof">`;
                } else if (['mp4', 'mov', 'avi'].includes(fileExtension)) {
                    mediaContent = `<video src="${report.fileproof}" controls></video>`;
                }

                notification.innerHTML = `
                    ${mediaContent}
                    <div>
                        <h4>${report.fullName}</h4>
                        <p><strong></strong> (${report.timeAgo})</p>
                    </div>
                `;
                notificationContainer.appendChild(notification);

                // Add click event listener to open the modal and display report details
                notification.addEventListener('click', function() {
                    showReportDetails(report);
                });
            });
        })
        .catch(error => console.error('Error fetching reports:', error));
}

function showReportDetails(report) {
    if (report.communityreport_id) {
        // Redirect to the /rescuemap page with the communityreport_id as a URL parameter
        window.location.href = `/rescuemap?communityreport_id=${report.communityreport_id}`;
    } else {
        console.error("Error: communityreport_id is undefined");
    }
}


        // Call this function periodically to refresh the notifications
        setInterval(fetchLatestReports, 60000); // Refresh every 60 seconds
        fetchLatestReports(); // Initial call to load notifications on page load

        function updatePhilippineTime() {
            const now = new Date();
            const options = {
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const philippineTime = now.toLocaleTimeString('en-US', options);
            document.getElementById('current-time').innerText = philippineTime;
        }

        setInterval(updatePhilippineTime, 1000); // Update time every second
        updatePhilippineTime(); // Initial call to display time immediately
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (necessary for Bootstrap dropdowns in Bootstrap 4) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>