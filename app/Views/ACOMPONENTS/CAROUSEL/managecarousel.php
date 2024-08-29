<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel Images</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <!-- Full jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js and Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
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
            z-index: 1; 
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .container-fluid {
            margin-left: 10px;
            margin-top: 1px;
            margin-bottom: 5px;
            padding: 10px;
            background-color: darkslategrey;
            z-index: 1; /* Ensure container has a lower z-index */
        }

        .container-fluid h3 {
            margin-bottom: 20px;
        }

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

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 1040; 
            display: none; 
        }

        .edit-container {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 30%;
            z-index: 1050;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            display: none;
        }

        .edit-container .close-btn {
            float: right;
            font-size: 1.5rem;
            cursor: pointer;
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

        .carousel-image {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?= view('ACOMPONENTS/adminheader'); ?>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Edit Container -->
    <div class="edit-container" id="editContainer">
        <span class="close-btn" id="closeEditForm">&times;</span>
        <form id="editForm" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image_source">Select New Image</label>
                <input type="file" name="image_source" class="form-control" multiple>
            </div>
            <img id="currentImage" src="" alt="Current Image" class="img-thumbnail">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-10">
                <div class="content">
                    <h3>Manage Carousel Images</h3>

                    <?php if (session()->getFlashdata('status')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('status') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('carousel/store') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="image_source">Add New Images</label>
                            <input type="file" name="image_source[]" class="form-control" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Images</button>
                    </form>

                    <hr>

                    <h3>Existing Images</h3>
<ul class="list-group">
    <?php foreach ($imageSources as $image): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= base_url($image['image_url']) ?>" class="carousel-image" alt="Carousel Image">
                <span class="image-url"><?= basename($image['image_url']) ?></span>
            </div>
            <span>
                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= $image['carousel_id'] ?>" data-url="<?= $image['image_url'] ?>">Edit</button>
                <form action="<?= base_url('carousel/delete/' . $image['carousel_id']) ?>" method="post" style="display:inline-block;">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </span>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Pagination Links -->
<div class="mt-3">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>
                </div>
            </div>
        </div>

        <?= view('hf/footer'); ?>
    </div>

    <script>
        $(document).ready(function () {
            $('.edit-btn').click(function () {
                var button = $(this);
                var id = button.data('id');
                var url = button.data('url');

                // Display the overlay and edit container using CSS
                $('#overlay').css('display', 'block');
                $('#editContainer').css('display', 'block');

                // Set the image and form action in the edit container
                $('#editForm').attr('action', "<?= base_url('carousel/update/') ?>" + id);
                $('#currentImage').attr('src', "<?= base_url() ?>" + '/' + url);
            });

            // Close the edit form
            $('#closeEditForm').click(function () {
                $('#editContainer').css('display', 'none');
                $('#overlay').css('display', 'none');
            });

            // Close the edit form if overlay is clicked
            $('#overlay').click(function () {
                $('#editContainer').css('display', 'none');
                $('#overlay').css('display', 'none');
            });
        });
    </script>
</body>

</html>
