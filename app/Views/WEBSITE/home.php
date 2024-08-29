<?php
use App\Models\CarouselModel;
$carouselModel = new CarouselModel();
$imageSources = $carouselModel->findAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Official Website</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body {
            background-image: linear-gradient(0deg, black, #480000, #f0f0f0);
            color: #343a40;
        }

        #carouselExample {
            max-width: 1500px;
            width: 100%;
            height: 400px;
            border: 2px solid #EF3340;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            margin-right: auto;
            margin-left: auto;
            margin-top: 20px;
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

        .btn-news {
            margin-top: 20px;
            text-align: center;
            position: relative;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: auto auto auto auto;
            font-size: 12px;
            justify-content: space-evenly;
            gap: 10px; 
        }

        .buttons-container {
            position: relative;
            margin-top: 10px;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: auto auto auto auto;
            font-size: 12px;
            height: 100px;
            justify-content: space-evenly;
            gap: 10px; 
        }

        footer {
            margin-bottom: 0;
        }

        .navigation-container {
            position: relative;
            margin-top: 10px;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: auto auto auto auto;
            font-size: 12px;
            height: 100px;
            justify-content: space-evenly;
        }
    </style>
</head>

<body>

    <?= view('WEBSITE/site'); ?>

    <div class="container">
        <div class="row justify-content-center">
            <!---------------------------------------  CAROUSEL IMAGES ----------------------------------------------->
            <div class="col-md-16">
                <div id="carouselExample" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($imageSources)): ?>
                            <?php foreach ($imageSources as $index => $imageSource): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img class="d-block w-100" src="<?= base_url($imageSource['image_url']) ?>" alt="Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="<?= base_url('/path/to/default/image.jpg') ?>" alt="Default Image">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="navigation-container">
            <div class="row justify-content-center buttons-container">
                <!--------------------------------------- NEWS PRESS RELEASE ----------------------------------------------->
                <div class="col-md-1 offset-md-1">
                    <a href="<?= site_url('news') ?>" class="btn btn-danger btn-news">News</a>
                </div>

                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-2 offset-md-1">
                    <a href="<?= site_url('') ?>" class="btn btn-danger btn-news">Announcements</a>
                </div>

                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-1 offset-md-1">
                    <a href="<?= site_url('') ?>" class="btn btn-danger btn-news">Safety Tips</a>
                </div>

                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-1 offset-md-1">
                    <a href="<?= site_url('') ?>" class="btn btn-danger btn-news">Holidays</a>
                </div>

                <!---------------------------------------  LINK TO OTHER AGENCIES ----------------------------------------------->
                <div class="col-md-1 offset-md-1">
                    <a href="<?= site_url('news') ?>" class="btn btn-danger btn-news">Link To Other Agencies</a>
                </div>
            </div>
        </div>
    </div>

    <?= view('hf/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script type="module">
        // Import the necessary functions from the Firebase Messaging module
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-analytics.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging.js";

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
                getToken(messaging, { vapidKey: 'BNEXDb7w8VzvQt3rD2pMcO4vnJ4Q5pBRILpb3WMtZ3PSfoFpb6CmI5p05Gar3Lq1tDQt5jC99tLo9Qo3Qz7_aLc' }).then((currentToken) => {
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
            const notificationContainer = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.classList.add('notification');
            notification.innerHTML = `
                <h2>${payload.notification.title}</h2>
                <p>${payload.notification.body}</p>
            `;
            notificationContainer.appendChild(notification);
        });

        $(document).ready(function () {
            $('#carouselExample').carousel({
                interval: 2000
            });
        });
    </script>
</body>

</html>
