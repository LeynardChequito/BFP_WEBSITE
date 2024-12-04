<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Report Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-black to-red-900 min-h-screen p-10">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-200 text-red-800 p-4 mb-6 rounded">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="fireReportForm" action="<?= site_url('rescuer-report/save') ?>" method="post" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
        <?= csrf_field() ?>
        <div class="mb-4">
            <label for="latitude" class="block text-gray-700 font-bold mb-2">Latitude:</label>
            <input type="text" id="latitude" name="latitude" readonly class="w-full p-2 border border-gray-300 rounded bg-gray-100">
        </div>

        <div class="mb-4">
            <label for="longitude" class="block text-gray-700 font-bold mb-2">Longitude:</label>
            <input type="text" id="longitude" name="longitude" readonly class="w-full p-2 border border-gray-300 rounded bg-gray-100">
        </div>
        
        <div class="mb-4">
            <label for="rescuer_name" class="block text-gray-700 font-bold mb-2">Name of Rescuer:</label>
            <input type="text" name="rescuer_name" id="rescuer_name" placeholder="Enter Rescuer's Name" required class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="report_date" class="block text-gray-700 font-bold mb-2">Date:</label>
            <input type="date" name="report_date" id="report_date" required class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="start_time" class="block text-gray-700 font-bold mb-2">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="end_time" class="block text-gray-700 font-bold mb-2">End Time:</label>
            <input type="time" name="end_time" id="end_time" required class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
            <textarea name="address" id="address" placeholder="Enter the Complete Address of Fire Incident" required class="w-full p-2 border border-gray-300 rounded"></textarea>
        </div>

        <div id="location-error" class="text-red-500 font-bold hidden mb-4"></div>

        <div class="mb-4">
            <label for="cause_of_fire" class="block text-gray-700 font-bold mb-2">Cause of Fire:</label>
            <select name="cause_of_fire" id="cause_of_fire" required class="w-full p-2 border border-gray-300 rounded">
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
<div class="mb-4">
            <label for="property_damage_cost" class="block text-gray-700 font-bold mb-2">Select Property Damage Cost:</label>
            <select name="property_damage_cost" id="property_damage_cost" required class="w-full p-2 border border-gray-300 rounded" onchange="toggleInputField()">
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
                <!-- <option value="other">Other Amount</option> -->
            </select>
        </div>
        <div class="mb-4">
            <label for="number_of_injuries" class="block text-gray-700 font-bold mb-2">Number of Casualties:</label>
            <input type="number" name="number_of_injuries" id="number_of_injuries" placeholder="Enter the Number of Casualties" class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="additional_information" class="block text-gray-700 font-bold mb-2">Additional Information (optional):</label>
            <textarea name="additional_information" id="additional_information" class="w-full p-2 border border-gray-300 rounded"></textarea>
        </div>

        <div class="mb-4">
            <label for="photo" class="block text-gray-700 font-bold mb-2">Upload a Photo (optional):</label>
            <input type="file" name="photo" id="photo" class="w-full p-2 border border-gray-300 rounded">
        </div>

        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Submit</button>
    </form>

    <script>
        // Automatically fetch location on page load
        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Populate latitude and longitude fields
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    },
                    function (error) {
                        let errorMessage = "";
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Permission denied. Please allow access to location services.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Location information is unavailable.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "The request to get user location timed out.";
                                break;
                            default:
                                errorMessage = "An unknown error occurred.";
                        }
                        document.getElementById('location-error').classList.remove('hidden');
                        document.getElementById('location-error').textContent = errorMessage;
                    }
                );
            } else {
                document.getElementById('location-error').classList.remove('hidden');
                document.getElementById('location-error').textContent = "Geolocation is not supported by this browser.";
            }
        });
    </script>
</body>

</html>
