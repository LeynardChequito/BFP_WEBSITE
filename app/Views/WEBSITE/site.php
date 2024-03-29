<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Call Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Custom styles */
        /* Add your custom styles here */
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
            background-color: rgba(0,0,0,0.4);
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
    </style>
</head>
<body>
  <!-- First Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark default-color">
    <img src="<?= base_url(); ?>images/Banner03_18Aug2018.png" alt="Logo" class="logo">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Use Bootstrap grid system for alignment -->
    <div class="ml-auto row align-items-center">
        <div class="col-auto">
            <button id="btncall" class="btn btn-success my-2 my-sm-0" onclick="openModal()">Emergency Call </button>
        </div>
        <div class="col-auto text-white">
            <span class="font-weight-bold">Ph Standard Time:</span>
            <div id="philippineTime" class="ml-2"></div>
        </div>
        <a class="btn btn-danger" href="<?= site_url('/logout') ?>">Logout</a>
    </div>
</nav>

  <!-- Second Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#additionalNav"
      aria-controls="additionalNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a href="<?= site_url('/home') ?>" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="activities" class="nav-item nav-link">Activities</a>
        </li>
        <li class="nav-item">
        <a href="achievements" class="nav-item nav-link">Achievements</a>
        </li>
        <li class="nav-item">
        <a href="contacts" class="nav-item nav-link">Contact Us</a>
        </li>
      </ul>
    </div>
  </nav>

<!-- Modal Form -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h4 class="text-center">Emergency Call Form</h4>

        <form id="emergencyCallForm" action="<?= base_url('emergency-call/submit') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <input type="hidden" id="user_id" name="user_id" value="<?= session('user_id') ?>">
                <label for="fullName"></label>
                <input type="text" id="fullName" name="fullName" class="form-control readonly" value="<?= session('fullName') ?>" readonly>
                
                <label for="fire_type">Type of Fire Incident:</label>
                <select id="fire_type" name="fire_type" class="form-control" required>
                    <option value="Residential Fire">Residential Fire</option>
                    <option value="Commercial Fire">Commercial Fire</option>
                    <option value="Wildfire">Wildfire</option>
                    <option value="Vehicle Fire">Vehicle Fire</option>
                    <option value="Other">Other (Please Explain)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fire_size">Size of Fire:</label>
                <select id="fire_size" name="fire_size" class="form-control" required>
                    <option value="Small">Small (noticed only in a small area)</option>
                    <option value="Medium">Medium (affecting several buildings or structures)</option>
                    <option value="Large">Large (putting many buildings or structures at risk)</option>
                    <option value="Under Control">Under Control (fire is controlled)</option>
                    <option value="Uncertain">Uncertain (no definite information)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="road_type">Type of Roadways:</label>
                <select id="road_type" name="road_type" class="form-control" required>
                    <option value="Highway">Highway </option>
                    <option value="Street">Street</option>
                    <option value="Avenue">Avenue </option>
                    <option value="Boulevard">Boulevard</option>
                    <option value="Rural Road">Rural Road </option>
                    <option value="Expressway">Expressway </option>
                    <option value="Alley">Alley </option>
                    <option value="Service Road">Service Road </option>
                </select>
            </div>

            <div class="form-group">
                <label for="additional_info">Additional Information:</label>
                <textarea id="additional_info" name="additional_info" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="photo_upload">Add a Photo (Optional):</label>
                <input type="file" id="photo_upload" name="photo_upload" class="form-control-file">
            </div>

            <!-- Hidden fields for latitude and longitude -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>
</div>

<script>
    // Function to open the modal
    function openModal() {
        document.getElementById("myModal").style.display = "block";
        getLocation(); // Get location when modal opens
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    // Function to update Philippine time
    function updatePhilippineTime() {
        const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric' };
        const philippineTime = new Date().toLocaleString('en-US', options);
        document.getElementById('philippineTime').innerText = philippineTime;
    }

    // Update time initially and set interval to update every second
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);

    // Function to get user's current location
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Function to display user's current position
    function showPosition(position) {
        document.getElementById("latitude").value = position.coords.latitude;
        document.getElementById("longitude").value = position.coords.longitude;
    }
</script>

<!-- Your other scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
