<?php
session_start(); // Start the session at the very top

require_once('settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch member details
$limit = 10;
// Check if the "show all" parameter is set
if (isset($_GET['show']) && $_GET['show'] === 'all') {
    // SQL query to fetch all member details
    $sql = "SELECT * FROM inventory";
    $show_all = true;
} else {
    // SQL query to fetch limited member details
    $sql = "SELECT * FROM inventory LIMIT $limit";
    $show_all = false;
}

$result = $conn->query($sql);

$sql_count = "SELECT COUNT(*) AS total FROM inventory";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_inventory = $row_count['total'];

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
    <link rel="stylesheet" href="./inventory.css" />
    <link rel="stylesheet" href="./inventoryform.css" />
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
                <a href="index.php">
                    <span class="material-icons-sharp"> dashboard </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="members.php">
                    <span class="material-icons-sharp"> person_outline </span>
                    <h3>Members</h3>
                </a>

                <a href="inventory.php" class="active">
                    <span class="material-icons-sharp"> inventory_2 </span>
                    <h3>Inventory </h3>
                </a>

                <a href="sales.php">
                    <span class="material-icons-sharp"> receipt_long </span>
                    <h3>Sales</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp"> notifications </span>
                    <h3>Notifications</h3>
                    <span class="message-count">26</span>
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
            <h1>Inventory</h1>

            <div class="insights">
                <!----- Start Tab 1 ----->
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
                <!----- End Tab 1 ----->

                <!----- Start Tab 2 ----->
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
                <!----- End Tab 2 ----->

                <!----- Start Tab 3 ----->
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
                <!----- End Tab 3 ----->
            </div>

            <div class="inventory-container">
                <!--Add Members Form-->
                <div class="inventory-detail">
                    <div class="inventory-detail-header">
                        <h2>Inventory's Detail</h2>

                        <div class="add-inventory">
                            <div>
                                <span class="material-icons-sharp">add</span>
                                <h3>Add Inventory</h3>
                            </div>
                        </div>
                    </div>


                    <table id="inventory-detail--table">
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Retail Price</th>
                                <th>Selling Price</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Reorder_Level</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["Item_ID"] . "</td>";
                                    echo "<td>" . $row["Name"] . "</td>";
                                    echo "<td>" . $row["Quantity"] . "</td>";
                                    echo "<td>" . $row["Retail_Price"] . "</td>";
                                    echo "<td>" . $row["Selling_Price"] . "</td>";
                                    echo "<td>" . $row["Supplier"] . "</td>";
                                    echo "<td>" . $row["Category"] . "</td>";
                                    echo "<td>" . $row["Brand"] . "</td>";
                                    echo "<td>" . $row["Reorder_Level"] . "</td>";
                                    echo "<td><a class='edit-inventory' onclick=\"requestInventoryInfo(this)\" data-inventory-id='" . $row["Item_ID"] . "'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if ($total_inventory > $limit): ?>
                        <?php if ($show_all): ?>
                            <a href="inventory.php" class="show-all">Show Less</a>
                        <?php else: ?>
                            <a href="inventory.php?show=all" class="show-all">Show All</a>
                        <?php endif; ?>
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
                        <p>Hey, <b>Meow</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="./images/profile-1.jpg" alt="Profile Picture" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Overlay (Form is moved outside the container) -->
        <div class="add-inventory-modal-overlay"></div>

        <!-- Registration Form in Modal -->
        <div class="add-inventory-modal">
            <div class="inventory-form-container">
                <!--Add Members Form-->
                <h1>Add Inventory</h1>
            </div>
        </div>

        <div class="edit-inventory-modal-overlay"></div>
        <!-- Edit Member Modal -->

        <div class="edit-inventory-modal">
            <div class="inventory-form-container">
                <header>Edit Inventory</header>
            </div>
        </div>

        <script src="./index.js"></script>
        <script src="form.js"></script>
    </div>
</body>

</html>

<?php
$conn->close();  // Close the connection
?>