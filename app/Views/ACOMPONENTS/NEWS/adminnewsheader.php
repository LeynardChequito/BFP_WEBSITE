          
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP WEBSITE Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            color: #fff;
        }

        .sidebar {
            margin-top: auto;
            background-color: #EF3340;
            color: #fff;
            height: 100vh;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            
            display: block;
        }

        /* .container-fluid {
            margin-left: 10px;
            margin-top: 1px;
            margin-bottom: 5px;
            padding: 10px;
        } */

        /* .container-fluid h3 {
            margin-bottom: 20px;
        } */

        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: darkviolet;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #343a40;
            color: #fff;
            border-bottom: none;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-primary:hover {
            background-color: #495057;
            border-color: #495057;
        }
    </style>
</head>

<body>
<!-- <div class="col-lg-2">
    <div class="sidebar">
        <h2 class="text-center mb-4">BFP Admin</h2>
        <ul class="list-unstyled components">
            <hr style="background-color: white; height: 2px; border: none;">

            <li>
                <a href="<?= site_url('/admin-home') ?>">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <a href="<?= site_url('/newscreate') ?>">
                    <i class="fas fa-newspaper mr-2"></i>Manage News
                </a>
            </li>
            <li>
                <a href="<?= site_url('carousel') ?>">
                    <i class="fas fa-images mr-2"></i>Manage Carousel
                </a>
            </li>
            <hr style="background-color: white; height: 2px; border: none;">

            
            <li>
                <a href="<?= site_url('rescuer-report/form') ?>">
                   <i class="fas fa-file-alt mr-2"></i> Add Fire Report
                </a>
            </li>
            <hr style="background-color: white; height: 2px; border: none;">

            <li>
                <a href="<?= site_url('graph') ?>">
                    <i class="fas fa-chart-pie mr-2"></i>Dashboard
                </a>
            </li>
        </ul>
        <hr style="background-color: white; height: 2px; border: none;">

        <a href="<?= site_url('/admin-registration') ?>" class="create-account-btn btn btn-danger btn-block mt-4">Create an Account</a>
        <a class="btn btn-danger btn-block mt-4" href="<?= site_url('/admin-logout') ?>">Logout</a>
    </div>
</div>
</body>
</html> -->