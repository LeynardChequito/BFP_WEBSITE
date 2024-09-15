<!DOCTYPE html>
<html>
<head>
    <title>Fire Report Form</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to bottom right, black, red);
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: blue;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: blueviolet;
        }

        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
        }
        
        .success-message {
            font-size: 24px;
            color: black;
        }
        
        .success-link, .success-submit {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .success-link:hover, .success-submit:hover {
            background-color: #0056b3;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .form-container, .modal-content {
                width: 90%;
                padding: 10px;
            }
            h1 {
                font-size: 18px;
            }
            .success-message {
                font-size: 16px;
            }
            button, .success-link, .success-submit {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Fire Report Form</h1>
    <form id="fireReportForm" action="<?=site_url('fire-report/store')?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label for="user_name">Name of Rescuer:</label>
        <input type="text" name="user_name" id="user_name" placeholder="Enter Rescuer's Name" required><br>

        <label for="report_date">Date:</label>
        <input type="date" name="report_date" id="report_date" required><br>

        <label for="start_time">Start Time:</label>
        <input type="time" name="start_time" id="start_time" required><br>

        <label for="end_time">End Time:</label>
        <input type="time" name="end_time" id="end_time" required><br>

        <label for="address">Address:</label>
        <textarea name="address" id="address" placeholder="Enter the Complete Address of Fire Incident" required></textarea><br>

        <label for="cause_of_fire">Cause of Fire:</label>
        <select name="cause_of_fire" id="cause_of_fire" required>
            <option value="Cooking">Cooking</option>
            <option value="Smoking material">Smoking material</option>
            <option value="Open flames">Open flames</option>
            <option value="Electrical">Electrical</option>
            <option value="Heating equipment">Heating equipment</option>
            <option value="Hazardous products">Hazardous products</option>
            <option value="Machinery / industrial">Machinery / industrial</option>
            <option value="Natural">Natural</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="fire_undetermined">Is the fire under investigation or designate "undetermined"?</label>
        <select name="fire_undetermined" id="fire_undetermined" required>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select><br>

        <label for="property_damage_cost">Select Property Damage Cost:</label>
        <div style="display: flex; align-items: center;">
<select name="property_damage_cost" id="property_damage_cost" required onchange="toggleInputField()">

<option value="₱0 - ₱99">₱0 - ₱99</option>
    <option value="₱100 - ₱999">₱100 - ₱999</option>
    <option value="₱1000 - ₱9999">₱1,000 - ₱9,999</option>
    <option value="₱10000 - ₱24999">₱10,000 - ₱24,999</option>
    <option value="₱25000 - ₱49999">₱25,000 - ₱49,999</option>
    <option value="₱50000 - ₱99999">₱50,000 - ₱99,999</option>
    <option value="₱100000 - ₱249999">₱100,000 - ₱249,999</option>
    <option value="₱250000 - ₱499999">₱250,000 - ₱499,999</option>
    <option value="₱500000 - ₱999999">₱500,000 - ₱999,999</option>
    <option value="₱1000000 - ₱1999999">₱1,000,000 - ₱1,999,999</option>
    <option value="other">Other Amount</option>
</select>
        </div>

<div id="custom_amount_field" style="display:none;">
    <label for="custom_amount">Enter Amount:</label>
    <div style="display: flex; align-items: center;">
        <span>₱</span>
        <input type="number" id="custom_amount" name="custom_amount" placeholder="Enter amount" />
    </div>
</div><br>

        <label for="number_of_injuries">Number of Casualties:</label>
        <input type="number" name="number_of_injuries" id="number_of_injuries" placeholder="Enter the Number of Casulaties><br>

        <label for="additional_information">Additional Information (optional):</label>
        <textarea name="additional_information" id="additional_information"></textarea><br>

        <label for="photo">Upload a Photo (optional):</label>
        <input type="file" name="photo" id="photo"><br>

        <button type="submit">Submit</button>
    </form>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="success-message">Fire Report Submitted Successfully!</div>
        <a href="<?= site_url('fire-report/create') ?>" class="success-link">Submit another report</a>
        <a href="<?= site_url('rescuemap') ?>" class="success-submit">Return to Rescuemap</a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modal = document.getElementById("successModal");
        if (!modal) {
            console.error("Modal element not found.");
            return;
        }

        var form = document.getElementById('fireReportForm');
        if (!form) {
            console.error("Form element not found.");
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    modal.style.display = "block";
                } else {
                    alert('An error occurred while submitting the form. Please try again.');
                }
            };
            xhr.onerror = function () {
                alert('An error occurred while submitting the form. Please try again.');
            };
            xhr.send(formData);
        });

    });

    function toggleInputField() {
        const select = document.getElementById('property_damage_cost');
        const customAmountField = document.getElementById('custom_amount_field');
        
        if (select.value === 'other') {
            customAmountField.style.display = 'block';
        } else {
            customAmountField.style.display = 'none';
        }
    }
</script>

</body>
</html>
