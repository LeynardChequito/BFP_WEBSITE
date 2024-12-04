<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Incident Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
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
    <div class="form-container card">
        <div class="card-body">
            <form action="<?= site_url('rescuer/final-incident-report/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
<div class="row align-items-center mb-4">
                            <div class="col-md-4 text-left">
                                <a href="<?= site_url('rescuer/final-incident-report') ?>" class="btn btn-secondary">Back to Reports</a>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>Add Incident Report</h1>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="submit" class="btn btn-primary btn-block">Submit Report</button>
                            </div>
                        </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rescuer_name">Name of Rescuer:</label>
                            <input type="text" class="form-control" name="rescuer_name" id="rescuer_name" placeholder="Enter Rescuer's Name" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="report_date">Date:</label>
                            <input type="date" class="form-control" name="report_date" id="report_date" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_time">Start Time:</label>
                            <input type="time" class="form-control" name="start_time" id="start_time" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_time">End Time:</label>
                            <input type="time" class="form-control" name="end_time" id="end_time" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter the Complete Address of Fire Incident" required></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cause_of_fire">Cause of Fire:</label>
                            <select class="form-control" name="cause_of_fire" id="cause_of_fire" required>
                                <option value="Cooking">Cooking</option>
                                <option value="Smoking material">Smoking material</option>
                                <option value="Open flames">Open flames</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Heating equipment">Heating equipment</option>
                                <option value="Hazardous products">Hazardous products</option>
                                <option value="Machinery / industrial">Machinery / industrial</option>
                                <option value="Natural">Natural</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="property_damage_cost">Property Damage Cost:</label>
                            <select class="form-control" name="property_damage_cost" id="property_damage_cost" required onchange="toggleCustomAmount()">
                                <option value="₱0 - ₱99">₱0 - ₱99</option>
                                <option value="₱100 - ₱999">₱100 - ₱999</option>
                                <option value="₱1000 - ₱9999">₱1,000 - ₱9,999</option>
                                <option value="₱10000 - ₱24999">₱10,000 - ₱24,999</option>
                                <option value="₱25000 - ₱49999">₱25,000 - ₱49,999</option>
                                <option value="₱50000 - ₱99999">₱50,000 - ₱99,999</option>
                                <option value="₱100000 - ₱249999">₱100,000 - ₱249,999</option>
                                <option value="₱250000 - ₱499999">₱250,000 - ₱499,999</option>
                                <option value="₱500000 - ₱999999">₱500,000 - ₱999,999</option>
                                <option value="₱1000000">₱1,000,000 and above</option>
                                <!-- <option value="custom">Other Amount</option> -->
                            </select>
                            <!-- <div id="custom_amount_field" style="display:none; margin-top: 10px;">
                                <label for="custom_amount">Enter Custom Amount:</label>
                                <div style="display: flex; align-items: center;">
                                    <span>₱</span>
                                    <input type="number" class="form-control" name="custom_amount" id="custom_amount" placeholder="Enter amount">
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="number_of_injuries">Number of Casualties:</label>
                            <input type="number" class="form-control" name="number_of_injuries" id="number_of_injuries" placeholder="Enter the Number of Casualties">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">Upload a Photo (optional):</label>
                            <input type="file" class="form-control-file" name="photo" id="photo" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="additional_information">Additional Information (optional):</label>
                            <textarea class="form-control" name="additional_information" id="additional_information" rows="3"></textarea>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<?= view('hf/footer'); ?>

<!-- <script>
    function toggleCustomAmount() {
        const select = document.getElementById('property_damage_cost');
        const customAmountField = document.getElementById('custom_amount_field');
        
        if (select.value === 'custom') {
            customAmountField.style.display = 'block';
        } else {
            customAmountField.style.display = 'none';
        }
    }
</script> -->
<script>
    document.getElementById('incidentForm').addEventListener('submit', function () {
        document.getElementById('submitButton').disabled = true;
    });
</script>

</body>
</html>
