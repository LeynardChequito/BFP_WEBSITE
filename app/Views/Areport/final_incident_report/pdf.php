<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report - <?= $report['final_report_id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
        <div class="text-center mb-10">
            <img src="https://bfpcalapancity.online/bfpcalapancity/public/design/logo.png" alt="BFP Logo"
                class="w-32 h-auto mx-auto mb-4"> <!-- Replace with the actual path to your logo -->
            <h1 class="text-3xl font-semibold text-gray-800">Bureau of Fire Protection</h1>
            <h2 class="text-xl text-gray-600 mt-2">Incident Report</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 mb-8">
                <tbody>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Rescuer Name</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['rescuer_name'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Report Date</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['report_date'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Start Time</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['start_time'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">End Time</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['end_time'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Address</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['address'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Cause of Fire</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['cause_of_fire'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Property Damage Cost</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= number_format((float)$report['property_damage_cost_estimate'], 2) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Number of Injuries</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['number_of_injuries'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Additional Information</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['additional_information'] ?></td>
                    </tr>
                    <tr>
                        <th class="bg-blue-600 text-white uppercase p-4 border border-gray-300 text-left">Photo</th>
                        <td class="bg-gray-100 p-4 text-gray-800 border border-gray-300"><?= $report['photo'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between mt-8">
            <a href="<?= site_url('rescuer/final-incident-report') ?>" class="btn-back px-5 py-3 bg-blue-500 text-white rounded hover:bg-blue-600">Back To Dashboard</a>
            <button onclick="window.print()" class="btn-print px-5 py-3 bg-green-500 text-white rounded hover:bg-green-600">Print Report</button>
        </div>

        <div class="footer mt-12 text-center text-gray-500 text-sm">
            <p>Report generated on <?= date('Y-m-d H:i:s') ?></p>
            <p>&copy; <?= date('Y') ?> Bureau of Fire Protection. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>
