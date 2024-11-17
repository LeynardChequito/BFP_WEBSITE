<!-- app\Views\RESCUERREPORT\fire_report_form.php -->
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

    <form id="fireReportForm" action="<?= site_url('fire-report/store') ?>" method="post" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
        <?= csrf_field() ?>

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

        <!-- <div id="custom_amount_field" class="mb-4" style="display:none;">
            <label for="custom_amount" class="block text-gray-700 font-bold mb-2">Enter Amount:</label>
            <div class="flex items-center">
                <span class="mr-2 text-gray-700">₱</span>
                <input type="number" id="custom_amount" name="custom_amount" placeholder="Enter amount" class="w-full p-2 border border-gray-300 rounded" />
            </div>
        </div> -->

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

    <div id="successModal" class="modal hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg text-center max-w-sm">
            <span class="close text-gray-500 hover:text-gray-700 cursor-pointer">&times;</span>
            <div class="text-lg font-semibold text-green-600 mb-4">Fire Report Submitted Successfully!</div>
            <a href="<?= site_url('fire-report/create') ?>" class="block text-blue-500 hover:underline mb-2">Submit another report</a>
            <a href="<?= site_url('admin-home') ?>" class="block text-blue-500 hover:underline">Return to Admin Dashboard</a>
        </div>
    </div>

    <script>
        function toggleInputField() {
            const selectElement = document.getElementById("property_damage_cost");
            const customAmountField = document.getElementById("custom_amount_field");
            customAmountField.style.display = selectElement.value === "other" ? "block" : "none";
        }

        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById('fireReportForm');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);


                // Debug: Log FormData key-value pairs
                // console.log('Form data being sent:');
                // for (let [key, value] of formData.entries()) {
                //     console.log(`${key}:`, value);
                // }


                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Optional for AJAX detection

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText); // Parse JSON response

                            if (response.success) {
                                console.log('Data received from server:', response.data);
                                document.getElementById('successModal').classList.remove('hidden');
                            } else if (response.errors) {
                                console.error('Validation errors:', response.errors);
                                console.log('Data received from server:', response.data);
                                alert('Validation failed. Check the console for details.');
                            } else {
                                console.error('Unexpected server response:', response);
                                console.log('Data received from server:', response.data);
                                alert('Unexpected server response.');
                            }
                        } catch (e) {
                            console.error('Error parsing server response:', e);
                            console.error('Raw response:', xhr.responseText);
                            alert('An error occurred while processing the response.');
                        }
                    } else {
                        console.error('Server error:', xhr.status, xhr.statusText);
                        alert('An error occurred on the server. Please try again.');
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error occurred during the request.');
                    alert('A network error occurred. Please check your internet connection.');
                };

                xhr.send(formData);
            });

            document.querySelector('.close').onclick = function() {
                document.getElementById('successModal').classList.add('hidden');
            };
        });
    </script>
</body>

</html>