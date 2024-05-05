
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .album {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
            margin: 20px;
        }

        .album-info {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .album img {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .album img:hover {
            transform: scale(1.1);
        }

        .btn.btn-danger.mt-3 {
            margin-top: 20px; /* Adjusted margin for spacing */
        }
    </style>
</head>

<body>
    <!-- First Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark default-color" style="background-color: red;">
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

    
<?= $this->renderSection('content') ?>

<div class="album">
    <div class="album-info">
        <h3><strong>Inspection</strong></h3>
        <a href="<?= site_url('/Show More') ?>" class="nav-link"></a>
        <img src="website/inspection/1.jpg" alt="Photo 2">
        <img src="website/inspection/2.jpg" alt="Photo 3">
        <img src="website/inspection/3.jpg" alt="Photo 4">
        <img src="website/inspection/4.jpg" alt="Photo 2">
        <img src="website/inspection/5.jpg" alt="Photo 3">
        <img src="website/inspection/7.jpg" alt="Photo 2">
        <img src="website/inspection/8.jpg" alt="Photo 3">
        <!-- Add more images as needed -->
    </div>
</div>

<?= view('hf/footer'); ?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
        // Function to update Philippine time
        function updatePhilippineTime() {
            const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric' };
            const philippineTime = new Date().toLocaleString('en-US', options);
            document.getElementById('philippineTime').innerText = philippineTime;
        }

        // Update time initially and set interval to update every second
        updatePhilippineTime();
        setInterval(updatePhilippineTime, 1000);
    </script>
</body>
</html>
