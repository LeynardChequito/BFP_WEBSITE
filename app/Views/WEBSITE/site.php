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

<body onload="getLocation();">
    <!-- First Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-image: linear-gradient(150deg, black, red);">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url(); ?>images/logo.png" alt="Logo">
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
                    <form action="<?= site_url('communityreport/submit') ?>" enctype="multipart/form-data" method="post">
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
                            <input type="file" name="fileproof" id="fileproof" class="form-control" accept="image/*;capture=camera,video/*;capture=camcorder" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener when DOM is fully loaded
        var emergencyForm = document.getElementById('emergencyForm');
        if (emergencyForm) {
            emergencyForm.addEventListener('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?= site_url('communityreport/submit') ?>", true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Form submitted successfully!");

                            window.parent.postMessage({
                                action: 'updateMap',
                                data: {
                                    latitude: formData.get('latitude'),
                                    longitude: formData.get('longitude'),
                                    fullName: formData.get('fullName')
                                }
                            }, '*');
                            closeModal();
                        } else {
                            alert("Form submission failed: " + response.message);
                        }
                    }
                };

                xhr.send(formData);
            });
        } else {
            console.error("Element with id 'emergencyForm' not found.");
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

        // Update the input fields in the modal form with the user's coordinates
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Function to open the modal and get the user's location
    function openModal() {
        document.getElementById("myModal").style.display = "block";
        getLocation(); // Get the user's current location
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    // Function to update Philippine time
    function updatePhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            hour12: true,
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric'
        };
        const philippineTime = new Date().toLocaleString('en-US', options);
        document.getElementById('philippineTime').innerText = philippineTime;
    }

    // Update time initially and set interval to update every second
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);
</script>

    <!-- Your other scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>