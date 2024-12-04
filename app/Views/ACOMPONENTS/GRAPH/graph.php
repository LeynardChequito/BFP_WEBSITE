<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Reports Data Visualization</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
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
        .container-fluid {
            padding-right: 0px !important;
            padding-left: 0px !important;
            margin-right: 0px !important;
            margin-left: 0px !important;
        }
        .chart-container.standard-size {
            height: 300px;
        }

        .chart-container.large-size {
            height: 400px;
        }

        .chart-container.extralarge-size {
            height: 550px;
        }

        .chart-container canvas {
            max-height: 100%;
            max-width: 100%;
        }

        .chart-header {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
   

    <div class="container-fluid">
         <?= view('ACOMPONENTS/adminheader'); ?>
        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-9">
                <div class="chart-header">Fire Reports Data Visualization</div>
                <div class="row">
                    <!-- Fire Incidents Chart -->
                    <div class="col-lg-6">
                        <div class="chart-container standard-size">
                            <p>NUMBER OF FIRE INCIDENTS</p>
                            <canvas id="fireIncidentsChart"></canvas>
                        </div>
                    </div>

                    <!-- Damage Costs Chart -->
                    <div class="col-lg-6">
                        <div class="chart-container standard-size">
                            <p>DAMAGE COSTS</p>
                            <canvas id="damageCostsChart"></canvas>
                        </div>
                    </div>

                    <!-- Cause of Fire Chart -->
                    <div class="col-lg-6">
                        <div class="chart-container large-size">
                            <p>CAUSE OF FIRE INCIDENTS</p>
                            <canvas id="causeOfFireChart"></canvas>
                        </div>
                    </div>

                    <!-- Number of Casualties Chart -->
                    <div class="col-lg-6">
                        <div class="chart-container extralarge-size">
                            <p>NUMBER OF CASUALTIES</p>
                            <div class="form-group">
                                <label for="timePeriod">Select Time Period:</label>
                                <select class="form-control" id="timePeriod">
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="form-group" id="monthSelectorContainer">
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
                            <canvas id="casualtiesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= view('hf/footer'); ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timePeriodSelector = document.getElementById('timePeriod');
            const monthSelectorContainer = document.getElementById('monthSelectorContainer');
            const monthSelector = document.getElementById('monthSelector');
            const casualtiesChartElement = document.getElementById('casualtiesChart').getContext('2d');

            let casualtiesChart;

            fetchAndRenderCasualtiesChart('weekly', monthSelector.value);

            timePeriodSelector.addEventListener('change', function () {
                const timePeriod = timePeriodSelector.value;

                if (timePeriod === 'weekly') {
                    monthSelectorContainer.style.display = 'block';
                } else {
                    monthSelectorContainer.style.display = 'none';
                }

                fetchAndRenderCasualtiesChart(timePeriod, timePeriod === 'weekly' ? monthSelector.value : null);
            });

            monthSelector.addEventListener('change', function () {
                const timePeriod = timePeriodSelector.value;
                if (timePeriod === 'weekly') {
                    fetchAndRenderCasualtiesChart(timePeriod, monthSelector.value);
                }
            });

            function fetchAndRenderCasualtiesChart(timePeriod, month) {
                let url = `/graph/getReport?timePeriod=${timePeriod}`;
                if (month) {
                    url += `&month=${month}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (!data || typeof data !== 'object' || Object.keys(data).length === 0) {
                            console.error('No data available:', data);
                            alert('No data available for the selected time period.');
                            return;
                        }

                        if (casualtiesChart) {
                            casualtiesChart.destroy();
                        }

                        casualtiesChart = new Chart(casualtiesChartElement, {
                            type: timePeriod === 'yearly' ? 'line' : 'bar',
                            data: {
                                labels: Object.keys(data),
                                datasets: Object.keys(data[Object.keys(data)[0]] || {}).map(address => ({
                                    label: address,
                                    data: Object.keys(data).map(period => data[period]?.[address] || 0),
                                    backgroundColor: getRandomColor(),
                                })),
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                            },
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }
            fetch('/graph/getReports')
        .then((response) => response.json())
        .then((data) => {
            renderFireIncidentsChart(data.fireIncidents);
            renderDamageCostsChart(data.damageCosts);
            renderCauseOfFireChart(data.causeOfFire);
        })
        .catch((error) => console.error("Error fetching data:", error));

    function renderFireIncidentsChart(fireIncidents) {
        const ctx = document.getElementById("fireIncidentsChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: Object.keys(fireIncidents),
                datasets: [
                    {
                        label: "Number of Fire Incidents",
                        data: Object.values(fireIncidents),
                        backgroundColor: "rgb(0, 123, 255)",
                        borderColor: "rgb(0, 123, 255)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }

    function renderDamageCostsChart(damageCosts) {
        const ctx = document.getElementById("damageCostsChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: Object.keys(damageCosts),
                datasets: [
                    {
                        label: "Damage Costs (₱)",
                        data: Object.values(damageCosts),
                        backgroundColor: "rgb(255, 99, 132)",
                        borderColor: "rgb(255, 99, 132)",
                        fill: false,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => `₱${value.toLocaleString()}`,
                        },
                    },
                },
            },
        });
    }

    function renderCauseOfFireChart(causeOfFire) {
        const ctx = document.getElementById("causeOfFireChart").getContext("2d");
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: Object.keys(causeOfFire),
                datasets: [
                    {
                        data: Object.values(causeOfFire),
                        backgroundColor: [
                            "rgb(255, 99, 132)",
                            "rgb(54, 162, 235)",
                            "rgb(255, 206, 86)",
                            "rgb(75, 192, 192)",
                            "rgb(153, 102, 255)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
            },
        });
    }
            function getRandomColor() {
                return `#${Math.floor(Math.random() * 16777215).toString(16)}`;
            }
        });
    </script>
</body>

</html>
