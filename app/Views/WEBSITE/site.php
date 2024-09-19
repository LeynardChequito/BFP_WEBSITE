<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Call Form</title> <!-- Missing closing tag fixed -->

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

<body onload="getLocation();">
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

    <!-- Second Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php echo (current_url() == site_url('/home')) ? 'active' : ''; ?>">
                    <a href="<?= site_url('/home') ?>" class="nav-link">Home</a>
                </li>
                <li class="nav-item <?php echo (current_url() == site_url('/activities')) ? 'active' : ''; ?>">
                    <a href="<?= site_url('/activities') ?>" class="nav-link">Activities</a>
                </li>
                <li class="nav-item <?php echo (current_url() == site_url('/achievements')) ? 'active' : ''; ?>">
                    <a href="<?= site_url('/achievements') ?>" class="nav-link">Achievements</a>
                </li>
                <li class="nav-item <?php echo (current_url() == site_url('/contacts')) ? 'active' : ''; ?>">
                    <a href="<?= site_url('/contacts') ?>" class="nav-link">Contact Us</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emergency Call Form</h5>
                    <button type="button" class="close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="emergencyForm" action="<?= site_url('communityreport/submit') ?>" enctype="multipart/form-data" method="post">
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
                            <label for="fileproof">Upload File:</label>
                            <input type="file" id="fileproof" name="fileproof" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
 document.addEventListener('DOMContentLoaded', function() {
    var emergencyForm = document.getElementById('emergencyForm');
    if (emergencyForm) {
        emergencyForm.addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?= site_url('communityreport/submit') ?>", true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Form submitted successfully!");

                            // Extract necessary data from formData
                            var latitude = formData.get('latitude');
                            var longitude = formData.get('longitude');
                            var fullName = formData.get('fullName');

                            // Call the function to send the notification
                            submitEmergencyCall({
                                latitude: latitude,
                                longitude: longitude,
                                fullName: fullName
                            });

                            // Send message to parent window to update the map
                            window.parent.postMessage({
                                action: 'updateMap',
                                data: {
                                    latitude: latitude,
                                    longitude: longitude,
                                    fullName: fullName
                                }
                            }, '*');

                            closeModal();
                        } else {
                            alert("Form submission failed: " + response.message);
                        }
                    } catch (e) {
                        alert("Error parsing server response: " + e.message);
                    }
                } else {
                    alert("Form submission failed with status: " + xhr.status);
                }
            };

            xhr.onerror = function() {
                alert("Request error. Please check your network connection.");
            };

            xhr.send(formData);
        });
    }
});


        // Function to get the user's current location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Function to display user's current position on the modal form
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }


        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        // Close the modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Get user's location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Set latitude and longitude in form
        function showPosition(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }

        // Handle errors
        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        // Display Philippine Time
        function updatePhilippineTime() {
            const options = {
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            };

            const now = new Date();
            document.getElementById("philippineTime").textContent = now.toLocaleTimeString("en-US", options);
        }

        // Update time every second
        setInterval(updatePhilippineTime, 1000);

        // Call update immediately to avoid delay
        updatePhilippineTime();
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
