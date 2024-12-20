<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Call Form</title>

    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>

    <!-- Load ArcGIS REST JS from CDN -->
    <script src="https://unpkg.com/@esri/arcgis-rest-request@4.0.0/dist/bundled/request.umd.js"></script>
    <script src="https://unpkg.com/@esri/arcgis-rest-routing@4.0.0/dist/bundled/routing.umd.js"></script>

    <!-- Load Esri Leaflet Routing from CDN -->
    <script src="https://unpkg.com/esri-leaflet-routing@3.1.1/dist/esri-leaflet-routing.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="manifest" href="app/Views/manifest.json">

    <style>
        .bureau-of-fire-protection {
            font-family: "Bebas Neue", sans-serif;
            font-weight: 400;
            font-size: 60px;
            font-style: normal;
            color: #f5f5f5;
            margin-top: 0;
            margin-bottom: 0;
        }

        #map {
            height: 400px;
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
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;

        }

        .modal-header {
            border-bottom: none;
        }

        .modal-header .close {
            margin: -1rem -1rem -1rem auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Form styles */
        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .readonly {
            border: none;
            background-color: #f5f5f5;
            padding: 8px;
        }

        .navbar-dark .navbar-nav .nav-link {
            font-size: 16px;
        }

        .navbar-brand img {
            width: 90px;
            height: 90px;
        }

        .navbar-brand p {
            font-family: "Bebas Neue", sans-serif;
            font-size: 24px;
            margin: 0;
        }

        #map {
            height: 400px;
        }

        button[type="submit"] {
            width: 100%;
        }

        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- First Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-image: linear-gradient(150deg, black, red);">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url(); ?>/bfpcalapancity/public/images/logo.png" alt="Logo">
            <p class="d-inline-block text-white ml-2" contenteditable="true">Bureau of Fire Protection</p>
        </a>
        <div class="ml-auto row align-items-center">
            <div class="col-auto">
                <button id="btncall" class="btn btn-success my-2 my-sm-0" onclick="openModal()">Emergency Call</button>
            </div>
            <div class="col-auto text-white">
                <span class="font-weight-bold">Ph Standard Time:</span>
                <div id="philippineTime" class="ml-2"></div>
            </div>
            <a class="btn btn-danger" href="<?= site_url('/logout') ?>">Logout</a>
        </div>
    </nav>


<!-- Second Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="<?= site_url('/home') ?>" class="nav-link">Home</a>
            </li>
            <!-- Dynamic Main Folders in Dropdown -->
            <?php if (!empty($mainFolders) && is_array($mainFolders)): ?>
                <?php foreach ($mainFolders as $mainFolder): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown<?= esc($mainFolder['main_folder_id']) ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= esc($mainFolder['name']) ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown<?= esc($mainFolder['main_folder_id']) ?>">
                            <?php if (!empty($mainFolder['subfolders']) && is_array($mainFolder['subfolders'])): ?>
                                <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                                    <a class="dropdown-item" href="<?= site_url("folders/view_sub_folders/{$subFolder['sub_folder_id']}") ?>">
                                        <?= esc($subFolder['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="dropdown-item text-muted">No Subfolders</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="nav-item">
                    <a href="#" class="nav-link disabled">No folders available</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>


    <!-- Modal for Emergency Form -->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emergency Call Form</h5>
                    <button type="button" class="close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="emergencyForm" action="<?= site_url('communityreport/submit') ?>" enctype="multipart/form-data" method="post">

                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />


                        <div class="form-group">
                            <label for="fullName">Your Name:</label>
                            <input type="text" id="fullName" name="fullName" class="form-control readonly" value="<?= session('fullName') ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude:</label>
                            <input type="text" id="latitude" name="latitude" class="form-control readonly" readonly>
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude:</label>
                            <input type="text" id="longitude" name="longitude" class="form-control readonly" readonly>
                        </div>
                        <div class="form-group">
                            <label for="fileproof">Upload File Proof (Image/Video)</label>
                            <input type="file" name="fileproof" id="fileproof" class="form-control" accept="image/*, video/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>

    <script type="module">
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the location is obtained after DOM is fully loaded
            getLocation();
        });

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
        let mToken;

        // Retrieve FCM token for the current device
function getToken() {
    messaging.getToken({
            vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc'
        })
        .then((currentToken) => {
            if (currentToken) {
                console.log('Token retrieved:', currentToken);
                localStorage.setItem('rescuerToken', currentToken); // Store token for later use
            } else {
                console.log('No registration token available.');
            }
        })
        .catch((error) => {
            console.error('Error retrieving token:', error);
        });
}



        // Handle incoming messages (while the app is in the foreground)
        messaging.onMessage((payload) => {
            console.log('Message received:', payload);
            displayNotification(payload.notification);
        });

        window.openModal = function() {
        document.getElementById("myModal").style.display = "block";
        getLocation(); // Get the user's current location
    };

    window.closeModal = function() {
        document.getElementById("myModal").style.display = "none";
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Ensure the location is obtained after DOM is fully loaded
        getLocation();

        // Form submission logic
        const emergencyForm = document.getElementById('emergencyForm');

        if (emergencyForm) {
            emergencyForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "https://bfpcalapancity.online/communityreport/submit", true);

                xhr.onload = function() {
                    try {
                        const contentType = xhr.getResponseHeader("content-type");

                        if (contentType && contentType.includes("application/json")) {
                            const response = JSON.parse(xhr.responseText);

                            if (response.success) {
                                alert(response.message);
                                triggerNotification("New Emergency Call", response.message);
                                closeModal();
                            } else {
                                const errorMessages = response.errors
                                    ? Object.values(response.errors).join(', ')
                                    : 'Unknown error occurred';
                                alert("Form submission failed: " + errorMessages);
                            }
                        } else {
                            console.error("Unexpected response type:", xhr.responseText);
                            alert("Unexpected response from server. Please check logs.");
                        }
                    } catch (error) {
                        console.error("Error parsing the response as JSON:", error);
                        alert("An error occurred. Please try again.");
                    }
                };

                xhr.send(formData); // Don't forget to send the form data
            });
        }

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.error('Service Worker registration failed:', error);
                });
        }

        // Trigger a notification
        function triggerNotification(title, body) {
            if (Notification.permission === "granted") {
                new Notification(title, {
                    body: body
                });
            } else {
                Notification.requestPermission().then((permission) => {
                    if (permission === "granted") {
                        new Notification(title, {
                            body: body
                        });
                    }
                });
            }
        }

        // Update Philippine time
        function updatePhilippineTime() {
            const options = {
                timeZone: 'Asia/Manila',
                hour12: true,
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            };
            document.getElementById('philippineTime').innerText = new Date().toLocaleString('en-US', options);
        }

        setInterval(updatePhilippineTime, 1000);
    });
    </script>

    <!-- Your other scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>