<?php
require_once('../database/settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$memberId = $_GET['Member_ID'] ?? null;
$period = $_GET['period'] ?? 'daily';

if (isset($_GET['period'])) {
    //$period = $_GET['period'];
    $specificDate = $_GET['specific_date'] ?? null; // Get specific date if provided
    $specificMonth = $_GET['specific_month'] ?? null; // Get specific month if provided

    $categoryQuantities = getCategoryQuantities($conn, $period, $specificDate, $specificMonth, $memberId);

    // Call the function to get top frequent items based on the selected period
    $topItems = getTopFrequentItemsByPeriod($conn, $period, $specificDate, $specificMonth, $memberId);

    echo json_encode([
        'categoryQuantities' => $categoryQuantities, // For pie chart
        'topItems' => $topItems // For bar chart
    ]);
    exit;
}

function getCategoryQuantities($conn, $period, $specificDate = null, $specificMonth = null, $memberId = null)
{
    $memberCondition = '';
    if ($memberId) {
        $memberCondition = "AND sales.Member_ID = '$memberId'"; // Assuming there's a Member_ID in the sales table
    }

    $dateCondition = '';

    // Set the date condition based on the selected period
    if ($period === 'daily') {
        $dateCondition = "DATE(sales.Sale_Date) = CURDATE()";
    } elseif ($period === 'weekly') {
        $dateCondition = "YEARWEEK(sales.Sale_Date, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($period === 'monthly') {
        $dateCondition = "MONTH(sales.Sale_Date) = MONTH(CURDATE()) AND YEAR(sales.Sale_Date) = YEAR(CURDATE())";
    } elseif ($period === 'specific_date' && $specificDate) {
        $dateCondition = "DATE(sales.Sale_Date) = '$specificDate'";
    } elseif ($period === 'specific_month' && $specificMonth) {
        $dateCondition = "MONTH(sales.Sale_Date) = MONTH('$specificMonth-01') AND YEAR(sales.Sale_Date) = YEAR('$specificMonth-01')";
    }

    // SQL query with the date condition
    $sql1 = "SELECT inventory.Category AS Item_Category, SUM(sales.Quantity) AS Total_Quantity
            FROM sales 
            JOIN inventory ON sales.Item_ID = inventory.Item_ID
            WHERE $dateCondition $memberCondition
            GROUP BY inventory.Category";
    $result1 = $conn->query($sql1);

    // Fetch and store category quantities in an associative array
    $categoryData = [];
    while ($row = $result1->fetch_assoc()) {
        $categoryData[$row['Item_Category']] = $row['Total_Quantity'];
    }

    return $categoryData;
}

function getTopFrequentItemsByPeriod($conn, $period, $specificDate = null, $specificMonth = null, $memberId = null)
{
    $memberCondition = '';
    if ($memberId) {
        $memberCondition = "AND sales.Member_ID = '$memberId'"; // Assuming there's a Member_ID in the sales table
    }

    // Determine date condition based on period
    $dateCondition = '';
    switch ($period) {
        case 'daily':
            $dateCondition = "DATE(sales.Sale_Date) = CURDATE()";
            break;
        case 'weekly':
            $dateCondition = "YEARWEEK(sales.Sale_Date, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'monthly':
            $dateCondition = "MONTH(sales.Sale_Date) = MONTH(CURDATE()) AND YEAR(sales.Sale_Date) = YEAR(CURDATE())";
            break;
        case 'specific_date':
            if ($specificDate) {
                $dateCondition = "DATE(sales.Sale_Date) = '$specificDate'";
            }
            break;
        case 'specific_month':
            if ($specificMonth) {
                $year = substr($specificMonth, 0, 4);
                $month = substr($specificMonth, 5, 2);
                $dateCondition = "YEAR(sales.Sale_Date) = $year AND MONTH(sales.Sale_Date) = $month";
            }
            break;
        default:
            return []; // Return empty array if period is not specified
    }

    // SQL query to fetch top 10 items by frequency in the specified period
    $sql2 = "SELECT inventory.Name AS item_name, SUM(sales.Quantity) AS total_quantity
            FROM sales
            JOIN inventory ON sales.Item_ID = inventory.Item_ID
            WHERE $dateCondition $memberCondition
            GROUP BY sales.Item_ID
            ORDER BY total_quantity DESC
            LIMIT 10";

    $result2 = $conn->query($sql2);

    // Fetch the results as an array
    $topItems = [];
    while ($row = $result2->fetch_assoc()) {
        $topItems[] = [
            'item_name' => $row['item_name'],
            'total_quantity' => $row['total_quantity']
        ];
    }
    return $topItems;
}

function getMembers($conn)
{
    $sql = "SELECT Member_ID, Full_Name FROM members";
    $result = $conn->query($sql);

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = [
            'id' => $row['Member_ID'],
            'name' => $row['Full_Name']
        ];
    }
    return $members;
}

