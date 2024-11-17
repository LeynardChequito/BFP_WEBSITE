<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($file['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?= view('WEBSITE/site'); ?>

    <div class="container mx-auto my-8 p-4 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4"><?= esc($file['title']) ?></h2>
        
        <p class="text-gray-600 mb-4"><?= esc($file['description']) ?></p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <?php 
            // Decode the JSON array of file paths
            $mediaFiles = json_decode($file['file_path'], true); 
            ?>
            <?php foreach ($mediaFiles as $media): ?>
                <?php 
                    // Determine the file extension
                    $fileExtension = strtolower(pathinfo($media, PATHINFO_EXTENSION)); 
                ?>
                <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <!-- Display image -->
                    <img src="<?= base_url(trim($media)) ?>" alt="<?= esc($file['title']) ?>" class="w-full h-48 object-cover rounded-lg">
                <?php elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])): ?>
                    <!-- Display video -->
                    <video controls class="w-full h-48 object-cover rounded-lg">
                        <source src="<?= base_url(trim($media)) ?>" type="video/<?= $fileExtension ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <p class="text-sm text-gray-500">
            <?php if ($file['updated_at']): ?>
                Updated on: <?= date('F j, Y', strtotime($file['updated_at'])) ?>
            <?php else: ?>
                Created on: <?= date('F j, Y', strtotime($file['created_at'])) ?>
            <?php endif; ?>
        </p>

        <a href="javascript:history.back()" class="text-blue-500 mt-6 inline-block">Go back</a>
    </div>
    <?= view('hf/footer'); ?>
</body>
</html>
