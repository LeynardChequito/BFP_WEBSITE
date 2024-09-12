<!DOCTYPE html>
<html lang="en">

          
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

        .container-fluid {
            margin-left: 10px;
            margin-top: 1px;
            margin-bottom: 5px;
            padding: 10px;
            background-color: darkslategrey;
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
    <?= view('ACOMPONENTS/adminheader'); ?>

    <div class="container-fluid">

        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-10">
                <div class="content">
                    <h3>Welcome to BFP Admin Dashboard</h3>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#newsModal">
                        Add News
                    </button>

                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th class="col-md-1">News ID</th>
                                <th class="col-md-3">Title</th>
                                <th class="col-md-4">Content</th>
                                <th class="col-md-2">Image</th>
                                <th class="col-md-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            <?php foreach ($news as $item) : ?>
                                <?php if ($count < 5) : ?>
                                    <tr class="table-row">
                                        <td><?= $item['news_id']; ?></td>
                                        <td><?= substr($item['title'], 0, 50); ?> ...</td>
                                        <td><?= substr($item['content'], 0, 100); ?> ...</td>
                                        <td><?= $item['image']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning edit-news-btn" data-newsid="<?= $item['news_id'] ?>" data-title="<?= $item['title'] ?>" data-content="<?= $item['content'] ?>" data-image="<?= $item['image'] ?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger delete-news-btn" data-newsid="<?= $item['news_id'] ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="mt-3">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>

                </div>
            </div>
        </div>

        <?= view('hf/footer'); ?>

    </div>

    <?= view('ACOMPONENTS/NEWS/NewsCreate'); ?>

    <!-- Edit News Modal -->
    <div class="modal fade" id="editNewsModal" tabindex="-1" role="dialog" aria-labelledby="editNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNewsModalLabel">Edit News</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editNewsForm" action="<?= base_url('news/update') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="news_id" id="editNewsId" value="">
                        <div class="form-group">
                            <label for="editNewsTitle">Title</label>
                            <input type="text" class="form-control" id="editNewsTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="editNewsContent">Content</label>
                            <textarea class="form-control" id="editNewsContent" name="content" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editNewsImage">Image</label>
                            <input type="file" class="form-control" id="editNewsImage" name="image" multiple>
                            <img src="" alt="News Image" class="img-thumbnail" id="editNewsImagePreview" style="max-width: 100%; height: auto;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditForm()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to set up the edit modal with data
        function setEditModal(newsId, title, content, image) {
            $('#editNewsId').val(newsId);
            $('#editNewsTitle').val(title);
            $('#editNewsContent').val(content);
            $('#editNewsImagePreview').attr('src', '<?= base_url('public/newsphoto/') ?>' + image);
            $('#editNewsModal').modal('show');
        }

        // Event listener for the edit button click
        $(document).on('click', '.edit-news-btn', function() {
            var newsId = $(this).data('newsid');
            var title = $(this).data('title');
            var content = $(this).data('content');
            var image = $(this).data('image');

            // Call the function to set up the edit modal with data
            setEditModal(newsId, title, content, image);
        });

        // Function to submit the edit form
        function submitEditForm() {
            $('#editNewsForm').submit();
        }

        // Function to handle delete confirmation
        function showDeleteConfirmation(newsId) {
            var confirmation = confirm("Are you sure you want to delete this news?");
            if (confirmation) {
                window.location.href = '<?= base_url('delete/') ?>' + newsId;
            }
        }

        // Event listener for the delete button click
        $(document).on('click', '.delete-news-btn', function() {
            var newsId = $(this).data('newsid');
            showDeleteConfirmation(newsId);
        });

        // After deleting a news item, redirect back to the newscreate page
        <?php if (session()->has('success')) : ?>
            window.location.href = '<?= base_url('newscreate') ?>';
        <?php endif; ?>
    </script>
</body>

</html>