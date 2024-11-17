<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFP WEBSITE Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            color: #fff;
        }

        .sidebar {
            margin-top: auto;
            background-color: #EF3340;
            color: #fff;
            height: 100vh;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;

            display: block;
        }

        .container-fluid {
            padding-right: 0px !important;
            padding-left: 0px !important;
            margin-right: 0px !important;
            margin-left: 0px !important;
        }

        .container-fluid h3 {
            margin-bottom: 20px;
        }

        .content {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: darkviolet;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #343a40;
            color: #fff;
            border-bottom: none;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-primary:hover {
            background-color: #495057;
            border-color: #495057;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <?= view('ACOMPONENTS/adminheader'); ?>
        <!-- 
        <div style="margin-bottom: 20px;"></div> -->

        <div class="row">

            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <?php if (session()->has('success')) : ?>
                <div class="alert">
                    <?= session('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <!------------- MAIN CONTENT ---------------------->
            <div class="col-md-9">
                <div class="content">
                    <div class="card" style="max-width: 100rem;">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Welcome Admin</title>
                            <rect width="100%" height="100%" fill="#2F363F"></rect>
                            <text x="50%" y="40%" fill="#ffffff" dy=".3em" text-anchor="middle" font-size="40" font-family="Sofia, sans-serif" text-shadow="2px 2px 5px #9900cc" class="font-effect-outline">
                                <tspan x="50%" dy="-.3em">Good Day!</tspan>
                                <tspan x="50%" dy="1.2em">Welcome, Admin! Keep up the great work </tspan>
                                <tspan x="50%" dy="1.2em">and let's achieve success today!</tspan>
                            </text>

                        </svg>

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h3 class="card-title" style="text-align:center;">Vision</h3>
                                    <p class="card-text">Our vision is to create a better future by providing quality education to all students.</p>
                                </div>
                                <div class="col">
                                    <h3 class="card-title" style="text-align:center;">Mission</h3>
                                    <p class="card-text">Our mission is to empower individuals with the knowledge and skills to succeed in their chosen fields.</p>
                                </div>
                            </div>
                        </div>

                        <hr style="background-color: #ff6666; height: 2px; border: none;">

                        <ul class="list-group list-group-flush" id="task-list">
                            <!-- Tasks will be populated dynamically -->
                        </ul>

                        <div class="card-body">
                            <h5 class="card-title">TO DO LISTS</h5>
                            <input type="text" id="new-task" class="form-control mb-2" placeholder="Add a new task">
                            <button class="btn btn-primary" onclick="addTask()">Add Task</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= view('hf/footer'); ?>
    </div>

    <?= view('ACOMPONENTS/NEWS/NewsCreate'); ?>

    <script>
        // Function to add task to the DOM
        function addTaskToDOM(taskText, checked = false) {
            const taskList = document.getElementById('task-list');
            const newTask = document.createElement('li');
            newTask.className = 'list-group-item d-flex justify-content-between align-items-center';
            newTask.innerHTML = `
        <span>${taskText}</span>
        <div>
            <label class="me-2" style="margin-left: 30px;">
                <input type="checkbox" class="form-check-input me-2" ${checked ? 'checked' : ''}> On Process
            </label>
            <button class="btn btn-danger btn-sm" style="margin-left: 10px;" onclick="removeTask(this)">Done</button>
        </div>
    `;
            taskList.appendChild(newTask);
        }

        // Function to add a new task
        function addTask() {
            const taskText = document.getElementById('new-task').value;
            if (taskText === '') {
                alert('Please enter a task');
                return;
            }
            addTaskToDOM(taskText);
            saveTasks(); // Save tasks to localStorage
            document.getElementById('new-task').value = ''; // Clear input field
        }

        // Function to remove a task with confirmation
        function removeTask(button) {
            // Show confirmation dialog
            const confirmation = confirm("Are you sure you want to mark this task as done?");
            if (confirmation) {
                const taskItem = button.closest('li');
                taskItem.remove();
                saveTasks(); // Save tasks to localStorage after removal
            }
        }

        // Function to save tasks to localStorage
        function saveTasks() {
            const taskList = document.querySelectorAll('#task-list li');
            const tasks = [];
            taskList.forEach(task => {
                tasks.push({
                    text: task.querySelector('span').textContent,
                    checked: task.querySelector('input').checked
                });
            });
            localStorage.setItem('tasks', JSON.stringify(tasks));
        }

        // Function to load tasks from localStorage
        function loadTasks() {
            const savedTasks = localStorage.getItem('tasks');
            if (savedTasks) {
                const tasks = JSON.parse(savedTasks);
                tasks.forEach(task => {
                    addTaskToDOM(task.text, task.checked);
                });
            }
        }

        // Listen for changes to checkboxes and save state
        document.addEventListener('change', function(e) {
            if (e.target && e.target.type === 'checkbox') {
                saveTasks(); // Save tasks to localStorage on checkbox toggle
            }
        });

        // Load tasks when the page is loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
        });
    </script>

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>