<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit File</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

<?= view('ACOMPONENTS/adminheader'); ?>
<?= view('ACOMPONENTS/amanagesidebar'); ?>

    <div class="w-full max-w-3xl mx-auto mt-16 p-8 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-center">Edit File</h2>
        <form action="<?= site_url("folders/updateFile/{$file['file_id']}") ?>" method="post">
            <?= csrf_field() ?>

            <!-- Title Input -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                <input type="text" name="title" id="title" class="w-full p-2 bg-gray-700 border border-gray-600 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= esc($file['title']) ?>" required>
            </div>

            <!-- Description Textarea -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                <textarea name="description" id="description" class="w-full p-2 bg-gray-700 border border-gray-600 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" required><?= esc($file['description']) ?></textarea>
            </div>

            <!-- Main Folder Dropdown -->
            <div class="mb-4">
                <label for="main_folder" class="block text-sm font-medium text-gray-300 mb-1">Main Folder</label>
                <select id="main_folder" class="w-full p-2 bg-gray-700 border border-gray-600 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadSubFolders(this.value)">
                    <option value="">Select Main Folder</option>
                    <?php foreach ($mainFolders as $mainFolder): ?>
                        <option value="<?= $mainFolder['main_folder_id'] ?>" <?= $file['sub_folder_id'] == $mainFolder['main_folder_id'] ? 'selected' : '' ?>>
                            <?= esc($mainFolder['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sub Folder Dropdown -->
            <div class="mb-6">
                <label for="sub_folder" class="block text-sm font-medium text-gray-300 mb-1">Sub Folder</label>
                <select name="sub_folder" id="sub_folder" class="w-full p-2 bg-gray-700 border border-gray-600 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <?php foreach ($mainFolders as $mainFolder): ?>
                        <?php if ($file['sub_folder_id'] == $mainFolder['main_folder_id']): ?>
                            <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                                <option value="<?= $subFolder['sub_folder_id'] ?>" <?= $file['sub_folder_id'] == $subFolder['sub_folder_id'] ? 'selected' : '' ?>>
                                    <?= esc($subFolder['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">Update File</button>
                <a href="/folders/manage" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-150">Cancel</a>
            </div>
        </form>
    </div>

<?= view('hf/footer'); ?>

    <script>
        function loadSubFolders(mainFolderId) {
            const subFolderSelect = document.getElementById('sub_folder');
            subFolderSelect.innerHTML = '';

            <?php foreach ($mainFolders as $mainFolder): ?>
                if (mainFolderId == '<?= $mainFolder['main_folder_id'] ?>') {
                    <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                        const option = document.createElement('option');
                        option.value = '<?= $subFolder['sub_folder_id'] ?>';
                        option.text = '<?= esc($subFolder['name']) ?>';
                        subFolderSelect.appendChild(option);
                    <?php endforeach; ?>
                }
            <?php endforeach; ?>
        }
    </script>
</body>
</html>
