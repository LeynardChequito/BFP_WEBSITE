<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Reports Graph</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container-fluid {
            padding: 20px;
        }

        .chart-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-container.standard-size {
            height: 300px;
            /* Standard size for other charts */
        }

        .chart-container.large-size {
            height: 400px;
            /* Increased size for the pie chart */
        }
        .chart-container.extralarge-size {
            height: 550px;
            /* Increased size for the pie chart */
        }

        .chart-container canvas {
            max-height: 100%;
            max-width: 100%;
            /* Ensure the canvas fits within the container */
        }

        .chart-header {
            font-size: 2rem;
            /* Increase font size */
            font-weight: bold;
            /* Make the text bold */
            text-align: center;
            /* Center the text */
            margin-bottom: 40px;
            /* Add space below the header */
        }
    </style>
</head>

<body>
    <!-- Include your header views -->
    <?= view('ACOMPONENTS/NEWS/adminnewsheader'); ?>
    <?= view('ACOMPONENTS/adminheader'); ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Include your sidebar -->
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-9">
                <div class="chart-header">Fire Reports Data Visualization</div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container standard-size">
                            <p>NUMBER OF FIRE INCIDENTS</p>
                            <canvas id="monthlyBarChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container standard-size">
                            <p>DAMAGE COSTS</p>
                            <canvas id="damageLineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container large-size">
                            <p>CAUSE OF FIRE INCIDENTS</p>
                            <canvas id="causePieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container extralarge-size">
                        <p>NUMBER OF CASUALTIES</p>

                        <!-- Time Period Selector -->
                        <div class="form-group">
                            <label for="timePeriod">Select Time Period:</label>
                            <select class="form-control" id="timePeriod">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="monthSelector">Select Month:</label>
                            <select class="form-control" id="monthSelector">
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>

                        <!-- Chart Container -->
                        <div class="chart-container standard-size">
                            <p>Number of Injuries</p>
                            <canvas id="injuriesChart"></canvas>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Include your footer -->
        <?= view('hf/footer'); ?>
    </div>

    <script>
        // Function to calculate monthly incidents count
        function calculateMonthlyIncidents(reports = []) {
            if (!Array.isArray(reports)) {
                console.error('Invalid data format. Expected an array.');
                return [];
            }
            return Array.from({
                length: 12
            }, (_, monthIndex) => {
                const monthReports = reports.filter(report => new Date(report.report_date).getMonth() === monthIndex);
                return monthReports.length;
            });
        }

        // Function to calculate monthly damage costs
        function calculateDamageCosts(reports = []) {
            const monthlyCosts = Array.from({
                length: 12
            }, () => 0); // Initialize with 12 months

            reports.forEach(report => {
                // Check if property_damage_cost is a valid string and not "UNKNOWN"
                if (report.property_damage_cost && typeof report.property_damage_cost === 'string' && report.property_damage_cost !== 'UNKNOWN') {
                    const matches = report.property_damage_cost.match(/\d+/g); // Extract all numeric values
                    if (matches) {
                        const totalCost = matches.map(Number).reduce((sum, num) => sum + num, 0); // Sum all numeric values
                        const month = new Date(report.report_date).getMonth(); // Get the month from report_date
                        monthlyCosts[month] += totalCost; // Add the total cost to the corresponding month
                    } else {
                        console.warn(`Could not extract number from property_damage_cost: ${report.property_damage_cost}`);
                    }
                } else {
                    console.warn(`Invalid or missing property_damage_cost in report:`, report);
                }
            });

            return {
                monthlyCosts: monthlyCosts
            };
        }

        // Function to calculate cause percentages
        function calculateCausePercentages(reports) {
            const causeCount = {};
            const totalReports = reports.length;
            reports.forEach(report => {
                const cause = report.cause_of_fire;
                if (!causeCount[cause]) {
                    causeCount[cause] = 0;
                }
                causeCount[cause]++;
            });
            const causePercentages = {};
            for (const cause in causeCount) {
                causePercentages[cause] = (causeCount[cause] / totalReports) * 100;
            }
            return causePercentages;
        }

        // Fetch data and render charts on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/graph/getReports')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(reports => {
                    console.log('Fetched reports:', reports); // Log the reports to see what data is coming in

                    if (!Array.isArray(reports)) {
                        throw new Error('Invalid data format. Expected an array.');
                    }

                    // Process data
                    const monthlyIncidents = calculateMonthlyIncidents(reports);
                    const damageCosts = calculateDamageCosts(reports);
                    const causePercentages = calculateCausePercentages(reports);

                    // Render charts
                    renderMonthlyBarChart(monthlyIncidents);
                    renderDamageLineChart(damageCosts);
                    renderCausePieChart(causePercentages);
                })
                .catch(error => {
                    console.error('Error fetching or processing data:', error);
                });


            function renderMonthlyBarChart(monthlyIncidents) {
                const ctx = document.getElementById('monthlyBarChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: 'Number of Fire Incidents',
                            data: monthlyIncidents,
                            backgroundColor: 'rgb(0, 123, 255)', // Dark Blue
                            borderColor: 'rgb(0, 123, 255)', // Dark Blue Border
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            function renderDamageLineChart(damageCosts) {
                const ctx = document.getElementById('damageLineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: 'Property Damage Cost',
                            data: damageCosts.monthlyCosts,
                            backgroundColor: 'rgb(255, 69, 0)', // Dark Red
                            borderColor: 'rgb(255, 69, 0)', // Dark Red Border
                            tension: 0.001
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function renderCausePieChart(causePercentages) {
                const ctx = document.getElementById('causePieChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(causePercentages),
                        datasets: [{
                            label: 'Cause of Fire Incidents',
                            data: Object.values(causePercentages),
                            backgroundColor: [
                                'rgb(255, 99, 132)', // Vibrant Red
                                'rgb(54, 162, 235)', // Bright Blue
                                'rgb(255, 206, 86)', // Bright Yellow
                                'rgb(75, 192, 192)', // Bright Teal
                                'rgb(153, 102, 255)', // Bright Purple
                                'rgb(255, 159, 64)', // Bright Orange
                                'rgb(255, 69, 0)', // Darker Red
                                'rgb(0, 123, 255)', // Dark Blue
                                'rgb(255, 193, 7)', // Dark Yellow
                                'rgb(0, 206, 209)', // Darker Teal
                                'rgb(138, 43, 226)', // Dark Purple
                                'rgb(255, 140, 0)' // Dark Orange
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }
        });

        // Function for handling injuries chart with time period and month selectors
        document.addEventListener('DOMContentLoaded', function() {
            const timePeriodSelector = document.getElementById('timePeriod');
            const monthSelector = document.getElementById('monthSelector');
            const injuriesChartElement = document.getElementById('injuriesChart').getContext('2d');
            let injuriesChart;

            // Initial fetch with the default 'weekly' time period and selected month
            fetchDataAndRenderChart('weekly', monthSelector.value);

            timePeriodSelector.addEventListener('change', function() {
                const selectedTimePeriod = timePeriodSelector.value;
                const selectedMonth = selectedTimePeriod === 'weekly' ? monthSelector.value : null;

                if (selectedTimePeriod === 'weekly') {
                    monthSelector.parentElement.style.display = 'block';
                } else {
                    monthSelector.parentElement.style.display = 'none';
                }

                fetchDataAndRenderChart(selectedTimePeriod, selectedMonth);
            });

            monthSelector.addEventListener('change', function() {
                const selectedTimePeriod = timePeriodSelector.value;
                if (selectedTimePeriod === 'weekly') {
                    const selectedMonth = monthSelector.value;
                    fetchDataAndRenderChart(selectedTimePeriod, selectedMonth);
                }
            });

            function getWeekRanges(month) {
                const daysInMonth = new Date(new Date().getFullYear(), month, 0).getDate();
                const weeks = [];
                let startDay = 1;

                for (let week = 1; startDay <= daysInMonth; week++) {
                    let endDay = Math.min(startDay + 6, daysInMonth);
                    weeks.push(`Week ${week} (Day ${startDay}-${endDay})`);
                    startDay += 7;
                }

                return weeks;
            }

            function fetchDataAndRenderChart(timePeriod, month = null) {
                let url = `/graph/getReport?timePeriod=${timePeriod}`;
                if (month) {
                    url += `&month=${month}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (injuriesChart) {
                            injuriesChart.destroy();
                        }

                        let labels = [];
                        if (timePeriod === 'weekly' && month) {
                            const monthIndex = new Date(Date.parse(month + " 1, 2022")).getMonth() + 1;
                            labels = getWeekRanges(monthIndex);
                        } else {
                            labels = Object.keys(data);
                        }

                        injuriesChart = new Chart(injuriesChartElement, {
                            type: timePeriod === 'yearly' ? 'line' : 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: `Number of Injuries (${timePeriod})`,
                                    data: Object.values(data),
                                    backgroundColor: 'rgb(179, 0, 0)',
                                    borderColor: 'rgb(77, 0, 0)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching or processing data:', error);
                    });
            }
        });
    </script>
</body>

</html>