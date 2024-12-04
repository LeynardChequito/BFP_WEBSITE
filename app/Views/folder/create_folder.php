<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Main Folder and Subfolder</title>
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

        <?= view('ACOMPONENTS/adminheader'); ?>
    <!-- App Container -->
    <div class="app-container">
        <!-- Sidebar -->
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <h1 class="text-4xl font-bold text-black mb-8">Create Main Folder and Subfolder</h1>
            <div class="form-container">
                <form action="/folders/storeFolder" method="post">
                    <div class="mb-4">
                        <label for="main_folder_name" class="block text-gray-400 mb-2">Main Folder Name:</label>
                        <input type="text" name="main_folder_name" id="main_folder_name" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required>
                    </div>
                    <div class="mb-4">
                        <label for="sub_folder_name" class="block text-gray-400 mb-2">Subfolder Name:</label>
                        <input type="text" name="sub_folder_name" id="sub_folder_name" class="w-full p-3 bg-gray-800 text-white rounded border border-gray-600" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 px-4 rounded hover:bg-blue-700">Create Folder and Subfolder</button>
                </form>
            </div>
        </div>
    </div>

    <?= view('hf/footer'); ?>
</body>
</html>
