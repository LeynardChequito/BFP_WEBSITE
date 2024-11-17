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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />

    <style>
    body {
            background-image: linear-gradient(0deg, black, #480000, #f0f0f0);
            color: #343a40;
        }
        .carousel-container {
            height: 20rem;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .advertisement-container {
            max-height: 300px;
            overflow-y: auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .advertisement-card {
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .advertisement-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Adjust spacing for grid layout */
        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
        }
    
    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Modal Container */
    .modal-content {
        background-color: #ffffff;
        border-radius: 10px;
        max-width: 500px;
        width: 90%;
        padding: 2rem;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    /* Fade-in Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .modal-header h5 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333333;
    }

    .modal-header .close {
        font-size: 1.5rem;
        cursor: pointer;
        color: #888888;
        transition: color 0.2s;
    }

    .modal-header .close:hover {
        color: #333333;
    }

    /* Form Styling */
    .modal-body {
        margin-top: 1rem;
    }

    .modal-body label {
        font-size: 0.9rem;
        font-weight: bold;
        color: #555555;
        margin-bottom: 0.5rem;
    }

    .modal-body .form-control {
        border: 1px solid #dddddd;
        border-radius: 5px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        color: #333333;
        background-color: #f9f9f9;
        margin-bottom: 1rem;
    }

    .modal-body .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Submit Button */
    .modal-body .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        font-weight: bold;
        color: #ffffff;
        transition: background-color 0.2s;
    }

    .modal-body .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
</head>

<body class="bg-gray-900 font-sans text-white">

    <!-- Navbar -->
    <?= view('WEBSITE/site'); ?>

    <!-- Main Content Wrapper -->
    <div class="container mx-auto my-8 p-4">
        <div class="grid-container">

            <!-- Left Column: Carousel -->
            <div class="carousel-container bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div id="carouselExample" class="carousel slide relative" data-ride="carousel">
                    <div class="carousel-inner h-full">
                        <?php if (!empty($imageSources)): ?>
                            <?php foreach ($imageSources as $index => $imageSource): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img class="w-full h-full object-cover" src="<?= base_url($imageSource['image_url']) ?>" alt="Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img class="w-full h-full object-cover" src="<?= base_url('bfpcalapancity/public/images/default.jpg') ?>" alt="Default Image">
                            </div>
                        <?php endif; ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

            <!-- Right Sidebar: Advertisement -->
<div class="advertisement-container text-gray-900">
    <h5 class="text-2xl font-semibold text-red-700 mb-6">Suggested Readings</h5>
    <div class="space-y-2">
        <?php if (!empty($advertisementFiles)): ?>
            <?php foreach ($advertisementFiles as $file): ?>
                <?php
                    // Split the file paths by comma and select a random one
                    $filePaths = explode(',', $file['file_path']);
                    $randomFilePath = trim($filePaths[array_rand($filePaths)]);
                    $fileExtension = strtolower(pathinfo($randomFilePath, PATHINFO_EXTENSION));
                ?>
                <a href="<?= site_url("folders/file_details/{$file['file_id']}") ?>" class="advertisement-card hover:bg-gray-200 transition duration-200 block p-2">
                    <div class="flex items-center space-x-3">
                        <!-- Display image or video on the left side -->
                        <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?= base_url($randomFilePath) ?>" alt="<?= esc($file['title']) ?>" class="w-14 h-14 object-cover rounded-lg shadow-md">
                        <?php elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])): ?>
                            <video controls class="w-14 h-14 object-cover rounded-lg shadow-md">
                                <source src="<?= base_url($randomFilePath) ?>" type="video/<?= $fileExtension ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                        <!-- Content on the right side -->
                        <div>
                            <h6 class="text-sm font-semibold"><?= esc($file['title']) ?></h6>
                            <p class="text-xs text-gray-500"><?= esc($file['main_folder_name']) ?> • <?= date('M d, Y', strtotime($file['created_at'])) ?></p>
                            <p class="text-xs text-gray-600 mt-1">Read more</p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600 text-sm">No advertisements available.</p>
        <?php endif; ?>
    </div>
</div>



        <!-- Bottom Row: Navigation Container spanning full width -->
        <div class="navigation-container flex justify-center space-x-4 my-4">
            <?php if (!empty($mainFolders) && is_array($mainFolders)): ?>
                <?php foreach ($mainFolders as $mainFolder): ?>
                    <a href="<?= site_url("folders/view_sub_folders/{$mainFolder['main_folder_id']}") ?>"
                        class="px-6 py-2 bg-red-500 text-white font-semibold rounded-full shadow-lg hover:bg-red-600 transition duration-300">
                        <?= esc($mainFolder['name']) ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-white">No folders available.</p>
            <?php endif; ?>
        </div>
    </div>


    <?= view('hf/footer'); ?>

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
            const notificationContainer = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.classList.add('notification');
            notification.innerHTML = `
                <h2>${payload.notification.title}</h2>
                <p>${payload.notification.body}</p>
            `;
            notificationContainer.appendChild(notification);
        });

        $(document).ready(function() {
            $('#carouselExample').carousel({
                interval: 2000
            });
        });
        
            // Function to close modal
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }
    </script>
</body>

</html>