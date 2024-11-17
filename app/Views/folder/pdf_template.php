<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($file['title']) ?></title>
    <style>
        /* Base styling for the document */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #fff;
            margin: 40px;
        }

        /* Header and Footer */
        .header, .footer {
            font-size: 12px;
            color: #666;
            width: 100%;
            position: fixed;
            left: 0;
            right: 0;
            text-align: center;
        }

        /* Header styling */
        .header {
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 10px 40px;
        }

        .header-left {
            text-align: left;
        }

        .header-right {
            text-align: right;
        }

        /* Footer styling */
        .footer {
            bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #ddd;
            padding: 10px 40px;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        /* Container for main content */
        .container {
            max-width: 800px;
            margin: 60px auto 80px; /* Offset for header and footer */
            padding: 20px;
            background-color: #f4f4f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Title section styling */
        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Grid layout for images */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }

        .grid-item {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .grid-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Page number placeholder */
        .footer-right:after {
            content: "Page " counter(page) " of " counter(page);
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="header-left"><?= date('n/j/y, g:i A', time() + (8 * 3600)) ?></div>
        <div class="header-right"><?= esc($file['title']) ?></div>
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <!-- Title and Description Section -->
        <h1><?= esc($file['title']) ?></h1>
        <p><?= esc($file['description']) ?></p>

        <!-- Image Grid Layout -->
        <div class="grid">
            <?php foreach ($filePaths as $path): ?>
                <?php
                    $fileExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $fileUrl = base_url($path);
                ?>
                <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <div class="grid-item">
                        <img src="<?= $fileUrl ?>" alt="<?= esc($file['title']) ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <div class="footer-left">https://bfpcalapancity.online/folders/previewFile/<?= $file['file_id'] ?></div>
        <div class="footer-right"></div> <!-- Placeholder for page number -->
    </div>

</body>
</html>
