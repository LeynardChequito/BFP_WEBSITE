<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Folder Files</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .app-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            min-width: 250px; /* Adjust width as needed */
            background-color: #dc3545; /* Sidebar color */
        }

        .main-content {
            flex: 1;
            padding: 16px;
        }

        .header {
            width: 100%;
            background-color: #343a40; /* Header color */
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="header">
        <?= view('ACOMPONENTS/adminheader'); ?>
    </div>

    <div class="app-container">
        <div class="sidebar">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>
        </div>

        <div class="main-content">
            <h1 class="text-4xl font-bold text-white mb-8">Manage Folder Files</h1>
            <div class="container bg-gray-800 p-6 rounded-lg shadow-lg">
                <button class="bg-blue-500 text-white py-2 px-4 rounded mb-4 hover:bg-blue-700" onclick="window.location.href='/folders/createFolder'">Create Main Folder and Subfolder</button>
                <button class="bg-blue-500 text-white py-2 px-4 rounded mb-4 hover:bg-blue-700" onclick="window.location.href='/folders/createFile'">Create File</button>

                <?php foreach ($mainFolders as $mainFolder): ?>
                    <div class="folder-section my-6">
                        <h3 class="folder-header text-lg text-blue-400 font-medium cursor-pointer flex items-center" onclick="toggleTable('table_<?= $mainFolder['main_folder_id'] ?>')">
                            <?= $mainFolder['name'] ?> Folder
                            <button class="ml-3 bg-yellow-500 text-white text-sm py-1 px-2 rounded hover:bg-yellow-600" onclick="event.stopPropagation(); openEditFolderModal('main', <?= $mainFolder['main_folder_id'] ?>, '<?= $mainFolder['name'] ?>')">Edit</button>
                            <button class="ml-1 bg-red-500 text-white text-sm py-1 px-2 rounded hover:bg-red-700" onclick="event.stopPropagation(); deleteFolder('main', <?= $mainFolder['main_folder_id'] ?>)">Delete</button>
                        </h3>

                        <table id="table_<?= $mainFolder['main_folder_id'] ?>" class="w-full border-collapse mt-4 hidden">
                            <thead>
                                <tr class="bg-gray-700 text-gray-300">
                                    <th class="p-3">Subfolder ID</th>
                                    <th class="p-3">Title</th>
                                    <th class="p-3">Description</th>
                                    <th class="p-3">Preview</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                                    <tr>
                                        <td colspan="5" class="text-blue-400 font-semibold p-4">
                                            <?= $subFolder['name'] ?> Subfolder
                                            <button class="ml-2 bg-yellow-500 text-white text-sm py-1 px-2 rounded hover:bg-yellow-600" onclick="openEditFolderModal('sub', <?= $subFolder['sub_folder_id'] ?>, '<?= $subFolder['name'] ?>')">Edit</button>
                                            <button class="ml-1 bg-red-500 text-white text-sm py-1 px-2 rounded hover:bg-red-700" onclick="deleteFolder('sub', <?= $subFolder['sub_folder_id'] ?>)">Delete</button>
                                        </td>
                                    </tr>
                                    <?php foreach ($subFolder['files'] as $file): ?>
                                        <tr class="bg-gray-800">
                                            <td class="p-3"><?= $file['file_id'] ?></td>
                                            <td class="p-3"><?= $file['title'] ?></td>
                                            <td class="p-3"><?= $file['description'] ?></td>
                                            <td class="p-3">
                                                <?php
                                                    $filePaths = json_decode($file['file_path'], true);
                                                    foreach ($filePaths as $path) {
                                                        $fileExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                                        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])):
                                                ?>
                                                        <img src="<?= base_url($path) ?>" alt="Image Preview" class="w-20 h-20 object-cover rounded mb-2">
                                                <?php else: ?>
                                                        <span>File format not supported for preview</span>
                                                <?php endif; } ?>
                                            </td>

                                            <td class="actions p-3 space-x-2">
                                                <?php
