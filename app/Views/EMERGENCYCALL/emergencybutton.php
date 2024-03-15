<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Call Form</title>
    <style>
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
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 600px;
            border-radius: 10px;
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
        }

        /* Form styles */
        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .readonly {
            border: none;
            background-color: #f5f5f5;
            padding: 8px;
        }
    </style>
</head>
<body>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <form id="emergencyCallForm" action="<?= base_url('emergency-call/submit') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <label for="fire_type">Type of Fire:</label>
            <select id="fire_type" name="fire_type" required>
                <option value="Residential Fire">Residential Fire</option>
                <option value="Commercial Fire">Commercial Fire</option>
                <option value="Wildfire">Wildfire</option>
                <option value="Vehicle Fire">Vehicle Fire</option>
                <option value="Other">Other (Please Explain)</option>
            </select>

            <label for="fire_size">Size of Fire:</label>
            <select id="fire_size" name="fire_size" required>
                <option value="Small">Small (noticed only in a small area)</option>
                <option value="Medium">Medium (affecting several buildings or structures)</option>
                <option value="Large">Large (putting many buildings or structures at risk)</option>
                <option value="Under Control">Under Control (fire is controlled)</option>
                <option value="Uncertain">Uncertain (no definite information)</option>
            </select>

            <label for="victim_permission">Victim's Permission Granted:</label>
            <input type="checkbox" id="evacuate_permission" name="evacuate_permission">
            <label for="evacuate_permission">Agreed to evacuate from the affected area</label>
            <br>
            <input type="checkbox" id="extinguisher_usage" name="extinguisher_usage">
            <label for="extinguisher_usage">Used a fire extinguisher to control the fire</label>
            <br>
            <input type="checkbox" id="neighbor_assistance" name="neighbor_assistance">
            <label for="neighbor_assistance">Received assistance from neighbors</label>

            <label for="location_identification">Identify Location:</label>
            <button type="button" id="location_identification" onclick="identifyLocation()">Press to Identify Location</button>

            <label for="photo_upload">Add a Photo (Optional):</label>
            <input type="file" id="photo_upload" name="photo_upload">

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById("myModal").style.display = "block";
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    // Function to identify location
    function identifyLocation() {
        // Add your code to identify the location here
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
