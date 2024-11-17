<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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

        th,
        td {
            text-align: center;
            /* Center-align the text in table cells */
        }

        td {
            background-color: #ECF0F1;
            color: #34495E;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            /* Light grey on hover */
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        /* Style for the dropdown menu to ensure visibility */
        .dropdown-menu {
            position: fixed !important;
            z-index: 9999;
            /* Make sure the dropdown appears above all other content */
        }
    </style>

    <script>
        $(document).ready(function () {
            $('.dropdown-toggle').on('click', function (event) {
                event.preventDefault(); // Prevent default action
                
                const dropdownMenu = $(this).next('.dropdown-menu');
                
                // Calculate the dropdown position relative to the button
                const buttonOffset = $(this).offset();
                const dropdownHeight = dropdownMenu.outerHeight();
                const dropdownWidth = dropdownMenu.outerWidth();
                const windowWidth = $(window).width();
                const windowHeight = $(window).height();

                // Position the dropdown menu directly under the button by default
                let dropdownTop = buttonOffset.top + $(this).outerHeight();
                let dropdownLeft = buttonOffset.left;

                // Adjust position if dropdown overflows to the right
                if (dropdownLeft + dropdownWidth > windowWidth) {
                    dropdownLeft = windowWidth - dropdownWidth - 20; // Ensure some margin from right side
                }

                // Adjust position if dropdown overflows to the bottom
                if (dropdownTop + dropdownHeight > windowHeight) {
                    dropdownTop = buttonOffset.top - dropdownHeight; // Display it above the button
                }

                // Set the calculated position
                dropdownMenu.css({
                    top: dropdownTop,
                    left: dropdownLeft,
                    display: 'block'
                });
            });

            // Hide dropdown menu when clicking outside
            $(document).on('click', function (event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').hide();
                }
            });
        });

        function confirmDelete(reportId) {
            if (confirm('Are you sure you want to delete this report?')) {
                document.getElementById('deleteForm_' + reportId).submit();
            }
        }
    </script>
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
                    <div class="table-responsive">
                        <!-- Make the table responsive -->
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
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdown">
                                                <a class="dropdown-item"
                                                    href="<?= site_url('rescuer/final-incident-report/edit/' . $report['final_report_id']) ?>">Edit</a>
                                                <a class="dropdown-item"
                                                    href="<?= site_url('rescuer/final-incident-report/preview/pdf/' . $report['final_report_id']) ?>"
                                                    target="_blank">Preview PDF</a>
                                                <a class="dropdown-item"
                                                    href="<?= site_url('rescuer/final-incident-report/preview/excel/' . $report['final_report_id']) ?>"
                                                    target="_blank">Preview Excel</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item"
                                                    href="<?= site_url('rescuer/final-incident-report/export/pdf/' . $report['final_report_id']) ?>">Export
                                                    PDF</a>
                                                <a class="dropdown-item"
                                                    href="<?= site_url('rescuer/final-incident-report/export/excel/' . $report['final_report_id']) ?>">Export
                                                    Excel</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                    onclick="confirmDelete(<?= $report['final_report_id'] ?>)">Delete</a>
                                            </div>
                                        </div>

                                        <!-- Hidden Form for Delete Action -->
                                        <form id="deleteForm_<?= $report['final_report_id'] ?>"
                                            action="<?= site_url('rescuer/final-incident-report/delete/' . $report['final_report_id']) ?>"
                                            method="POST" style="display: none;">
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- End of table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <?= view('hf/footer'); ?>
</body>

</html>