$fileId = $file['file_id'];
$title = htmlspecialchars($file['title'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($file['description'], ENT_QUOTES, 'UTF-8');
?>
                                                <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600" onclick="editFile(<?= $fileId ?>, '<?= $title ?>', '<?= $description ?>')">Edit</button>
                                                <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-700" onclick="deleteFile(<?= $file['file_id'] ?>)">Delete</button>
                                                <button class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600" onclick="previewFile(<?= $file['file_id'] ?>)">Preview</button>
                                                <button class="bg-purple-500 text-white py-1 px-3 rounded hover:bg-purple-600" onclick="exportFile('pdf', <?= $file['file_id'] ?>)">Export PDF</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?= view('hf/footer'); ?>
    <script>
        function toggleTable(tableId) {
            const table = document.getElementById(tableId);
            table.classList.toggle('hidden');
        }

        function previewFile(fileId) {
            window.location.href = `/folders/previewFile/${fileId}`;
        }

        function exportFile(format, fileId) {
            if (format === 'pdf') {
                window.location.href = `/folders/exportFilePDF/${fileId}`;
            }
        }

        function openEditFolderModal(type, id, name) {
            document.getElementById('folderName').value = name;
            document.getElementById('folderType').value = type;
            document.getElementById('folderId').value = id;
            document.getElementById('editFolderModal').classList.remove('hidden');
            document.getElementById('editFolderModal').classList.add('flex');
        }

        function closeEditFolderModal() {
            document.getElementById('editFolderModal').classList.add('hidden');
            document.getElementById('editFolderModal').classList.remove('flex');
        }

          function openEditFileModal(fileId, title, description) {
    // Check if elements exist before setting values to avoid the null error
    const titleElement = document.getElementById('fileTitle');
    const descriptionElement = document.getElementById('fileDescription');
    const fileIdElement = document.getElementById('fileId');

    if (titleElement && descriptionElement && fileIdElement) {
        // Populate the form with current file information
        titleElement.value = title;
        descriptionElement.value = description;
        fileIdElement.value = fileId;

        // Show the modal
        document.getElementById('editFileModal').classList.remove('hidden');
        document.getElementById('editFileModal').classList.add('flex');
    } else {
        console.error('Edit file elements not found.');
    }
}

function closeEditFileModal() {
    // Hide the modal
    document.getElementById('editFileModal').classList.add('hidden');
    document.getElementById('editFileModal').classList.remove('flex');
}

        function editFile(fileId, title, description) {
            openEditFileModal(fileId, title, description);
        }

        document.getElementById('editFileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const fileId = document.getElementById('fileId').value;
        const title = document.getElementById('fileTitle').value;
        const description = document.getElementById('fileDescription').value;
        const files = document.getElementById('fileUpload').files;

        // Prepare form data to send with the request
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('fileId', fileId);

        // Append files to formData
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        // Send the data to the server
        fetch(`/folders/updateFile/${fileId}`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                console.error(`Error: ${response.status} ${response.statusText}`);
                throw new Error('Failed to update file');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Reload page to reflect changes
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error updating file:', error);
            alert('An error occurred while updating the file. Please try again.');
        });
    });
        function deleteFolder(type, id) {
            if (confirm(`Are you sure you want to delete this ${type === 'main' ? 'main folder' : 'subfolder'}?`)) {
                const url = type === 'main'
                    ? `/folders/deleteMainFolder/${id}`
                    : `/folders/deleteSubFolder/${id}`;

                fetch(url, { method: 'DELETE' })
                    .then(response => {
                        if (!response.ok) {
                            console.error(`Error: ${response.status} ${response.statusText}`);
                            throw new Error('Failed to delete folder');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting folder:', error);
                        alert('An error occurred while deleting the folder. Please try again.');
                    });
            }
        }

        function deleteFile(fileId) {
            if (confirm("Are you sure you want to delete this file?")) {
                fetch(`/folders/deleteFile/${fileId}`, { method: 'DELETE' })
                    .then(response => {
                        if (!response.ok) {
                            console.error(`Error: ${response.status} ${response.statusText}`);
                            throw new Error('Failed to delete file');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting file:', error);
                        alert('An error occurred while deleting the file. Please try again.');
                    });
            }
        }
    </script>

</body>

</html>
