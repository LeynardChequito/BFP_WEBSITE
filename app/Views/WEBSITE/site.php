<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Call Form</title>

    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f9;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Dark background */
            animation: fadeIn 0.5s; /* Smooth fade-in animation */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            animation: scaleIn 0.3s ease; /* Smooth scale animation */
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 10px;
        }

        .modal-header .close {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-header .close:hover {
            color: #ff4d4d;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }

        /* Form styling */
        form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .readonly {
            background-color: #f5f5f5;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Close button animation */
        .close:hover {
            color: #ff3333;
        }
    </style>
</head>

<body>
    <!-- First Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-image: linear-gradient(150deg, black, red);">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url(); ?>/bfpcalapancity/public/images/logo.png" alt="Logo">
            <p class="d-inline-block text-white ml-2">Bureau of Fire Protection</p>
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

    <!-- Modal for Emergency Form -->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emergency Call Form</h5>
                    <span class="close" onclick="closeModal()">&times;</span>
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
                            <input type="file" name="fileproof" id="fileproof" class="form-control" accept="image/*, video/*" capture="environment" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            // Get location when the DOM is fully loaded
            getLocation();

            const emergencyForm = document.getElementById('emergencyForm');
            if (emergencyForm) {
                emergencyForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "<?= site_url('communityreport/submit') ?>", true);

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            alert("Form submitted successfully!");
                            closeModal();
                        } else {
                            alert("Error submitting the form. Please try again.");
                        }
                    };

                    xhr.onerror = function () {
                        alert("An error occurred while submitting the form. Please check your connection.");
                    };

                    xhr.send(formData);
                });
            }

            // Function to show modal
            window.openModal = function () {
                document.getElementById("myModal").style.display = "block";
                getLocation();
            }

            // Function to close modal
            window.closeModal = function () {
                document.getElementById("myModal").style.display = "none";
            }

            // Function to get user's location
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            // Function to display user's position in the form
            function showPosition(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }
        });
    </script>

    <!-- Bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
