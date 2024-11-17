<?php
// Define the sidebar items
$sidebarItems = [
    ['url' => '/admin-home', 'icon' => 'fas fa-home', 'text' => 'Home'],
    ['url' => 'carousel', 'icon' => 'fas fa-images', 'text' => 'Manage Carousel'],
    ['url' => 'rescuer/final-incident-report', 'icon' => 'fas fa-file-alt', 'text' => 'Manage Final Report'],
    ['url' => 'graph', 'icon' => 'fas fa-chart-pie', 'text' => 'Dashboard'],
    ['url' => '/folders/manage', 'icon' => 'fas fa-folder', 'text' => 'Manage Folders'], // New entry for Manage Folders
];

// Helper function to check if the current page matches the menu item
if (!function_exists('isActive')) {
    function isActive($url) {
        return strpos(current_url(), $url) !== false ? 'active' : '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP Admin Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Sidebar Styles */
        .sidebar {
            background-color: #EF3340; /* Red color */
            height: 100vh;
            width: 240px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .sidebar a {
            color: #fff;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            transition: background-color 0.2s;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #D12D34; /* Darker shade of red */
        }

        .sidebar .active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            padding: 0 1rem;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
        }

        /* Content Alignment */
        .main-container {
            display: flex;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin-top: 60px; /* Space for header */
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
            }

            .content {
                margin-top: 80px; /* Space for header on small screens */
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar text-white">
            <div class="p-4">
                <h2 class="font-bold">BFP Admin</h2>
            </div>
            <nav class="flex-1 overflow-y-auto">
                <ul class="mt-4 space-y-2">
                    <?php foreach ($sidebarItems as $item): ?>
                        <li>
                            <a href="<?= site_url($item['url']) ?>" class="flex items-center px-4 py-2 hover:bg-red-700 <?= isActive($item['url']) ?>">
                                <i class="<?= $item['icon'] ?> w-5 h-5 mr-2"></i>
                                <span><?= $item['text'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= site_url('/admin-registration') ?>" class="block w-full text-center bg-red-700 hover:bg-red-800 py-2 rounded mb-2">
                    Create an Account
                </a>
                <a href="<?= site_url('/admin-logout') ?>" class="block w-full text-center bg-red-700 hover:bg-red-800 py-2 rounded">
                    Logout
                </a>
            </div>
        </aside>
</div>
</body>
</html>
