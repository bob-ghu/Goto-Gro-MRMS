<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$year = isset($_GET['year']) ? $_GET['year'] : date('Y'); // Default to the current year if not provided

// Function to get revenue data
function getRevenueData($year, $conn) {
    $query = "
        SELECT MONTH(s.Date) as month, SUM(s.Total_Price) as monthly_revenue
        FROM sales s
        WHERE YEAR(s.Date) = $year
        GROUP BY MONTH(s.Date)
        ORDER BY MONTH(s.Date)";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Function to get profit data
function getProfitData($year, $conn) {
    $query = "
        SELECT MONTH(s.Date) as month, SUM((i.Selling_Price - i.Retail_Price) * s.Quantity) as monthly_profit
        FROM sales s
        JOIN inventory i ON s.Item_ID = i.Item_ID
        WHERE YEAR(s.Date) = $year
        GROUP BY MONTH(s.Date)
        ORDER BY MONTH(s.Date)";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Fetch revenue and profit data
$revenue_data = getRevenueData($year, $conn);
$profit_data = getProfitData($year, $conn);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue and Profit Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        select {
            padding: 10px;
            margin-bottom: 20px;
        }
        .chart-container {
            width: 100%;
            height: 400px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Revenue and Profit Analysis</h1>

    <label for="year">Select Year: </label>
    <select id="year">
        <option value="2024" <?php if($year == 2024) echo 'selected'; ?>>2024</option>
        <option value="2023" <?php if($year == 2023) echo 'selected'; ?>>2023</option>
        <option value="2022" <?php if($year == 2022) echo 'selected'; ?>>2022</option>
        <!-- Add more years as needed -->
    </select>

    <div class="chart-container">
        <canvas id="revenueProfitChart"></canvas>
    </div>

    <h2>Monthly Breakdown</h2>
    <table id="monthly-summary-table">
        <thead>
            <tr>
                <th>Month</th>
                <th>Revenue ($)</th>
                <th>Profit ($)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
            <?php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            for ($i = 0; $i < 12; $i++) {
                $revenue = isset($revenue_data[$i]) ? $revenue_data[$i]['monthly_revenue'] : 0;
                $profit = isset($profit_data[$i]) ? $profit_data[$i]['monthly_profit'] : 0;
                echo "<tr><td>{$months[$i]}</td><td>\${$revenue}</td><td>\${$profit}</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Fetch data and update the chart and table
let chart;
document.getElementById('year').addEventListener('change', updateChart);

// Function to update chart and table based on the selected year
function updateChart() {
    const selectedYear = document.getElementById('year').value;

    // Fetch data from the backend using AJAX
    fetch('index.php?year=' + selectedYear)
        .then(response => response.text())
        .then(responseText => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(responseText, 'text/html');
            
            const revenueData = [];
            const profitData = [];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // Collect new data from the updated table
            const rows = doc.querySelectorAll('#monthly-summary-table tbody tr');
            rows.forEach((row, index) => {
                revenueData.push(parseFloat(row.cells[1].textContent.replace('$', '').trim()));
                profitData.push(parseFloat(row.cells[2].textContent.replace('$', '').trim()));
            });

            // Update chart with new data
            if (chart) {
                chart.destroy(); // Destroy the old chart before creating a new one
            }

            const ctx = document.getElementById('revenueProfitChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months, // Month names as labels
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                        tension: 0.1
                    }, {
                        label: 'Profit',
                        data: profitData,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Amount (in $)'
                            }
                        }
                    }
                }
            });
        });
}

// Initialize chart with the first year (default)
updateChart();
</script>

</body>
</html>