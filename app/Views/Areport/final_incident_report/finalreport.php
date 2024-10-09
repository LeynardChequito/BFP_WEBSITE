<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Full jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js and Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            color: #2C3E50;
        }

        .container {
            margin-top: 20px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .btn {
            margin-right: 5px;
        }

        table {
            margin-top: 20px;
        }

        th {
            background-color: #EF3340; /* Red color for table header */
            color: #fff;
        }

        th, td {
            text-align: center; /* Center-align the text in table cells */
        }

        td {
            background-color: #ECF0F1;
            color: #34495E;
        }

        tbody tr:hover {
            background-color: #f1f1f1; /* Light grey on hover */
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?= view('ACOMPONENTS/adminheader'); ?>
    <div class="container-fluid">
        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>
            <div class="col-md-10">
                <div class="container">
                    <h2>Final Incident Reports</h2>
                    <a href="<?= site_url('rescuer/final-incident-report/create') ?>" class="btn btn-primary mb-3">Add New Report</a>
                    <div class="table-responsive"> <!-- Make the table responsive -->
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Community Report ID</th>
                                    <th>Full Name</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>File Proof</th>
                                    <th>Timestamp</th>
                                    <th>Rescuer Name</th>
                                    <th>Report Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Address</th>
                                    <th>Cause of Fire</th>
                                    <th>Property Damage Cost</th>
                                    <th>Number of Injuries</th>
                                    <th>Additional Information</th>
                                    <th>Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= $report['communityreport_id'] ?></td>
                                    <td><?= $report['fullName'] ?></td>
                                    <td><?= $report['latitude'] ?></td>
                                    <td><?= $report['longitude'] ?></td>
                                    <td><?= $report['fileproof'] ?></td>
                                    <td><?= $report['timestamp'] ?></td>
                                    <td><?= $report['rescuer_name'] ?></td>
                                    <td><?= $report['report_date'] ?></td>
                                    <td><?= $report['start_time'] ?></td>
                                    <td><?= $report['end_time'] ?></td>
                                    <td><?= $report['address'] ?></td>
                                    <td><?= $report['cause_of_fire'] ?></td>
                                    <td><?= number_format($report['property_damage_cost'], 2) ?></td>
                                    <td><?= $report['number_of_injuries'] ?></td>
                                    <td><?= $report['additional_information'] ?></td>
                                    <td><?= $report['photo'] ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/edit/' . $report['final_report_id']) ?>">Edit</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/preview/pdf/' . $report['final_report_id']) ?>" target="_blank">Preview PDF</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/preview/excel/' . $report['final_report_id']) ?>" target="_blank">Preview Excel</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/export/pdf/' . $report['final_report_id']) ?>">Export PDF</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/export/excel/' . $report['final_report_id']) ?>">Export Excel</a>
                                                <form action="<?= site_url('rescuer/final-incident-report/delete/' . $report['final_report_id']) ?>" method="POST" style="display:inline;">
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this report?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div> <!-- End of table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <?= view('hf/footer'); ?>
</body>
</html>
