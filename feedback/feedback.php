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

// Notification count
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$recentNoti = mysqli_query($conn, $query2);

//feedback query:
$feedbackQuery = "SELECT * FROM feedback ORDER BY created_at DESC"; // Adjust if needed
$feedbackResult = mysqli_query($conn, $feedbackQuery);

// Check if the feedback query was successful
if (!$feedbackResult) {
    die("Error fetching feedback: " . mysqli_error($conn));
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

// Close the database connection
$conn->close();
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
    <link rel="stylesheet" href="../styles/feedback.css" />
    <link rel="stylesheet" href="../styles/feedbackform.css" />
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
                <a href="../analytics/analytics.php">
                    <span class="material-icons-sharp"> insights </span>
                    <h3>Analytics</h3>
                </a>
                <a href="../Feedback/Feedback.php" class="active">
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
            <h1>Feedback</h1>

            <div class="feedback-container">
                <!--member add feedback-->
                <div class="feedback-detail">
                    <div class="feedback-detail-header">
                        <h2>Feedback Record</h2>
                        <div class="add-feedback">
                            <div>
                                <span class="material-icons-sharp">add</span>
                                <h3>Feedback</h3>
                            </div>
                        </div>
                    </div>

                    <table id="feedback-detail--table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Feedback Type</th>
                                <th>Comments</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch each feedback record and display it
                            while ($row = mysqli_fetch_assoc($feedbackResult)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['feedback_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['feedback_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['comments']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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
        <!-- Modal Overlay (Form is moved outside the container) -->
        <div class="add-feedback-modal-overlay"></div>

        <div class="add-feedback-modal">
            <div class="feedback-form-container">

                <h1>Feedback</h1>
                <form id="add" method="post" action="./feedback_add.php" class="feedback-form" novalidate="novalidate">
                    <div class="column">
                        <div class="input-box">
                            <label>Full name</label>
                            <input type="text" name="name" id="name" maxlength="100" pattern="^[a-zA-Z ]+$" placeholder="Enter name" required />
                            <section id="name_error" class="error"></section>
                        </div>

                        <div class="input-box">
                            <label>Email Address</label>
                            <input type="text" name="email" id="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                                placeholder="Example: name@domain.com" required />
                            <section id="email_error" class="error"></section>
                        </div>
                    </div>

                    <div class="input-box">
                        <label>Feedback Type</label>
                        <div class="select-box addfeedbacktype-box">
                            <select name="feedback_type" id="feedback_type" required>
                                <option value="">Select Feedback Type</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="complaint">Complaint</option>
                                <option value="compliment">Compliment</option>
                            </select>
                        </div>
                        <section id="feedback_type_error" class="error"></section>
                    </div>

                    <div class="input-box">
                        <label>Comments</label>
                        <textarea name="comments" id="comments" placeholder="Enter your comments here..." required></textarea>
                        <section id="comments_error" class="error"></section>
                    </div>
                    <button>Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../index/index.js"></script>
    <script src="./feedback.js"></script>
    <script src="./feedbackform.js"></script>

</body>

</html>

<?php
$conn->close();  // Close the connection
?>