<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subfolders of <?= esc($mainFolder['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?= view('WEBSITE/site'); ?>

<div class="container mx-auto my-8 p-4 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Subfolders of <?= esc($mainFolder['name']) ?></h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        <?php foreach ($subFolders as $subFolder): ?>
            <div class="border rounded-lg p-4 bg-white shadow">
                <h3 class="text-xl font-semibold mb-2"><?= esc($subFolder['name']) ?></h3>
                <p class="text-gray-500 mb-2">
                    <?= $subFolder['updated_at'] ? 'Updated on: ' . date('F j, Y', strtotime($subFolder['updated_at'])) : 'Created on: ' . date('F j, Y', strtotime($subFolder['created_at'])) ?>
                </p>

                <!-- List of files in the subfolder -->
                <?php if (!empty($subFolder['files'])): ?>
                    <ul class="list-disc ml-4">
                        <?php foreach ($subFolder['files'] as $file): ?>
                            <li>
                                <a href="<?= site_url("folders/file_details/{$file['file_id']}") ?>" class="text-blue-500 hover:underline">
                                    <?= esc($file['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-400">No files available in this subfolder.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="<?= site_url('/home') ?>" class="text-blue-500 mt-6 inline-block">Go back</a>
</div>

</body>
</html>
