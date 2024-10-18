<?php
require_once 'database_check.php';

require_once('settings.php'); // Include your database settings

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

//Query to fetch recent sales
$query4 = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$result3 = mysqli_query($conn, $query4);

$salesQuery = "SELECT members.Member_ID, members.Full_Name, inventory.Item_ID, inventory.Name, sales.Quantity, sales.Sale_Date, sales.Sales_ID
               FROM sales 
               JOIN members ON sales.Member_ID = members.Member_ID
               JOIN inventory ON sales.Item_ID = inventory.Item_ID
               ORDER BY Sale_Date DESC LIMIT 3";

$salesResult = $conn->query($salesQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Goto Gro MRMS</title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
  <div class="container">
    <aside>
      <div class="top">
        <div class="logo">
          <img src="./images/logo.png" alt="Logo" />
          <h2>Goto<span class="danger">Gro</span></h2>
        </div>
        <div class="close" id="close-btn">
          <span class="material-icons-sharp"> close </span>
        </div>
      </div>

      <div class="sidebar">
        <a href="index.php" class="active">
          <span class="material-icons-sharp"> dashboard </span>
          <h3>Dashboard</h3>
        </a>
        <a href="members.php">
          <span class="material-icons-sharp"> person_outline </span>
          <h3>Members</h3>
        </a>

        <a href="inventory.php">
          <span class="material-icons-sharp"> inventory_2 </span>
          <h3>Inventory </h3>
        </a>

        <a href="sales.php">
          <span class="material-icons-sharp"> receipt_long </span>
          <h3>Sales</h3>
        </a>

        <a href="notification.php">
          <span class="material-icons-sharp"> notifications </span>
          <h3>Notifications</h3>
          <?php if (mysqli_num_rows($notiCount) > 0): ?>
            <span class="message-count">
              <?php echo mysqli_num_rows($notiCount); ?>
            </span>
          <?php endif; ?>
        </a>
        <a href="#">
          <span class="material-icons-sharp"> insights </span>
          <h3>Analytics</h3>
        </a>
        <a href="#">
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
      <h1>Dashboard</h1>

      <div class="insights">
        <!-- SALES -->
        <div class="sales">
          <span class="material-icons-sharp"> analytics </span>
          <div class="middle">
            <div class="left">
              <h3>Total Sales</h3>
              <h1>$25,024</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>81%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
        </div>

        <!-- EXPENSES -->
        <div class="expenses">
          <span class="material-icons-sharp"> bar_chart </span>
          <div class="middle">
            <div class="left">
              <h3>Total Expenses</h3>
              <h1>$14,160</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>62%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
        </div>

        <!-- INCOME -->
        <div class="income">
          <span class="material-icons-sharp"> stacked_line_chart </span>
          <div class="middle">
            <div class="left">
              <h3>Total Income</h3>
              <h1>$10,864</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>44%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
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
            <img src="./images/profile-1.jpg" alt="Profile Picture" />
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

        <a href="notification.php">
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

      <!-- Side Recent Sales -->
      <div class="recent-sales">
        <h2>Recent Sales</h2>
        <?php if ($salesResult && $salesResult->num_rows > 0): ?>
          <?php while ($row = $salesResult->fetch_assoc()): ?>
            <div class="updates">
              <a href="sales.php?Sales_ID=<?php echo $row['Sales_ID']; ?>">
                <div class="message">
                  <b><?php echo htmlspecialchars($row['Full_Name']); ?></b><br>
                  <span><?php echo htmlspecialchars($row['Name']) . ' ' . htmlspecialchars($row['Quantity']); ?></span><br>
                  <br><small class="text-muted"><?php echo htmlspecialchars($row['Sale_Date']); ?></small>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No recent sales available.</p>
        <?php endif; ?>
      </div>

      <script src="./index.js"></script>
    </div>
</body>

</html>