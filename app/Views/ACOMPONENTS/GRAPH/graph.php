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
        }

        h2 {
            font-weight: 600;
            margin-bottom: 20px;
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
                <h2>Fire Reports Data Visualization</h2>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <p>NUMBER OF FIRE INCIDENTS</p>
                            <canvas id="monthlyBarChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <canvas id="damageLineChart"></canvas>
                        </div>
                        <div class="chart-container mt-4">
                            <canvas id="causePieChart"></canvas>
                        </div>
                    </div>
                    <!-- Added section for injuries chart -->
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <p>NUMBER OF INJURIES</p>
                            <canvas id="monthlyInjuriesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Include your footer -->
        <?= view('hf/footer'); ?>
    </div>

    <script>
        // Define functions to process data and set up charts

        // Function to calculate monthly incidents count
        function calculateMonthlyIncidents(reports) {
            return Array.from({ length: 12 }, (_, monthIndex) => {
                const monthReports = reports.filter(report => new Date(report.report_date).getMonth() === monthIndex);
                return monthReports.length;
            });
        }

        // Function to calculate monthly damage costs
        function calculateDamageCosts(reports) {
            const monthlyCosts = Array.from({ length: 12 }, () => 0);
            reports.forEach(report => {
                const numericCost = parseInt(report.property_damage_cost.match(/\d+/)[0]);
                const month = new Date(report.report_date).getMonth();
                monthlyCosts[month] += numericCost;
            });
            return { monthlyCosts: monthlyCosts };
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
// Function to calculate monthly injuries count
function calculateMonthlyInjuries(reports) {
    
    const monthlyInjuries = Array.from({ length: 12 }, () => 0);
    
    reports.forEach(report => {
        const monthIndex = new Date(report.report_date).getMonth();

        if (report.number_of_injuries !== null && report.number_of_injuries !== undefined) {
            if (typeof report.number_of_injuries === 'number') {
                monthlyInjuries[monthIndex] += report.number_of_injuries;
            } else if (Array.isArray(report.number_of_injuries)) {
                if (report.number_of_injuries.every(item => typeof item === 'number')) {
                    const totalInjuriesInReport = report.number_of_injuries.reduce((acc, val) => acc + val, 0);
                    monthlyInjuries[monthIndex] += totalInjuriesInReport;
                } else {
                    console.warn(`Unexpected array format for number_of_injuries: ${report.number_of_injuries}`);
                }
            } else {
                console.warn(`Unexpected format for number_of_injuries: ${report.number_of_injuries}`);
            }
        }
    });

    return monthlyInjuries;
}


        // Fetch data and render charts on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/graph/getReports')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(reports => {
                    console.log('Fetched reports:', reports);

                    // Process data
                    const monthlyIncidents = calculateMonthlyIncidents(reports);
                    const damageCosts = calculateDamageCosts(reports);
                    const causePercentages = calculateCausePercentages(reports);
                    const monthlyInjuries = calculateMonthlyInjuries(reports); // Calculate injuries data

                    console.log('Monthly Incidents:', monthlyIncidents);
                    console.log('Damage Costs:', damageCosts);
                    console.log('Cause Percentages:', causePercentages);
                    console.log('Monthly Injuries:', monthlyInjuries);

                    // Render charts
                    renderMonthlyBarChart(monthlyIncidents);
                    renderDamageLineChart(damageCosts);
                    renderCausePieChart(causePercentages);
                    renderMonthlyInjuriesChart(monthlyInjuries); // Render injuries chart
                })
                .catch(error => {
                    console.error('Error fetching or processing data:', error);
                });
        });

        // Function to render monthly incidents bar chart
        function renderMonthlyBarChart(monthlyIncidents) {
            const ctx = document.getElementById('monthlyBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Number of Fire Incidents',
                        data: monthlyIncidents,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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

        // Function to render damage costs line chart
        function renderDamageLineChart(damageCosts) {
            const ctx = document.getElementById('damageLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Property Damage Cost',
                        data: damageCosts.monthlyCosts,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.1
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
                                callback: function (value, index, values) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Function to render cause pie chart
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
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)',
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                        borderWidth: 1
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

        // Function to render monthly injuries bar chart
        function renderMonthlyInjuriesChart(monthlyInjuries) {
            const ctx = document.getElementById('monthlyInjuriesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Number of Injuries',
                        data: monthlyInjuries,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
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
    </script>
</body>
</html>
