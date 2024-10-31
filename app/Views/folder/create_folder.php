<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Main Folder and Subfolder</title>
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

        .form-control {
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
        <h1>Create Main Folder and Subfolder</h1>

        <form action="/folders/storeFolder" method="post">
            <label for="main_folder_name">Main Folder Name:</label>
            <input type="text" name="main_folder_name" class="form-control" required>

            <label for="sub_folder_name">Subfolder Name:</label>
            <input type="text" name="sub_folder_name" class="form-control" required>

            <button type="submit" class="btn btn-custom">Create Folder and Subfolder</button>
        </form>
    </div>
</div>

<?= view('hf/footer'); ?>

</body>
</html>
