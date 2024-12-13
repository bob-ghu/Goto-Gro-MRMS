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

$limit = 8; // Number of notifications per page

// Query to get the total number of notifications for pagination calculations
$sql_count = "SELECT COUNT(*) AS total FROM notifications";
$count_result = mysqli_query($conn, $sql_count);
$row_count = $count_result->fetch_assoc();
$total_notifications = $row_count['total'];
$total_pages = ceil($total_notifications / $limit);

if (isset($_POST['page_input']) && $_POST['page_input'] <= $total_pages) {
    $page = $_POST['page_input'];
} else {
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
}
$offset = ($page - 1) * $limit;

// Modify SQL query to include pagination
$showlimit = "SELECT created_at, noti, message, notification_type FROM notifications ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result2 = mysqli_query($conn, $showlimit);

// Query to fetch unread notifications
$query = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$result = mysqli_query($conn, $query);

// Fetch unread notifications and mark as read
$notifications = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    $update_sql = "UPDATE notifications SET is_read = 1 WHERE is_read = 0";
    mysqli_query($conn, $update_sql);
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
    <link rel="stylesheet" href="../styles/notification.css" />
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

                <a href="../notification/notification.php" class="active">
                    <span class="material-icons-sharp"> notifications </span>
                    <h3>Notifications</h3>
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
            <h1>Notifications</h1>
            <div class="notification-detail">
                <table id="notification-detail--table">
                    <thead>
                        <tr>
                            <th>Notification</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="notification-section">
                            <?php
                            if ($result2 && mysqli_num_rows($result2) > 0) {
                                // Fetch notifications from the result set
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $noti = $row['noti'];
                                    $message = $row['message'];
                                    $type = $row['notification_type'];
                                    $date = $row['created_at'];
                                    echo "<tr>";
                                    echo "<td class='notification-column'>";
                                    echo "<div class='notification-wrapper'>";
                                    // Output each notification with the appropriate class
                                    echo "<div class='notification-item $type'>";
                                    echo "<div class='notification-content'>";
                                    echo "<div class='icon'>";
                                    echo "<span class='material-icons-sharp'>" . ($type === 'alert' ? 'error' : ($type === 'warning' ? 'warning' : 'info')) . "</span>";
                                    echo "</div>";
                                    echo "<div class='message-text'>";
                                    echo "<p>$noti $message</p>";
                                    echo "</div>";
                                    echo "</div>"; // Close notification-content
                                    echo "</div>"; // Close notification-item
                                    echo "</div>"; // Close notification-wrapper
                                    echo "</td>";
                                    echo "<td class='notification-date'>";
                                    echo "<div class='date-wrapper'>";
                                    echo "$date";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<div class='notification-item info'>";
                                echo "<div class='icon'><span class='material-icons-sharp'>info</span></div>";
                                echo "<p>No new notifications.</p>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <div id="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="pagination-prev-btn"><span class="material-icons-sharp">arrow_back_ios</span></a>
                    <?php endif; ?>

                    Page
                    <?php echo "<form id=\"page-form\" method=\"post\" action=\"./notification.php\" class=\"page-form\" novalidate=\"novalidate\">
                                        <input type=\"text\" name=\"page_input\" id=\"page_input\" size=\"1\" value=" . $page . " /> 
                                    </form>"; ?> of
                    <?php
                    // If there are no pages, display "1" as the default total
                    echo ($total_pages < 1) ? "1" : $total_pages;
                    ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="pagination-next-btn"><span class="material-icons-sharp">arrow_forward_ios</span></a>
                    <?php endif; ?>
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
                                        } else {
                                            echo $rank;
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
        <script src="../index/index.js"></script>
    </div>
</body>

</html>