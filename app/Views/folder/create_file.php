<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create File</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Dark theme styling */
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 30px;
            min-height: calc(100vh - 100px);
            box-sizing: border-box;
        }

        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.25);
        }

        h1 {
            font-size: 2rem;
            color: #ffffff;
            margin-bottom: 20px;
        }

        label {
            color: #b0b0b0;
        }

        .form-control, .form-select {
            background-color: #2c2c2c;
            color: #e0e0e0;
            border: none;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .btn-custom {
            background-color: #0070f3;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.2s;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>

<?= view('ACOMPONENTS/adminheader'); ?>
<?= view('ACOMPONENTS/amanagesidebar'); ?>

<div class="main-content">
    <div class="container">
        <h1>Create New File</h1>

        <form action="/folders/storeFile" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control" required>

            <label for="description">Description:</label>
            <textarea name="description" class="form-control" required></textarea>

            <label for="file">Files (Images/Videos):</label>
            <input type="file" name="files[]" accept="image/*,video/*" multiple class="form-control" required>

            <label for="main_folder">Main Folder:</label>
            <select name="main_folder" class="form-select" onchange="updateSubFolders(this.value)" required>
                <option value="">Select Main Folder</option>
                <?php foreach ($mainFolders as $mainFolder): ?>
                    <option value="<?= $mainFolder['main_folder_id'] ?>"><?= $mainFolder['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sub_folder">Sub Folder:</label>
            <select name="sub_folder" id="sub_folder" class="form-select" required>
                <option value="">Select Sub Folder</option>
            </select>

            <button type="submit" class="btn btn-custom">Save Files</button>
        </form>
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
