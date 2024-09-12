<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Carousel Image</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Carousel Image</h2>

        <form action="<?= base_url('carousel/update/' . $id) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image_source">Edit Image File</label>
                <input type="file" name="image_source" class="form-control" required>
                <div class="mt-3">
                    <p>Current Image:</p>
                    <img src="<?= base_url($imageSource['image_url']) ?>" alt="Current Image" class="img-thumbnail" style="max-width: 300px;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Image</button>
            <a href="<?= base_url('carousel') ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html> -->
