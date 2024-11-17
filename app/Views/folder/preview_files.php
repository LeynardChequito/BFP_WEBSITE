<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($file['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Hide elements with the class 'no-print' when printing or exporting to PDF */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans flex flex-col items-center justify-center min-h-screen p-6 space-y-4">

    <!-- Action Buttons with 'no-print' class to hide them during print/PDF export -->
    <!-- Action Buttons -->
<div class="flex space-x-4 no-print">
    <button onclick="history.back()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-500">Back</button>
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500">Print Document</button>
    <a href="<?= base_url('/folders/exportAsPDF/' . $file['file_id']) ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500">Save as PDF</a>
</div>


    <!-- File Preview Content -->
    <div class="max-w-3xl w-full p-6 bg-gray-800 rounded-lg shadow-lg">
        <!-- Title Section -->
        <h1 class="text-3xl font-bold text-blue-400 mb-4"><?= esc($file['title']) ?></h1>
        <p class="text-lg mb-6"><?= esc($file['description']) ?></p>

        <!-- File Previews in a Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <?php
            $filePaths = json_decode($file['file_path'], true);
            foreach ($filePaths as $path):
                $fileExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $fileUrl = base_url($path);
            ?>
                <!-- Image Preview -->
                <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <div class="flex justify-center items-center">
                        <img src="<?= $fileUrl ?>" alt="<?= esc($file['title']) ?>" class="rounded-lg shadow-lg w-full h-32 object-cover">
                    </div>
                
                <!-- Video Preview -->
                <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])): ?>
                    <div class="flex justify-center items-center">
                        <video controls class="rounded-lg shadow-lg w-full h-32 object-cover">
                            <source src="<?= $fileUrl ?>" type="video/<?= $fileExtension ?>">
                        </video>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- JavaScript for PDF Export -->
    <script>
        function saveAsPDF() {
            const element = document.querySelector('.max-w-3xl'); // Only select the preview content
            const options = {
                margin:       0.5,
                filename:     '<?= addslashes($file["title"]) ?>.pdf', // Use file title as filename
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'Letter', orientation: 'portrait' }
            };

            html2pdf().from(element).set(options).save();
        }
    </script>

    <!-- html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</body>
</html>