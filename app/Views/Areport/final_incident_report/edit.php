<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5"><?= view('ACOMPONENTS/amanagesidebar'); ?>
        <h2>Edit Incident Report</h2>
        <form action="<?= site_url('rescuer/final-incident-report/update/' . $report['final_report_id']) ?>" method="POST">
            <div class="form-group">
                <label for="communityreport_id">Community Report ID</label>
                <input type="text" class="form-control" name="communityreport_id" value="<?= $report['communityreport_id'] ?>" required>
            </div>
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" class="form-control" name="fullName" value="<?= $report['fullName'] ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" value="<?= $report['address'] ?>" required>
            </div>
            <div class="form-group">
                <label for="cause_of_fire">Cause of Fire</label>
                <input type="text" class="form-control" name="cause_of_fire" value="<?= $report['cause_of_fire'] ?>" required>
            </div>
            <!-- Add additional fields as necessary -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <a href="<?= site_url('rescuer/final-incident-report') ?>" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
    <?= view('hf/footer'); ?>
</body>
</html>
