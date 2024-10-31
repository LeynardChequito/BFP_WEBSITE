<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Preview</title>
    <style>
        .folder, .subfolder, .file {
            margin: 10px 0;
        }
        .file img, .file video {
            max-width: 200px;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>File Preview</h1>
    <?php foreach ($mainFolders as $mainFolder): ?>
        <div class="folder">
            <h2>Main Folder: <?= $mainFolder['name'] ?></h2>
            <?php if (!empty($mainFolder['subfolders'])): ?>
                <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                    <div class="subfolder">
                        <h3>Subfolder: <?= $subFolder['name'] ?></h3>
                        <?php if (!empty($subFolder['files'])): ?>
                            <?php foreach ($subFolder['files'] as $file): ?>
                                <div class="file">
                                    <p>Title: <?= $file['title'] ?></p>
                                    <p>Description: <?= $file['description'] ?></p>
                                    <?php
                                    $fileExtension = pathinfo($file['file_path'], PATHINFO_EXTENSION);
                                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                        <img src="<?= base_url($file['file_path']) ?>" alt="<?= $file['title'] ?>">
                                    <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])): ?>
                                        <video controls>
                                            <source src="<?= base_url($file['file_path']) ?>" type="video/<?= $fileExtension ?>">
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No files available in this subfolder.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No subfolders available in this main folder.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
