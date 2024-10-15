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
       :root {
            --primary-color: #EF3340;
            --secondary-color: #343a40;
            --background-gradient: linear-gradient(0deg, black, #480000, #f0f0f0);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background-gradient);
            color: var(--secondary-color);
        }
        .navbar {
            background-color: var(--primary-color);
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        #carouselExample {
            max-width: 100%;
            height: 500px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
        }
        .carousel-inner, .carousel-item, .carousel-item img {
            height: 100%;
            object-fit: cover;
        }
        .btn-news {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-news:hover {
            background-color: #d62b36;
            transform: translateY(-2px);
        }
        .navigation-container {
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>

<body>

    <?= view('WEBSITE/site'); ?>

    <div class="container">
        <div class="row justify-content-center">
            <!---------------------------------------  CAROUSEL IMAGES ----------------------------------------------->
            <div class="col-md-12">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($imageSources)): ?>
                            <?php foreach ($imageSources as $index => $imageSource): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img class="d-block w-100" src="<?= base_url($imageSource['image_url']) ?>" alt="Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="<?= base_url('bfpcalapancity/public/images') ?>" alt="Default Image">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="navigation-container">
            <div class="row g-3">
                <!--------------------------------------- NEWS PRESS RELEASE ----------------------------------------------->
                <div class="col-md-4 col-lg-2">
                    <a href="<?= site_url('news') ?>" class="btn btn-news w-100"><i class="fas fa-newspaper me-2"></i>News</a>
                </div>
                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-4 col-lg-2">
                    <a href="<?= site_url('announcements') ?>" class="btn btn-news w-100"><i class="fas fa-bullhorn me-2"></i>Announcements</a>
                </div>
                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-4 col-lg-2">
                    <a href="<?= site_url('safety-tips') ?>" class="btn btn-news w-100"><i class="fas fa-shield-alt me-2"></i>Safety Tips</a>
                </div>
                <!--------------------------------------- SAFETY TIPS  ----------------------------------------------->
                <div class="col-md-4 col-lg-2">
                    <a href="<?= site_url('holidays') ?>" class="btn btn-news w-100"><i class="fas fa-calendar-alt me-2"></i>Holidays</a>
                </div>
                <!---------------------------------------  LINK TO OTHER AGENCIES ----------------------------------------------->
                <div class="col-md-4 col-lg-4">
                    <a href="<?= site_url('agencies') ?>" class="btn btn-news w-100"><i class="fas fa-link me-2"></i>Link To Other Agencies</a>
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
