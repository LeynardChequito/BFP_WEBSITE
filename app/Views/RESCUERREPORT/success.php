<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
        }
        h2 {
            color: #28a745;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            font-size: 16px;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Report Submitted Successfully!</h2>
        <p>Thank you for your submission. Your report has been recorded.</p>
        <a href="<?= base_url('admin-home') ?>">Back to Admin Home</a>
        <a href="<?= base_url('rescuer-report/form') ?>">Submit Another Report</a>
    </div>
</body>
</html>
