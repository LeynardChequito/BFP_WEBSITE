<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Popper.js and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            background-color: #EF3340;
            /* Red color for table header */
            color: #fff;
        }

        th,
        td {
            text-align: center;
        }

        td {
            background-color: #ECF0F1;
            color: #34495E;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        /* Ensure visibility and proper positioning of dropdowns */
        .dropdown-menu {
            position: absolute;
            z-index: 9999;
        }

        .dropdown-menu.show {
            display: block !important;
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
                    <div class="row align-items-center mb-4">
                        <div class="col-md-1 text-right">
                            <a href="<?= site_url('rescuer/final-incident-report/create') ?>" class="btn btn-primary mb-3">Add New Report</a>
                        </div>
                        <div class="col-md-10 text-center" style="font-size: 50px;">
                            <h1>Final Incident Report</h1>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
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
                                    <td><?= $report['rescuer_name'] ?></td>
                                    <td><?= $report['report_date'] ?></td>
                                    <td><?= $report['start_time'] ?></td>
                                    <td><?= $report['end_time'] ?></td>
                                    <td><?= $report['address'] ?></td>
                                    <td><?= $report['cause_of_fire'] ?></td>
                                    <td><?= number_format((float)$report['property_damage_cost_estimate'], 2) ?></td>
                                    <td><?= $report['number_of_injuries'] ?></td>
                                    <td><?= $report['additional_information'] ?></td>
                                    <td><?= $report['photo'] ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/edit/' . $report['final_report_id']) ?>">Edit</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/preview/pdf/' . $report['final_report_id']) ?>">Preview PDF</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/preview/excel/' . $report['final_report_id']) ?>">Preview Excel</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/export/pdf/' . $report['final_report_id']) ?>">Export PDF</a>
                                                <a class="dropdown-item" href="<?= site_url('rescuer/final-incident-report/export/excel/' . $report['final_report_id']) ?>">Export Excel</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete(<?= $report['final_report_id'] ?>)">Delete</a>
                                            </div>
                                        </div>

                                        <form id="deleteForm_<?= $report['final_report_id'] ?>" action="<?= site_url('rescuer/final-incident-report/delete/' . $report['final_report_id']) ?>" method="POST" style="display: none;">
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= view('hf/footer'); ?>

    <script>
        $(document).ready(function () {
            // Close other dropdowns before opening the current one
            $('.dropdown-toggle').on('click', function (event) {
                event.preventDefault();
                $('.dropdown-menu').not($(this).next('.dropdown-menu')).removeClass('show').hide();
                $(this).next('.dropdown-menu').toggleClass('show').toggle();
            });

            // Close dropdown when clicking outside
            $(document).on('click', function (event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show').hide();
                }
            });
        });

        function confirmDelete(reportId) {
            if (confirm('Are you sure you want to delete this report?')) {
                document.getElementById('deleteForm_' + reportId).submit();
            }
        }
    </script>
</body>

</html>
