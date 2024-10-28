<?php
session_start(); // Start the session at the very top

require_once('../database/settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination setup
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page or set default to 1
$offset = ($page - 1) * $limit;

// Fetch all records if "show all" parameter is set
if (isset($_GET['show']) && $_GET['show'] === 'all') {
    $sql = "SELECT * FROM inventory";
    $show_all = true;
} else {
    $sql = "SELECT * FROM inventory LIMIT $limit OFFSET $offset";
    $show_all = false;
}

$result = $conn->query($sql);

// Get total number of records for pagination calculation
$sql_count = "SELECT COUNT(*) AS total FROM inventory";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_inventory = $row_count['total'];
$total_pages = ceil($total_inventory / $limit); // Total pages based on records

// Notification Count
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$result2 = mysqli_query($conn, $query2);
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
    <link rel="stylesheet" href="../styles/inventory.css" />
    <link rel="stylesheet" href="../styles/inventoryform.css" />
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

                <a href="../inventory/inventory.php" class="active">
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

                        <form method="get" action="">
                            <div class="search-row">

                                <div class="search-select-box">
                                    <p>Search by:</p>
                                    <select name="search-column" id="search-column">
                                        <option value="Item_ID">Item ID</option>
                                        <option value="Name">Item</option>
                                        <option value="Quantity">Quantity</option>
                                        <option value="Retail_Price">Retail Price</option>
                                        <option value="Selling_Price">Selling Price</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Category">Category</option>
                                        <option value="Brand">Brand</option>
                                        <option value="Reorder_Level">Reorder Level</option>
                                    </select>
                                </div>

                                <div class="search-container">
                                    <input type="text" name="search_bar" id="search_bar" maxlength="40" placeholder="Search" />
                                    <button type="submit"><span class="material-icons-sharp">search</span></button>
                                </div>
                            </div>
                        </form>

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
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Retail Price</th>
                                <th>Selling Price</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Reorder Level</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_GET['search_bar'])) {
                                // Retrieve the search term from the form and sanitize it
                                $search_term = $conn->real_escape_string($_GET['search_bar']);

                                // Retrieve the selected search column
                                $search_column = $_GET['search-column'];

                                // Construct the SQL query based on the selected column
                                $sql_search = "SELECT * FROM inventory WHERE $search_column LIKE '%$search_term%'";

                                $result_search = $conn->query($sql_search);

                                // Check if any results were found
                                if ($result_search->num_rows > 0) {
                                    while ($row = $result_search->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Item_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Retail_Price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Selling_Price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Supplier']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Category']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Brand']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Reorder_Level']) . "</td>";
                                        echo "<td><a class='edit-inventory' onclick=\"requestInventoryInfo(this)\" data-inventory-id='" . $row["Item_ID"] . "'>Edit</a></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "No results found.";
                                }
                            } else {
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
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div id="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>" class="pagination-prev-btn"><span class="material-icons-sharp">arrow_back_ios</span></a>
                        <?php endif; ?>

                        Page
                        <?php echo $page; ?> of
                        <?php
                        // If there are no pages, display "1" as the default total
                        echo ($total_pages < 1) ? "1" : $total_pages;
                        ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="pagination-next-btn"><span class="material-icons-sharp">arrow_forward_ios</span></a>
                        <?php endif; ?>
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
        </div>

        <!-- Modal Overlay (Form is moved outside the container) -->
        <div class="add-inventory-modal-overlay"></div>

        <!-- Registration Form in Modal -->
        <div class="add-inventory-modal">
            <div class="inventory-form-container">
                <!--Add Members Form-->
                <h1>Add Inventory</h1>
                <form id="add" method="post" action="./Inventory_Add.php" class="inventory-form" novalidate="novalidate">
                    <div class="column">
                        <div class="input-box">
                            <label>Item</label>
                            <input type="text" name="name" id="name" maxlength="100" pattern="^[a-zA-Z ]+$" placeholder="Enter Item Name" required />
                            <section id="name_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="quantity" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Quantity" required />
                            <section id="quantity_error" class="error"></section>
                        </div>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Retail Price</label>
                            <input type="number" step="0.01" name="retail_price" id="retail_price" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Retail Price" required />
                            <section id="retail_price_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Selling Price</label>
                            <input type="number" step="0.01" name="selling_price" id="selling_price" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Selling Price" required />
                            <section id="selling_price_error" class="error"></section>
                        </div>
                    </div>

                    <div class="input-box">
                        <label>Supplier</label>
                        <div class="addsupplier-box">
                        <input list="supplier" id="supplier_input" name="supplier_input" placeholder="Enter or Select Supplier" value="" autocomplete="off" required >
                            <datalist name="supplier" id="supplier" required>
                                <?php
                                    $sql = "SELECT DISTINCT Supplier FROM inventory";

                                    $result = $conn->query($sql);

                                    // Check if any results were found
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value=\"" . $row['Supplier'] . "\">";
                                        }
                                    } else {
                                        echo "No results found.";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <section id="supplier_error" class="error"></section>
                    </div>

                    <div class="input-box">
                        <label>Category</label>
                        <div class="addcategory-box">
                        <input list="category" id="category_input" name="category_input" placeholder="Enter or Select Category" value="" autocomplete="off" required >
                            <datalist name="category" id="category" required>
                                <?php
                                    $sql = "SELECT DISTINCT Category FROM inventory";

                                    $result = $conn->query($sql);

                                    // Check if any results were found
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value=\"" . $row['Category'] . "\">";
                                        }
                                    } else {
                                        echo "No results found.";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <section id="category_error" class="error"></section>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Brand Name</label>
                            <input type="text" name="brand" id="brand" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Enter Brand Name" required />
                            <section id="brand_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder" id="reorder" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Reorder Level" required />
                            <section id="reorder_error" class="error"></section>
                        </div>
                    </div>
                    <button>Add Inventory</button>
                </form>
            </div>
        </div>

        <div class="edit-inventory-modal-overlay"></div>
        <!-- Edit Inventory Modal -->

        <div class="edit-inventory-modal">
            <div class="inventory-form-container">
                <header>Edit Inventory</header>
                <form id="edit" method="post" action="./Inventory_Edit.php" class="inventory-form" novalidate="novalidate">
                    <input type="hidden" name="Item_ID" id="editItemID">
                    <div class="column">
                        <div class="input-box">
                            <label>Item</label>
                            <input type="text" name="name_edit" id="name_edit" maxlength="100" pattern="^[a-zA-Z ]+$" placeholder="Enter Item Name" required />
                            <section id="name_edit_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Quantity</label>
                            <input type="number" name="quantity_edit" id="quantity_edit" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Quantity" required />
                            <section id="quantity_edit_error" class="error"></section>
                        </div>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Retail Price</label>
                            <input type="number" step="0.01" name="retail_price_edit" id="retail_price_edit" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Retail Price" required />
                            <section id="retail_price_edit_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Selling Price</label>
                            <input type="number" step="0.01" name="selling_price_edit" id="selling_price_edit" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Selling Price" required />
                            <section id="selling_price_edit_error" class="error"></section>
                        </div>
                    </div>

                    <div class="input-box">
                        <label>Supplier</label>
                        <div class="editsupplier-box">
                        <input list="supplier" id="supplier_input_edit" name="supplier_input_edit" placeholder="Enter or Select Supplier" value="" autocomplete="off" required >
                            <datalist name="supplier_edit" id="supplier_edit" required>
                                <?php
                                    $sql = "SELECT DISTINCT Supplier FROM inventory";

                                    $result = $conn->query($sql);

                                    // Check if any results were found
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value=\"" . $row['Supplier'] . "\">";
                                        }
                                    } else {
                                        echo "No results found.";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <section id="supplier_edit_error" class="error"></section>
                    </div>

                    <div class="input-box">
                        <label>Category</label>
                        <div class="editcategory-box">
                        <input list="category" id="category_input_edit" name="category_input_edit" placeholder="Enter or Select Category" autocomplete="off" required >
                            <datalist name="category_edit" id="category_edit" required>
                                <?php
                                    $sql = "SELECT DISTINCT Category FROM inventory";

                                    $result = $conn->query($sql);

                                    // Check if any results were found
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value=\"" . $row['Category'] . "\">";
                                        }
                                    } else {
                                        echo "No results found.";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <section id="category_edit_error" class="error"></section>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Brand Name</label>
                            <input type="text" name="brand_edit" id="brand_edit" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Enter Brand Name" required />
                            <section id="brand_edit_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder_edit" id="reorder_edit" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Reorder Level" required />
                            <section id="reorder_edit_error" class="error"></section>
                        </div>
                    </div>
                    <button>Save Changes</button>
                </form>
            </div>
        </div>

        <script src="../index/index.js"></script>
        <script src="./inventory.js"></script>
        <script src="./inventoryform.js"></script>

        <script>
            // JavaScript function to send AJAX request to PHP
            function requestInventoryInfo(record) {
                // Make a GET request to the PHP script using fetch()
                fetch('./request_inventory.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'text/plain', // Specify plain text format
                        },
                        body: record.getAttribute('data-inventory-id')
                    })
                    .then(response => response.json()) // Expect a json response
                    .then(data => {
                        if (data.error) {
                            console.log("error"); // Debugging
                        } else {
                            // Fill in the form fields with data
                            document.getElementById('name_edit').value = data.Name;
                            document.getElementById('quantity_edit').value = data.Quantity;
                            document.getElementById('retail_price_edit').value = data.Retail_Price;
                            document.getElementById('selling_price_edit').value = data.Selling_Price;
                            document.getElementById('supplier_input_edit').value = data.Supplier;
                            document.getElementById('category_input_edit').value = data.Category;
                            document.getElementById('brand_edit').value = data.Brand;
                            document.getElementById('reorder_edit').value = data.Reorder_Level;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        </script>
    </div>
</body>

</html>

<?php
$conn->close();  // Close the connection
?>