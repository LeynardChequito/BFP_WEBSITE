<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder Contents - <?= esc($mainFolder) ?><?= $subFolder ? ' / ' . esc($subFolder) : '' ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <a href="javascript:history.back();" class="btn btn-danger mb-3">Back</a>
    <h1>Contents of <?= esc($mainFolder) ?><?= $subFolder ? ' / ' . esc($subFolder) : '' ?></h1>

    <div class="row">
        <?php foreach ($files as $file): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <?php $filePath = base_url("bfpcalapancity/public/gallery/$mainFolder" . ($subFolder ? "/$subFolder" : "") . "/$file"); ?>
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)): ?>
                            <img src="<?= $filePath ?>" class="card-img-top" alt="Image">
                        <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $file)): ?>
                            <video controls style="width: 100%;">
                                <source src="<?= $filePath ?>" type="video/<?= pathinfo($file, PATHINFO_EXTENSION) ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <p><a href="<?= $filePath ?>" target="_blank"><?= esc($file) ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
