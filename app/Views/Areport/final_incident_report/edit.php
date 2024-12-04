<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?= view('ACOMPONENTS/adminheader'); ?>
    <div class="container-fluid">
        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>
            <div class="col-md-10">
                <div class="container mt-5">
                    <form action="<?= site_url('rescuer/final-incident-report/update/'. $report['final_report_id']) ?>" method="POST" enctype="multipart/form-data">
                       <?= csrf_field() ?>
                       
                        <!-- Title and Buttons Row -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-4 text-left">
                                <a href="<?= site_url('rescuer/final-incident-report') ?>" class="btn btn-secondary">Back to Reports</a>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>Edit Incident Report</h1>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="rescuer_name">Full Name</label>
                                <input type="text" class="form-control" name="rescuer_name" value="<?= $report['rescuer_name'] ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="report_date">Report Date</label>
                                <input type="date" class="form-control" name="report_date" value="<?= $report['report_date'] ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_time">Start Time</label>
                                <input type="time" class="form-control" name="start_time" value="<?= $report['start_time'] ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_time">End Time</label>
                                <input type="time" class="form-control" name="end_time" value="<?= $report['end_time'] ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" value="<?= $report['address'] ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cause_of_fire">Cause of Fire</label>
                                <input type="text" class="form-control" name="cause_of_fire" value="<?= $report['cause_of_fire'] ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="property_damage_cost_estimate">Property Damage Cost Estimate</label>
                                <input type="number" step="0.01" class="form-control" name="property_damage_cost_estimate" value="<?= $report['property_damage_cost_estimate'] ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="number_of_injuries">Number of Injuries</label>
                                <input type="number" class="form-control" name="number_of_injuries" value="<?= $report['number_of_injuries'] ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="photo">Upload Photo</label>
                                <input type="file" class="form-control-file" name="photo">
                                <?php if (!empty($report['photo'])): ?>
                                    <p>Current Photo: <?= $report['photo'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="additional_information">Additional Information</label>
                            <textarea class="form-control" name="additional_information" rows="4" required><?= $report['additional_information'] ?></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?= view('hf/footer'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
