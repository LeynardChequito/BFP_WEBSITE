<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Incident Report No.<?= $report['final_report_id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header h2 {
            font-size: 18px;
            color: #555;
            margin: 0;
        }

        .content {
            width: 90%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        td:first-child {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
<div class="header">
    <img src="<?= $base64Logo ?>" alt="BFP Logo">
    <h1>Bureau of Fire Protection</h1>
    <h2>Incident Report</h2>
</div>


    <div class="content">
        <table>
            <tr>
                <td>Rescuer Name</td>
                <td><?= $report['rescuer_name'] ?></td>
            </tr>
            <tr>
                <td>Report Date</td>
                <td><?= $report['report_date'] ?></td>
            </tr>
            <tr>
                <td>Start Time</td>
                <td><?= $report['start_time'] ?></td>
            </tr>
            <tr>
                <td>End Time</td>
                <td><?= $report['end_time'] ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $report['address'] ?></td>
            </tr>
            <tr>
                <td>Cause of Fire</td>
                <td><?= $report['cause_of_fire'] ?></td>
            </tr>
            <tr>
                <td>Estimated Damage Cost</td>
                <td><?= number_format((float)$report['property_damage_cost_estimate'], 2) ?></td>
            </tr>
            <tr>
                <td>Number of Injuries</td>
                <td><?= $report['number_of_injuries'] ?></td>
            </tr>
            <tr>
                <td>Additional Information</td>
                <td><?= $report['additional_information'] ?></td>
            </tr>
            <tr>
                <td>Photo</td>
                <td><?= $report['photo'] ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Report generated on <?= date('Y-m-d H:i:s') ?></p>
        <p>&copy; <?= date('Y') ?> Bureau of Fire Protection. All Rights Reserved.</p>
    </div>
</body>

</html>
