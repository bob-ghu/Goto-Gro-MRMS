<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: ../login/login.php');
  exit;
}

require_once('../database/settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Query to fetch unread notifications from the database
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$result2 = mysqli_query($conn, $query2);
date_default_timezone_set('Asia/Kuala_Lumpur');
// Fetch data based on selected time period (daily, monthly, yearly)
$timePeriod = isset($_GET['timePeriod']) ? $_GET['timePeriod'] : 'daily';
$date = date('Y-m-d');
$month = date('m');
$year = date('Y');

switch ($timePeriod) {
  case 'daily':
    // Fetch hourly sales, revenue, and profit for the current day
    $query = "
      SELECT HOUR(Sale_Date) AS hour, COUNT(*) AS sales_count, SUM(Total_Price) AS revenue,
             SUM((i.Selling_Price - i.Retail_Price) * s.Quantity) AS profit
      FROM sales s
      JOIN inventory i ON s.Item_ID = i.Item_ID
      WHERE DATE(Sale_Date) = '$date'
      GROUP BY hour
      ORDER BY hour";
    break;

  case 'monthly':
    // Fetch daily sales, revenue, and profit for the current month
    $query = "
      SELECT DAY(Sale_Date) AS day, COUNT(*) AS sales_count, SUM(Total_Price) AS revenue,
             SUM((i.Selling_Price - i.Retail_Price) * s.Quantity) AS profit
      FROM sales s
      JOIN inventory i ON s.Item_ID = i.Item_ID
      WHERE MONTH(Sale_Date) = '$month' AND YEAR(Sale_Date) = '$year'
      GROUP BY day
      ORDER BY day";
    break;

  case 'yearly':
  default:
    // Fetch monthly sales, revenue, and profit for the current year
    $query = "
      SELECT MONTH(Sale_Date) AS month, COUNT(*) AS sales_count, SUM(Total_Price) AS revenue,
             SUM((i.Selling_Price - i.Retail_Price) * s.Quantity) AS profit
      FROM sales s
      JOIN inventory i ON s.Item_ID = i.Item_ID
      WHERE YEAR(Sale_Date) = '$year'
      GROUP BY month
      ORDER BY month";
    break;
}

$result = $conn->query($query);

// Initialize arrays to store data
$salesData = array_fill(0, ($timePeriod == 'yearly' ? 12 : ($timePeriod == 'monthly' ? 31 : 24)), 0);
$revenueData = $salesData;
$profitData = $salesData;

// Populate arrays with query results
while ($row = $result->fetch_assoc()) {
  $index = $timePeriod === 'daily' ? (int)$row['hour'] : (int)$row[($timePeriod === 'yearly' ? 'month' : 'day')] - 1;
  $salesData[$index] = (int)$row['sales_count'];
  $revenueData[$index] = (float)$row['revenue'];
  $profitData[$index] = (float)$row['profit'];
}

// Get the current year
$year = date('Y');

// Query to get the top 5 most popular items based on the total quantity sold in the current year
$popularItemQuery = "
    SELECT i.Name, SUM(s.Quantity) AS total_sold
    FROM sales s
    JOIN inventory i ON s.Item_ID = i.Item_ID
    WHERE YEAR(Sale_Date) = '$year'
    GROUP BY s.Item_ID
    ORDER BY total_sold DESC
    LIMIT 3";  // Adjust the limit if you want more or fewer items

$popularItemResult = $conn->query($popularItemQuery);

// Query to count the total number of staff members
$staffQuery = "SELECT COUNT(*) AS total_staff FROM Staff";
$staffResult = $conn->query($staffQuery);
$totalStaff = 0;

if ($staffResult && $staffResult->num_rows > 0) {
  $row = $staffResult->fetch_assoc();
  $totalStaff = $row['total_staff'];
}

// Query to count total number of visits
$visitQuery = "SELECT COUNT(*) AS total_visits FROM LoginHistory";
$visitResult = $conn->query($visitQuery);
$totalVisits = 0;

if ($visitResult && $visitResult->num_rows > 0) {
  $row = $visitResult->fetch_assoc();
  $totalVisits = $row['total_visits'];
}
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
  <link rel="stylesheet" href="../styles/index.css" />
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
        <a href="../index/index.php" class="active">
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
        <a href="../analytics/analytics.php">
          <span class="material-icons-sharp"> insights </span>
          <h3>Analytics</h3>
        </a>
        <a href="../feedback/feedback.php">
          <span class="material-icons-sharp"> feedback </span>
          <h3>Feedback</h3>
        </a>
        <a href="../login/logout.php">
          <span class="material-icons-sharp"> logout </span>
          <h3>Logout</h3>
        </a>
      </div>
    </aside>

    <main>
      <h1>Dashboard</h1>

      <div class="insights">
        <!-- TOTAL STAFF -->
        <div class="total-staff">
          <span class="material-icons-sharp">group</span> <!-- Icon for staff -->
          <div class="middle">
            <div class="left">
              <h3>Total Staff</h3>
              <h1><?php echo $totalStaff; ?></h1> <!-- Display the total staff count -->
            </div>
          </div>
          <small class="text-muted">Human resources overview</small>
        </div>

        <!-- TOTAL VISITS -->
        <div class="total-visits">
          <span class="material-icons-sharp">visibility</span> <!-- Icon for visits -->
          <div class="middle">
            <div class="left">
              <h3>Total Visits</h3>
              <h1><?php echo $totalVisits; ?></h1> <!-- Display the total visits count -->
            </div>
          </div>
          <small class="text-muted">User activity overview</small>
        </div>

      </div>

      <div class="middle-section">
        <div class="sales-profit">
          <h2>Sales & Profit</h2>
          <div class="graph">
            <label for="timePeriod">Select Time Period: </label>
            <select id="timePeriod" onchange="updateTimePeriod()">
              <option value="daily" <?php echo $timePeriod == 'daily' ? 'selected' : ''; ?>>Daily</option>
              <option value="monthly" <?php echo $timePeriod == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
              <option value="yearly" <?php echo $timePeriod == 'yearly' ? 'selected' : ''; ?>>Yearly</option>
            </select>

            <div class="chart-container" style="width: 100%; height: 400px;">
              <canvas id="salesRevenueProfitChart"></canvas>
            </div>
          </div>
        </div>

        <div class="recent-logins-section">
          <h2>Recent Logins</h2>
          <div class="recent-logins-list">
            <?php
            // Assuming you have a database connection established as $conn

            // Fetch recent logins from the LoginHistory table ordered by the most recent
            $query = "SELECT lh.Staff_ID, s.Full_Name, lh.LoginTime 
                  FROM loginhistory lh
                  JOIN staff s ON lh.Staff_ID = s.Staff_ID
                  ORDER BY lh.LoginTime DESC 
                  LIMIT 7"; // Limit to show the last 10 logins

            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              echo "<table>";
              echo "<tr>";
              echo "<th>Staff ID</th>";
              echo "<th>Full Name</th>";
              echo "<th>Login Time</th>";
              echo "</tr>";

              // Output data of each row
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Staff_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Full_Name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['LoginTime']) . "</td>";
                echo "</tr>";
              }

              echo "</table>";
            } else {
              echo "<p>No recent logins found.</p>";
            }
            ?>
          </div>
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
            <p>Hey, <b><?php echo $_SESSION["Username"] ?></b></p>
            <small class="text-muted"><?php echo $_SESSION["Role"] ?></small>
          </div>
          <div class="profile-photo">
            <img src="<?php echo $_SESSION["Picture"] ?>" alt="Profile Picture" />
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
          if ($result2 && mysqli_num_rows($result2) > 0) {
            // Fetch notifications from the result set
            while ($row = mysqli_fetch_assoc($result2)) {
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

      <div class="popular-item-table">
        <h2>Top Selling Items This Year</h2>
        <table>
          <thead>
            <tr>
              <th>Rank</th> <!-- New column for ranking -->
              <th>Item</th>
              <th>Quantity Sold</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($popularItemResult && $popularItemResult->num_rows > 0):
              $rank = 1; // Initialize a counter for ranking
              while ($row = $popularItemResult->fetch_assoc()):
            ?>
                <tr>
                  <td data-label="Rank">
                    <?php

                    // Add a crown for top 3 items
                    if ($rank == 1) {
                      echo '<span class="mdi--crown gold"></span>'; // Crown emoji for rank 1
                    } elseif ($rank == 2) {
                      echo '<span class="mdi--crown silver"></span>'; // Crown emoji for rank 2
                    } elseif ($rank == 3) {
                      echo '<span class="mdi--crown bronze"></span>'; // Crown emoji for rank 3
                    }
                    ?>
                  </td>
                  <td data-label="Item"><?php echo htmlspecialchars($row['Name']); ?></td>
                  <td data-label="Quantity Sold"><?php echo htmlspecialchars($row['total_sold']); ?></td>
                </tr>
                <?php $rank++; // Increment the rank 
                ?>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3">No sales data available for this year.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>



    <script src="./index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
      const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const daysInMonth = Array.from({
        length: 31
      }, (_, i) => i + 1);
      const hoursInDay = Array.from({
        length: 24
      }, (_, i) => i);

      function getLabels(timePeriod) {
        if (timePeriod === 'yearly') return months;
        if (timePeriod === 'monthly') return daysInMonth;
        return hoursInDay;
      }

      // Data from PHP
      const timePeriod = "<?php echo $timePeriod; ?>";
      const salesData = <?php echo json_encode(array_values($salesData)); ?>;
      const revenueData = <?php echo json_encode(array_values($revenueData)); ?>;
      const profitData = <?php echo json_encode(array_values($profitData)); ?>;

      // Initialize the Chart
      const ctx = document.getElementById("salesRevenueProfitChart").getContext("2d");
      let salesRevenueProfitChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: getLabels(timePeriod),
          datasets: [{
              label: "Sales Count",
              data: salesData,
              borderColor: "rgba(255, 99, 132, 1)",
              backgroundColor: "rgba(255, 99, 132, 0.2)",
              fill: true,
              tension: 0.3
            },
            {
              label: "Revenue ($)",
              data: revenueData,
              borderColor: "rgba(75, 192, 192, 1)",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              fill: true,
              tension: 0.3
            },
            {
              label: "Profit ($)",
              data: profitData,
              borderColor: "rgba(153, 102, 255, 1)",
              backgroundColor: "rgba(153, 102, 255, 0.2)",
              fill: true,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "top"
            },
            tooltip: {
              mode: "index",
              intersect: false
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: timePeriod.charAt(0).toUpperCase() + timePeriod.slice(1)
              }
            },
            y: {
              title: {
                display: true,
                text: "Amount"
              },
              beginAtZero: true
            }
          }
        }
      });

      function updateTimePeriod() {
        const selectedTimePeriod = document.getElementById("timePeriod").value;
        window.location.href = `index.php?timePeriod=${selectedTimePeriod}`;
      }
    </script>
</body>

</html>