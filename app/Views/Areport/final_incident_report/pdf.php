<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incident Report - <?= $report['final_report_id'] ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #2C3E50;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #BDC3C7;
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #2980B9;
            color: #ffffff;
            text-transform: uppercase;
        }
        td {
            background-color: #ECF0F1;
            color: #34495E;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #7F8C8D;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 120px; /* Adjust size as needed */
            height: auto;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-back {
            background-color: #3498DB;
            color: white;
        }
        .btn-print {
            background-color: #28A745;
            color: white;
        }
        .btn-save {
            background-color: #FFC107;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://bfpcalapancity.online/bfpcalapancity/public/design/logo.png" alt="BFP Logo"> <!-- Replace with the actual path to your logo -->
            <h1>Bureau of Fire Protection</h1>
            <h2>Incident Report</h2>
        </div>
        <table>
            <tr>
                <th>Community Report ID</th>
                <td><?= $report['communityreport_id'] ?></td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td><?= $report['fullName'] ?></td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><?= $report['latitude'] ?></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><?= $report['longitude'] ?></td>
            </tr>
            <tr>
                <th>File Proof</th>
                <td><?= $report['fileproof'] ?></td>
            </tr>
            <tr>
                <th>Timestamp</th>
                <td><?= $report['timestamp'] ?></td>
            </tr>
            <tr>
                <th>Rescuer Name</th>
                <td><?= $report['rescuer_name'] ?></td>
            </tr>
            <tr>
                <th>Report Date</th>
                <td><?= $report['report_date'] ?></td>
            </tr>
            <tr>
                <th>Start Time</th>
                <td><?= $report['start_time'] ?></td>
            </tr>
            <tr>
                <th>End Time</th>
                <td><?= $report['end_time'] ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= $report['address'] ?></td>
            </tr>
            <tr>
                <th>Cause of Fire</th>
                <td><?= $report['cause_of_fire'] ?></td>
            </tr>
            <tr>
                <th>Property Damage Cost</th>
                <td><?= number_format($report['property_damage_cost'], 2) ?></td>
            </tr>
            <tr>
                <th>Number of Injuries</th>
                <td><?= $report['number_of_injuries'] ?></td>
            </tr>
            <tr>
                <th>Additional Information</th>
                <td><?= $report['additional_information'] ?></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td><?= $report['photo'] ?></td>
            </tr>
        </table>
        <div class="button-container">
            <a href="<?= site_url('rescuer/final-incident-report') ?>">Back To Dashboard</a>
            <button class="btn btn-print" onclick="window.print()">Print Report</button>
            <button class="btn btn-save" onclick="saveReport()">Save Report</button>
        </div>
        <div class="footer">
            <p>Report generated on <?= date('Y-m-d H:i:s') ?></p>
            <p>&copy; <?= date('Y') ?> Bureau of Fire Protection. All Rights Reserved.</p>
        </div>
    </div>

    <script>
        function saveReport() {
            // Implement the saving functionality as needed.
            alert("Saving functionality is not implemented yet.");
        }
    </script>
</body>
</html>