if (isset($_GET['fetch_members'])) {
    $members = getMembers($conn);
    echo json_encode($members);
    exit; // Stop further execution after sending members data
}

// Notification count
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$recentNoti = mysqli_query($conn, $query2);

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Goto Gro MRMS</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="../styles/analytics.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>

<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="../images/logo.png" alt="Logo" />
                    <h2>Goto<span class="danger">Gro</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp"> close </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../index/index.php">
                    <span class="material-icons-sharp"> dashboard </span>
                    <h3>Dashboard</h3>
                </a>
                <a href="../members/members.php">
                    <span class="material-icons-sharp"> person_outline </span>
                    <h3>Members</h3>
                </a>
                <a href="../inventory/inventory.php">
                    <span class="material-icons-sharp"> inventory_2 </span>
                    <h3>Inventory </h3>
                </a>
                <a href="../sales/sales.php">
                    <span class="material-icons-sharp"> receipt_long </span>
                    <h3>Sales</h3>
                </a>
                <a href="../notification/notification.php">
                    <span class="material-icons-sharp"> notifications </span>
                    <h3>Notifications</h3>
                    <?php if (mysqli_num_rows($notiCount) > 0): ?>
                        <span class="message-count">
                            <?php echo mysqli_num_rows($notiCount); ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="../analytics/analytics.php" class="active">
                    <span class="material-icons-sharp"> insights </span>
                    <h3>Analytics</h3>
                </a>
                <a href="../feedback/feedback.php">
                    <span class="material-icons-sharp"> feedback </span>
                    <h3>Feedback</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> logout </span>
                    <h3>Logout</h3>
                </a>
                <!----- EXTRA ----->
                <a href="#">
                    <span class="material-icons-sharp"> report_gmailerrorred </span>
                    <h3>Reports</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> settings </span>
                    <h3>Settings</h3>
                </a>
            </div>
        </aside>



        <main>
            <h1>Analytics</h1>

            <div class="insights">

                <div class="filter">
                    <h3>Filter</h3>
                    <h4>Select Sales Period</h4>
                    <select id="salesPeriod" onchange="toggleInputFields()">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="specific_date">Specific Date</option>
                        <option value="specific_month">Specific Month</option>
                    </select>
                    <input type="date" id="specificDate" style="display: none;" placeholder="Select Date">
                    <input type="month" id="specificMonth" style="display: none;" placeholder="Select Month">

                    <h4>Select Member</h4>
                    <select id="memberSelect">
                        <option value="" selected>Select a member</option>
                        <!-- Options will be populated via JavaScript -->
                    </select>
                    <button onclick="updateCharts()">Update Chart</button>
                </div>

                <div class="pie-chart">
                    <div class="middle">
                        <div class="label">
                            <h3>Most Popular Item Categories </h3>
                            <h3 id="selectedPeriod1"></h3>
                            <h4>(Based on Total Quantity)</h4>
                        </div>
                        <div class="pie-chart-container">
                            <canvas id="categoryPieChart"></canvas>
                        </div>
                        <div id="chartLegend" class="custom-legend"></div> <!-- Legend container outside canvas -->
                    </div>
                </div>
            </div>


            <div class="graph">
                <h3>Item Sales Quantity by Period</h3>
                <h3 id="selectedPeriod2"></h3>
                <div class="bar-chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </main>

        <div class="right">
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp"> menu </span>
                </button>
                <div class="profile">
                    <div class="info">
                        <p>Hey, <b>Meow</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="../images/profile-1.jpg" alt="Profile Picture" />
                    </div>
                </div>
            </div>

            <!-- Side Notification -->
            <div class="notification-section">

                <h2>Notifications
                    <?php if (mysqli_num_rows($notiCount) > 0): ?>
                        <span class="message-count">
                            <?php echo mysqli_num_rows($notiCount); ?>
                        </span>
                    <?php endif; ?>
                </h2>

                <a href="../notification/notification.php">
                    <?php
                    if ($recentNoti && mysqli_num_rows($recentNoti) > 0) {
                        // Fetch notifications from the result set
                        while ($row = mysqli_fetch_assoc($recentNoti)) {
                            $noti = $row['noti'];
                            $date = $row['created_at'];
                            $type = $row['notification_type']; // e.g., 'success', 'error', 'warning'
                    ?>
                            <div class="item <?php echo $type; ?>">
                                <div class="icon">
                                    <span class="material-icons-sharp"><?php echo $type === 'alert' ? 'error' : ($type === 'warning' ? 'warning' : 'info'); ?></span>
                                </div>
                                <div class="message-content">
                                    <b><?php echo $noti; ?></b>
                                    <p><?php echo $date; ?></p> <!-- Date will appear on the next line -->
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        // If no notifications are found
                        echo '<div class="item info"><div class="icon"><span class="material-icons-sharp">info</span></div>No new notifications.</div>';
                    }
                    ?>
                </a>
            </div>
        </div>

        <!-- Display Category Data in a Pie Chart -->
        <script>
            let categoryPieChart;
            let salesChart; // Declare the variable for the sales chart instance

            function toggleInputFields() {
                const period = document.getElementById('salesPeriod').value;
                document.getElementById('specificDate').style.display = period == 'specific_date' ? 'block' : 'none';
                document.getElementById('specificMonth').style.display = period == 'specific_month' ? 'block' : 'none';
            }

            function updateCharts() {
                const period = document.getElementById('salesPeriod').value; // Get selected period
                const memberId = document.getElementById('memberSelect').value; // Get selected member ID
                let url = `analytics.php?period=${period}`;
                let selectedPeriodText = '';
                let specificDate = null;
                let specificMonth = null;

                console.log('Fetching data from:', url); // Debugging

                // Add specific date or month to URL if applicable
                if (period === 'specific_date') {
                    specificDate = document.getElementById('specificDate').value; // Get specific date
                    url += `&specific_date=${specificDate}`; // Append to URL
                    selectedPeriodText = `Sales on ${specificDate}`; // Update display text
                } else if (period === 'specific_month') {
                    specificMonth = document.getElementById('specificMonth').value; // Get specific month
                    url += `&specific_month=${specificMonth}`; // Append to URL
                    selectedPeriodText = `Sales in ${specificMonth}`; // Update display text
                } else {
                    selectedPeriodText = `Sales - ${period.charAt(0).toUpperCase() + period.slice(1)}`; // Format period text
                }

                if (memberId) {
                    url += `&Member_ID=${memberId}`;
                }

                // Set the selected period display text
                document.getElementById('selectedPeriod1').innerText = selectedPeriodText;
                document.getElementById('selectedPeriod2').innerText = selectedPeriodText;

                // Fetch data for both charts
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data received:', data); // Debugging


                        // Update Pie Chart for Category Quantities
                        const ctx1 = document.getElementById('categoryPieChart').getContext('2d');
                        if (categoryPieChart) {
                            categoryPieChart.destroy(); // Destroy previous chart instance if it exists
                        }

                        // Check if a member is selected
                        const selectedMemberName = document.getElementById('memberSelect').value ?
                            document.getElementById('memberSelect').selectedOptions[0].text :
                            '';

                        // Set the title based on whether a member is selected
                        const pieChartTitle = selectedMemberName ? `Sales by Member: ${selectedMemberName}` : 'Sales by Category';

                        categoryPieChart = new Chart(ctx1, {
                            type: 'pie',
                            data: {
                                labels: Object.keys(data.categoryQuantities),
                                datasets: [{
                                    label: 'Total Quantity by Category',
                                    data: Object.values(data.categoryQuantities).map(Number),
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
                                responsive: false,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false,
                                        position: 'right',
                                        labels: {
                                            font: {
                                                size: 14,
                                                family: 'Arial',
                                            },
                                            color: '#333',
                                            padding: 20,
                                            boxWidth: 20,
                                            boxHeight: 10,
                                        }
                                    },
                                    datalabels: {
                                        color: '#000',
                                        formatter: (value, context) => {
                                            const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                            const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
                                            return `${percentage}%`; // Display as a percentage
                                        },
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels, {
                                id: 'customLegend',
                                beforeRender: function(chart) {
                                    const quantities = chart.data.datasets[0].data;
                                    const legendHtml = chart.data.labels.map((label, index) => {
                                        return `<div style="display: flex; align-items: center;">
                                            <span style="background-color: ${chart.data.datasets[0].backgroundColor[index]}; 
                                                        width: 12px; height: 12px; display:inline-block; margin-right: 8px;"></span>
                                            ${label}: ${quantities[index]}
                                        </div>`;
                                    }).join("");
                                    document.getElementById('chartLegend').innerHTML = legendHtml;
                                }
                            }]
                        });



                        // Update Bar Chart for Top Items Sold
                        const ctx2 = document.getElementById('salesChart').getContext('2d');
                        if (salesChart) {
                            salesChart.destroy(); // Destroy previous chart instance if it exists
                        }

                        // Set the title for the bar chart similarly
                        const barChartTitle = selectedMemberName ? `Sales by Member: ${selectedMemberName}` : 'Top Sold Items';

                        salesChart = new Chart(ctx2, {
                            type: 'bar',
                            data: {
                                labels: data.topItems.map(item => item.item_name),
                                datasets: [{
                                    label: 'Quantity Sold',
                                    data: data.topItems.map(item => item.total_quantity),
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Quantity'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Items'
                                        }
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: barChartTitle
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Event listeners for dropdowns
            document.getElementById('salesPeriod').addEventListener('change', updateCharts);
            document.getElementById('memberSelect').addEventListener('change', updateCharts); // Update charts on member change

            // Load members on page load
            function loadMembers() {
                fetch('analytics.php?fetch_members=true')
                    .then(response => response.json())
                    .then(data => {
                        const memberSelect = document.getElementById('memberSelect');
                        memberSelect.innerHTML = ''; // Clear any existing options

                        // Re-add the placeholder option
                        const placeholderOption = document.createElement('option');
                        placeholderOption.value = '';
                        placeholderOption.textContent = 'Select a member';
                        //placeholderOption.disabled = true; // Make it unselectable
                        placeholderOption.selected = true; // Make it selected by default
                        memberSelect.appendChild(placeholderOption);

                        // Populate the member select with options from the fetched data
                        data.forEach(member => {
                            const option = document.createElement('option');
                            option.value = member.id;
                            option.textContent = member.name;
                            memberSelect.appendChild(option);
                        });

                        // Call to update charts after loading members
                        updateCharts();
                    })
                    .catch(error => console.error('Error fetching members:', error));
            }

            loadMembers(); // Call to load members on page load
        </script>
</body>

</html>