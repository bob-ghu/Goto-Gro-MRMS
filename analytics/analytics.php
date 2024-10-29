<?php
require_once('../database/settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch Member Purchases
function getMemberPurchases($conn) {
    // Prepare SQL statement to fetch items
    $sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, 
        sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, sales.Payment_Method, sales.Staff_ID 
        FROM sales 
        JOIN members ON sales.Member_ID = members.Member_ID 
        JOIN inventory ON sales.Item_ID = inventory.Item_ID";
    $result = $conn->query($sql);

    // Fetch all items as an array
    $purchases = [];
    while ($row = $result->fetch_assoc()) {
        $purchases[] = [
            'item_name' => $row['Item_Name'],
            'quantity' => $row['Quantity']
        ];
    }
    return $purchases;
}

// Frequent Item Analysis Based on Quantity
function getFrequentItems($purchases) {
    $frequency = [];
    foreach ($purchases as $purchase) {
        $item = $purchase['item_name'];
        $quantity = $purchase['quantity'];
        if (isset($frequency[$item])) {
            $frequency[$item] += $quantity; // Add the quantity to the existing total
        } else {
            $frequency[$item] = $quantity; // Initialize with the current quantity
        }
    }
    arsort($frequency); // Sort items by total quantity in descending order
    return array_slice($frequency, 0, 10, true); // Return top 10 items based on quantity
}

function getCategoryQuantities($conn) {
    $sql = "SELECT inventory.Category AS Item_Category, SUM(sales.Quantity) AS Total_Quantity
            FROM sales 
            JOIN inventory ON sales.Item_ID = inventory.Item_ID
            GROUP BY inventory.Category";
    $result = $conn->query($sql);

    // Fetch and store category quantities in an associative array
    $categoryData = [];
    while ($row = $result->fetch_assoc()) {
        $categoryData[$row['Item_Category']] = $row['Total_Quantity'];
    }

    //echo "<pre>";
    //print_r($categoryData);
    //echo "</pre>";

    return $categoryData;
}

// Fetch the period from GET parameters
$period = $_GET['period'] ?? 'daily'; // Default to 'daily' if not set

function getSalesByPeriod($conn, $period) {
    $dateFormat = '';
    switch ($period) {
        case 'daily':
            $dateFormat = 'DATE(Sale_Date)'; // Group by day
            break;
        case 'weekly':
            $dateFormat = 'YEAR(Sale_Date), WEEK(Sale_Date)'; // Group by week
            break;
        case 'monthly':
            $dateFormat = 'YEAR(Sale_Date), MONTH(Sale_Date)'; // Group by month
            break;
        default:
            $dateFormat = 'DATE(Sale_Date)'; // Default to daily
    }

    $sql = "SELECT $dateFormat AS Sale_Period, SUM(sales.Quantity) AS Total_Quantity
            FROM sales 
            GROUP BY Sale_Period
            ORDER BY Sale_Period";
    
    $result = $conn->query($sql);

    $salesData = [];
    while ($row = $result->fetch_assoc()) {
        // Format Sale_Period for display based on selected period
        if ($period === 'weekly') {
            $salesData["Week " . $row['Sale_Period']] = $row['Total_Quantity']; // Format as "Week 2022-01"
        } elseif ($period === 'monthly') {
            $salesData[date("F Y", mktime(0, 0, 0, $row['Sale_Period'][1], 1, $row['Sale_Period'][0]))] = $row['Total_Quantity']; // Format as "January 2022"
        } else {
            $salesData[$row['Sale_Period']] = $row['Total_Quantity']; // Just use the date
        }
    }

    return $salesData;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Step 4: Perform Analysis
$purchases = getMemberPurchases($conn);
$frequentItems = getFrequentItems($purchases);

$categoryQuantities = getCategoryQuantities($conn);

$salesData = getSalesByPeriod($conn, $period);
echo json_encode($salesData);

$conn->close(); // Close the database connection
?>

<style>
    /* Container for the chart to control its size */
    .chart-container {
        width: 800px;
        height: 400px;
        position: relative;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Frequent Item Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Step 5: Display Frequent Items in a Table -->
    <h3>Top 10 Most Frequent Items Bought</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Item</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($frequentItems as $item => $quantity): ?>
            <tr>
                <td><?php echo htmlspecialchars($item); ?></td>
                <td><?php echo htmlspecialchars($quantity); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Step 6: Display Frequent Items in a Bar Chart -->
    <div class="chart-container">
        <canvas id="frequentItemsChart"></canvas>
    </div>
    <script>
        const ctx1 = document.getElementById('frequentItemsChart').getContext('2d');
        const frequentItemsChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($frequentItems)); ?>,
                datasets: [{
                    label: 'Total Quantity Bought',
                    data: <?php echo json_encode(array_values($frequentItems)); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Enable responsiveness
                maintainAspectRatio: false, // Allow custom aspect ratio
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    <!-- Step 3: Display Category Data in a Pie Chart -->
    <h3>Most Popular Item Categories (Based on Total Quantity)</h3>
    <div class="chart-container">
        <canvas id="categoryPieChart"></canvas>
    </div>
    <script>
        const ctx2 = document.getElementById('categoryPieChart').getContext('2d');
        const categoryPieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($categoryQuantities)); ?>,
                datasets: [{
                    label: 'Total Quantity by Category',
                    data: <?php echo json_encode(array_values($categoryQuantities)); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>

    <h3>Select Sales Period</h3>
    <select id="salesPeriod">
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
    </select>
    <button onclick="updateChart()">Update Chart</button>

    <h3>Sales Quantity by Period</h3>
    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>

    <script>
        let salesChart; // Declare the variable to hold the chart instance

        function updateChart() {
            const period = document.getElementById('salesPeriod').value;

            fetch(`../sales/sales.php?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (salesChart) {
                        salesChart.destroy(); // Destroy the previous chart instance if it exists
                    }
                    const ctx3 = document.getElementById('salesChart').getContext('2d');
                    salesChart = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data), // X-axis labels based on period
                            datasets: [{
                                label: 'Total Quantity Sold',
                                data: Object.values(data), // Y-axis data based on quantity
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching sales data:', error)); // Handle any errors
        }
    </script>
</body>
</html>