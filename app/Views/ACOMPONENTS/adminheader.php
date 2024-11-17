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
            padding: 15px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Logo Styling */
        .headerbar .logo {
            max-width: 80px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding: 0 10px;
        }

        .logo-text {
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            margin-left: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Notification Dropdown */
        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
            width: 300px; /* Adjust width as needed */
            right: 0; /* Align to the right of the bell icon */
            left: auto; /* Prevent dropdown from stretching across the viewport */
        }

        .dropdown-toggle::after {
            display: none; /* Remove default dropdown arrow */
        }

        .notification-item {
            padding: 10px; /* Reduce padding for a more compact look */
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            background-color: white;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f8f8; /* Slight background change on hover */
        }

        .notification-item img,
        .notification-item video {
            width: 50px; /* Reduce image and video size */
            height: 50px;
            margin-right: 10px;
            border-radius: 5px;
            object-fit: cover;
        }

        .notification-item h4 {
            margin: 0;
            font-size: 14px; /* Slightly smaller font size */
            color: #333;
        }

        .notification-item p {
            margin: 3px 0 0 0; /* Reduce spacing */
            font-size: 12px;
            color: #555;
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
        <div class="logo-container">
            <img src="<?= base_url(); ?>/bfpcalapancity/public/design/logo.png" alt="BFP Logo" class="logo">
            <div class="logo-text">BFP Admin Dashboard</div>
        </div>

        <!-- Notification Dropdown -->
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell notification-icon"></i>
                <span id="notification-counter" class="badge-counter">5</span> <!-- Example count -->
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <div id="notificationContainer">
                    <!-- Notifications will be injected here dynamically -->
                </div>
            </div>
        </div>

        <!-- Philippine time -->
        <span id="philippineTime" class="text-white">Philippine Standard Time: <span id="current-time"></span></span>
    </div>

    <!-- Firebase and Notification Scripts -->
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


        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                messaging.getToken({
                    vapidKey: 'YOUR_VAPID_KEY'
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

        // Load the alarm sound
        const sirenSound = new Audio('https://bfpcalapancity.online/public/45secs_alarm.mp3');
        sirenSound.preload = 'auto';

        let userInteracted = false;
        document.addEventListener('click', function() {
            userInteracted = true;
        });

        messaging.onMessage((payload) => {
            console.log('Message received: ', payload);

            if (payload && payload.notification) {
                const notificationContainer = document.getElementById('notificationContainer');
                const notification = document.createElement('div');
                notification.classList.add('notification-item');
                notification.innerHTML = `<h4>${payload.notification.title}</h4><p>${payload.notification.body}</p>`;
                notificationContainer.appendChild(notification);

                if (userInteracted) {
                    sirenSound.play().catch(error => {
                        console.error('Error playing siren sound:', error);
                    });
                } else {
                    console.log('Siren sound not played. Waiting for user interaction.');
                }
            } else {
                console.error('Payload does not contain expected notification data.');
            }
        });

        function fetchLatestReports() {
            fetch("https://bfpcalapancity.online/getLatestReports")
                .then(response => response.json())
                .then(data => {
                    const notificationContainer = document.getElementById('notificationContainer');
                    notificationContainer.innerHTML = '';
                    let notificationCount = data.length;
                    
                    if (notificationCount > 0) {
                        document.getElementById('notification-counter').textContent = notificationCount;
                    } else {
                        document.getElementById('notification-counter').textContent = '0';
                    }

                    data.forEach(report => {
                        const notification = document.createElement('div');
                        notification.classList.add('notification-item');
                        notification.setAttribute('data-communityreport-id', report.communityreport_id);
                        
                        const mediaContent = (report.fileproof.endsWith('.jpg') || report.fileproof.endsWith('.png'))
                            ? `<img src="${report.fileproof}" alt="File Proof" class="w-16 h-16 mr-3 rounded-md">`
                            : `<video src="${report.fileproof}" class="w-16 h-16 mr-3 rounded-md" controls></video>`;

                        notification.innerHTML = `
                            ${mediaContent}
                            <div>
                                <h4>${report.fullName}</h4>
                                <p><strong>Submitted:</strong> ${report.timestamp}</p>
                            </div>
                        `;

                        notification.addEventListener('click', () => {
                            window.location.href = `/rescuemap?communityreport_id=${report.communityreport_id}`;
                        });

                        notificationContainer.appendChild(notification);
                    });
                })
                .catch(error => console.error('Error fetching reports:', error));
        }

        setInterval(fetchLatestReports, 60000);
        fetchLatestReports();

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

        setInterval(updatePhilippineTime, 1000);
        updatePhilippineTime();
    </script>

    <!-- jQuery and Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
