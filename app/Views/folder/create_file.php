<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create File</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
        }

        .app-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            min-width: 250px;
            background-color: #dc3545;
            color: #fff;
            padding: 20px;
        }

        .main-content {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
        }

        .header {
            width: 100%;
            background-color: #343a40;
            color: #fff;
            padding: 16px;
        }

        .form-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.25);
        }

        .btn {
            transition: all 0.3s;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Header -->
        <?= view('ACOMPONENTS/adminheader'); ?>

    <!-- App Container -->
    <div class="app-container">
        <!-- Sidebar -->
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <h1 class="text-4xl font-bold text-white mb-8">Create New File</h1>
            <div class="form-container">
                <form action="/folders/storeFile" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-400 mb-2">Title:</label>
                        <input type="text" name="title" id="title" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-400 mb-2">Description:</label>
                        <textarea name="description" id="description" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="block text-gray-400 mb-2">Files (Images/Videos):</label>
                        <input type="file" name="files[]" id="file" accept="image/*,video/*" multiple class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required>
                    </div>

                    <div class="mb-4">
                        <label for="main_folder" class="block text-gray-400 mb-2">Main Folder:</label>
                        <select name="main_folder" id="main_folder" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" onchange="updateSubFolders(this.value)" required>
                            <option value="">Select Main Folder</option>
                            <?php foreach ($mainFolders as $mainFolder): ?>
                                <option value="<?= $mainFolder['main_folder_id'] ?>"><?= $mainFolder['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="sub_folder" class="block text-gray-400 mb-2">Sub Folder:</label>
                        <select name="sub_folder" id="sub_folder" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required>
                            <option value="">Select Sub Folder</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white py-3 px-4 rounded hover:bg-blue-700">Save Files</button>
                </form>
            </div>
        </div>
    </div>

    <?= view('hf/footer'); ?>

    <script>
        const mainFolders = <?= json_encode($mainFolders) ?>;

        function updateSubFolders(mainFolderId) {
            const subFolderSelect = document.getElementById('sub_folder');
            subFolderSelect.innerHTML = '<option value="">Select Sub Folder</option>';

            const selectedMainFolder = mainFolders.find(folder => folder.main_folder_id == mainFolderId);
            if (selectedMainFolder) {
                selectedMainFolder.subfolders.forEach(subFolder => {
                    const option = document.createElement('option');
                    option.value = subFolder.sub_folder_id;
                    option.textContent = subFolder.name;
                    subFolderSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>
