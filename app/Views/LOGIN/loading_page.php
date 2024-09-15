<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading - Bureau of Fire Protection</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            flex-direction: column;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 150px;
            height: auto;
        }

        .bfp-name {
            font-size: 24px;
            color: #d72631; /* Color for Bureau of Fire Protection */
            margin-top: 10px;
            font-weight: bold;
        }

        .spinner {
            border: 16px solid #f3f4f6;
            border-top: 16px solid #d72631;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin-top: 30px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
            letter-spacing: 1px;
        }
    </style>
    <script>
        // Redirect to the login route after 3 seconds
        setTimeout(function() {
            window.location.href = "/login";
        }, 3000);
    </script>
</head>
<body>

<div class="logo-container">
    <img src="bfpcalapancity/public/design/logo.png" alt="Bureau of Fire Protection Logo">
    <div class="bfp-name">Bureau of Fire Protection</div>
</div>

<div class="spinner"></div>

<div class="loading-text">Loading... Please wait</div>

</body>
</html>
