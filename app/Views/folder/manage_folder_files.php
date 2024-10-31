<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Folder Files</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Dark theme styling */
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Container styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 30px;
            min-height: calc(100vh - 200px);
            box-sizing: border-box;
        }

        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.25);
        }

        .main-content h1 {
            font-size: 2.5rem;
            color: #ffffff;
            margin-bottom: 20px;
        }

        /* Button styling */
        .btn-custom {
            background-color: #0070f3;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-custom:hover {
            background-color: #005bb5;
        }

        /* Folder header styling */
        .folder-header {
            font-size: 1.2rem;
            color: #58a6ff;
            cursor: pointer;
            font-weight: 500;
            margin: 20px 0;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #2c2c2c;
        }
        th {
            background-color: #2d2d2d;
            color: #b0b0b0;
        }

        /* Action buttons with better visibility */
        .actions button {
            margin: 2px;
            padding: 6px 10px;
            font-size: 0.875rem;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .actions .btn-edit { background-color: #5bc0de; }
        .actions .btn-toggle { background-color: #5cb85c; }
        .actions .btn-preview { background-color: #f0ad4e; }
        .actions .btn-export { background-color: #d9534f; }
        .actions .btn-delete { background-color: #d9534f; }

        /* Media preview styling */
        .thumbnail, .video-preview {
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
            border: 1px solid #333;
            margin: 5px 0;
        }
    </style>
</head>
<body>

<?= view('ACOMPONENTS/adminheader'); ?>
<?= view('ACOMPONENTS/amanagesidebar'); ?>

<div class="main-content">
    <h1>Manage Folder Files</h1>
    <div class="container">
        <button class="btn btn-custom" onclick="window.location.href='/folders/createFolder'">Create Main Folder and Subfolder</button>
        <button class="btn btn-custom" onclick="window.location.href='/folders/createFile'">Create File</button>

        <?php foreach ($mainFolders as $mainFolder): ?>
            <div class="folder-section">
                <div class="folder-header" onclick="toggleTable('table_<?= $mainFolder['main_folder_id'] ?>')">
                    <?= $mainFolder['name'] ?> Folder
                </div>

                <table id="table_<?= $mainFolder['main_folder_id'] ?>" class="table" style="display:none;">
                    <thead>
                        <tr>
                            <th>File ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Preview</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mainFolder['subfolders'] as $subFolder): ?>
                            <?php foreach ($subFolder['files'] as $file): ?>
                                <tr>
                                    <td><?= $file['file_id'] ?></td>
                                    <td><?= $file['title'] ?></td>
                                    <td><?= $file['description'] ?></td>
                                    <td>
                                        <?php
                                            // Ensure the file path points to the correct directory in public/gallery
                                            $filePath = base_url("bfpcalapancity/public/gallery/{$mainFolder['name']}/{$subFolder['name']}/" . basename($file['file_path']));
                                            $fileExtension = strtolower(pathinfo($file['file_path'], PATHINFO_EXTENSION));

                                            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])):
                                        ?>
                                            <img src="<?= $filePath ?>" alt="Image Preview" class="thumbnail">
                                        <?php elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])): ?>
                                            <video controls class="video-preview">
                                                <source src="<?= $filePath ?>" type="video/<?= $fileExtension ?>">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php else: ?>
                                            <span>File format not supported for preview</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick="editFile(<?= $file['file_id'] ?>)">Edit</button>
                                        <button class="btn-toggle" onclick="toggleVisibility(<?= $file['file_id'] ?>)">
                                            <?= $file['is_visible'] ? 'Hide' : 'Show' ?>
                                        </button>
                                        <button class="btn-preview" onclick="previewFile('<?= $filePath ?>')">Preview PDF</button>
                                        <button class="btn-export" onclick="exportToPDF(<?= $file['file_id'] ?>)">Export PDF</button>
                                        <button class="btn-delete" onclick="deleteFile(<?= $file['file_id'] ?>)">Delete</button>
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

<?= view('hf/footer'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleTable(tableId) {
        const table = document.getElementById(tableId);
        table.style.display = (table.style.display === 'none') ? 'table' : 'none';
    }

    function editFile(fileId) {
        window.location.href = `/folders/editFile/${fileId}`;
    }

    function toggleVisibility(fileId) {
        fetch(`/folders/toggleVisibility/${fileId}`)
            .then(response => location.reload());
    }

    function previewFile(filePath) {
        const win = window.open(filePath, '_blank');
        win.focus();
    }

    function exportToPDF(fileId) {
        window.location.href = `/folders/exportFilePDF/${fileId}`;
    }

    function deleteFile(fileId) {
        if (confirm("Are you sure you want to delete this file?")) {
            fetch(`/folders/deleteFile/${fileId}`, { method: 'DELETE' })
                .then(response => location.reload());
        }
    }
</script>

</body>
</html>
