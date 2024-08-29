<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau of Fire Protection News Update</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            margin-top: 40px;
            position: relative;
        }

        .btn {
            padding: 10px 20px;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img {
            max-height: 200px;
            object-fit: cover;
        }

        .article-title {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
        }

        .article-slug {
            font-size: 16px;
            color: #6c757d;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .article-content {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .article-preview-heading {
            text-align: center;
            font-weight: bold;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .pagination {
            justify-content: center;
        }

        #gallery {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
        }

        #gallery img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<?= view('WEBSITE/site'); ?>

    <div class="container mt-4">
        <h2 class="article-preview-heading mb-4">Article Preview</h2>
        <a href="javascript:history.go(-1);" class="btn btn-danger mt-3">Back</a>
        <div class="row">
            <?php foreach ($news as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="article-title"><?= esc(substr($item['title'], 0, 40)) ?>...</h3>
                            <p class="article-slug">Slug: <?= esc(substr($item['slug'], 0, 30)) ?>...</p>
                            <p class="article-content"><?= esc(substr($item['content'], 0, 200)) ?>...</p>
                            <a href="<?= site_url('news/' . esc($item['slug'])) ?>" class="btn btn-danger">Read Full Article</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-3">
    <?= $pager->links('default', 'bootstrap_pagination') ?>
</div>
    </div>

    <?= view('hf/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Add event listeners to previous and next buttons
        previousButton.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            displayImage(currentIndex);
        });

        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % images.length;
            displayImage(currentIndex);
        });
    </script>

</body>

</html>
