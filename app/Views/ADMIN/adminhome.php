<?php
$imageSources = [
    'images/BABALA-400-×-1500px.png',
    'images/fire-safety-advocacy-banner-2023-01.jpg',
    'images/images2.jpg',
    'images/bfp-modernization.jpg',
    'images/bfp-banner.jpg',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP WEBSITE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        #carouselExample {
            max-width: 1000px;
            width: 100%;
            height: 350px;
            border: 2px solid #EF3340;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            margin-right: auto;
            margin-left: auto;
        }

        .carousel-inner {
            width: 100%;
            height: 100%;
            margin-top: 1px;
        }

        .carousel-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 1s ease-in-out;
        }

        p {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <?= view('ACOMPONENTS/adminheader'); ?>

    <!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div id="carouselExample" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($imageSources as $index => $imageSource) : ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img class="d-block w-100" src="<?= base_url($imageSource) ?>" alt="Image <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <p><a href="<?= site_url('news') ?>" class="btn btn-danger">PRESS RELEASE</a></p>
            </div>
        </div>
    </div> -->

    <?= view('COMPONENTS/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script type="module">
        // Import the necessary functions from the Firebase Messaging module
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-analytics.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging.js";

        // Your Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAiXnOQoNLOxLWEAw5h5JOTJ5Ad8Pcl6R8",
            authDomain: "pushnotifbfp.firebaseapp.com",
            projectId: "pushnotifbfp",
            storageBucket: "pushnotifbfp.appspot.com",
            messagingSenderId: "214092622073",
            appId: "1:214092622073:web:fbcbcb035161f7110c1a28",
            measurementId: "G-XMBH6JJ3M6"
        };

        // Initialize Firebase app
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const messaging = getMessaging(app);

        // Request permission to receive notifications
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // Get registration token
                getToken(messaging, {
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

        // Handle incoming messages
        onMessage((payload) => {
            console.log('Message received: ', payload);
            try {
                // Check if the notification payload contains the 'notification' object and its 'title' property
                if (payload && payload.notification && payload.notification.title) {
                    // Display the received message as a notification
                    const notificationContainer = document.getElementById('notificationContainer');
                    const notification = document.createElement('div');
                    notification.classList.add('notification');
                    notification.innerHTML = `
                <h2>${payload.notification.title}</h2>
                <p>${payload.notification.body}</p>
            `;
                    notificationContainer.appendChild(notification);
                } else {
                    console.error('Notification payload does not contain title property.');
                }
            } catch (error) {
                console.error('An error occurred while processing the notification:', error);
            }
        });


        $(document).ready(function() {
            $('#carouselExample').carousel({
                interval: 2000
            });
        });
    </script>
</body>

</html>