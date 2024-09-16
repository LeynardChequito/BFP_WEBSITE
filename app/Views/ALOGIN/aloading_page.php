<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau of Fire Protection</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(#1a0000, #4d0000,#990000, #cc0000,#ff1a1a, #ff6666, #d8a7ce);
            font-family: Arial, Helvetica, sans-serif,
            Papyrus, fantasy;
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
            font-size: 50px;
            color: white;
            margin-top: 10px;
            font-weight:bold;
        }
        .bfp-sub {
            font-size: 30px;
            color: white;
            margin-top: 10px;
            font-weight:bold;
        }
        .spinner {
            border: 5px solid #f3f4f6;
            border-top: 5px solid #d72631;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin-top: 150px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            color: #595959;
            letter-spacing: 1px;
        }
    </style>
    <script>
        // Redirect to the login route after 3 seconds
        setTimeout(function() {
            window.location.href = "/admin-login";
        }, 5000);
    </script>
</head>
<body>

<div class="logo-container">
    <img src="bfpcalapancity/public/design/logo.png" alt="Bureau of Fire Protection Logo">
    <div class="bfp-name">BUREAU OF FIRE PROTECTION</div>
    <div class="bfp-sub">Admin Portal</div>
</div>

<div class="spinner"></div>

<div class="loading-text">Loading... Please wait</div>

</body>
</html>
