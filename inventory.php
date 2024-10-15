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
                                <th>Reorder Level</th>
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
                <form id="add" method="post" action="Inventory_Add.php" class="inventory-form" novalidate="novalidate">
                    <div class="column">
                        <div class="input-box">
                            <label>Name</label>
                            <input type="text" name="name" id="name" maxlength="100" pattern="^[a-zA-Z ]+$" placeholder="Enter Stock Name" required />
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
                        <input type="text" name="supplier" id="supplier" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Enter Supplier's Name" required />
                        <section id="supplier_error" class="error"></section>
                    </div>

                    <div class="input-box">
                        <label>Category</label>
                        <div class="select-box">
                            <select name="category" id="category" required>
                                <option hidden value="">Select Category</option>
                                <option value="fruits_and_vegetables">Fruits and Vegetables</option>
                                <option value="meat_and_poultry">Meat and Poultry</option>
                                <option value="seafood">Seafood</option>
                                <option value="beverages">Beverages</option>
                                <option value="snacks">Snacks</option>
                                <option value="frozen_foods">Frozen Foods</option>
                                <option value="bakery">Bakery</option>
                                <option value="canned_goods">Canned Goods</option>
                                <option value="dry_goods">Dry Goods</option>
                                <option value="personal_care">Personal Care</option>
                                <option value="household_supplies">Household Supplies</option>
                                <option value="condiments_and_spices">Condiments and Spices</option>
                            </select>
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
                <form id="add" method="post" action="Inventory_Edit.php" class="inventory-form" novalidate="novalidate">
                <input type= "hidden" name="Item_ID" id="editItemID">
                    <div class="column">
                        <div class="input-box">
                            <label>Name</label>
                            <input type="text" name="name_edit" id="name_edit" maxlength="100" pattern="^[a-zA-Z ]+$" placeholder="Enter Stock Name" required />
                            <section id="name_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Quantity</label>
                            <input type="number" name="quantity_edit" id="quantity_edit" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Quantity" required />
                            <section id="quantity_error" class="error"></section>
                        </div>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Retail Price</label>
                            <input type="number" step="0.01" name="retail_price_edit" id="retail_price_edit" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Retail Price" required />
                            <section id="retail_price_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Selling Price</label>
                            <input type="number" step="0.01" name="selling_price_edit" id="selling_price_edit" maxlength="11" size="11" pattern="^\d{1,8}(\.\d{2})?$" placeholder="Enter Selling Price" required />
                            <section id="selling_price_error" class="error"></section>
                        </div>
                    </div>

                    <div class="input-box">
                        <label>Supplier</label>
                        <input type="text" name="supplier_edit" id="supplier_edit" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Enter Supplier's Name" required />
                        <section id="supplier_error" class="error"></section>
                    </div>

                    <div class="input-box">
                        <label>Category</label>
                        <div class="select-box">
                            <select name="category_edit" id="category_edit" required>
                                <option hidden value="">Select Category</option>
                                <option value="fruits_and_vegetables">Fruits and Vegetables</option>
                                <option value="meat_and_poultry">Meat and Poultry</option>
                                <option value="seafood">Seafood</option>
                                <option value="beverages">Beverages</option>
                                <option value="dairy">Dairy</option>
                                <option value="snacks">Snacks</option>
                                <option value="frozen_foods">Frozen Foods</option>
                                <option value="bakery">Bakery</option>
                                <option value="canned_goods">Canned Goods</option>
                                <option value="dry_goods">Dry Goods</option>
                                <option value="personal_care">Personal Care</option>
                                <option value="household_supplies">Household Supplies</option>
                                <option value="condiments_and_spices">Condiments and Spices</option>
                            </select>
                        </div>
                        <section id="category_error" class="error"></section>
                    </div>

                    <div class="column">
                        <div class="input-box">
                            <label>Brand Name</label>
                            <input type="text" name="brand_edit" id="brand_edit" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Enter Brand Name" required />
                            <section id="brandname_error" class="error"></section>
                        </div>
                        <div class="input-box">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder_edit" id="reorder_edit" maxlength="5" size="5" pattern="\d{1,5}" placeholder="Enter Reorder Level" required />
                            <section id="reorder_error" class="error"></section>
                        </div>
                    </div>
                    <button>Save Changes</button>
                </form>
            </div>
        </div>

        <script src="./index.js"></script>
        <script src="inventory.js"></script>
        <script src="inventoryform.js"></script>

        <script>
        // JavaScript function to send AJAX request to PHP
        function requestInventoryInfo(record) {
            // Make a GET request to the PHP script using fetch()
            fetch('request_inventory.php', {
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
                        const excludedCategory = data.Category;
                        const categories = [
                            "Fruits and Vegetables",
                            "Meat and Poultry",
                            "Seafood",
                            "Beverages",
                            "Dairy",
                            "Snacks",
                            "Frozen Foods",
                            "Bakery",
                            "Canned Goods",
                            "Dry Goods",
                            "Personal Care",
                            "Household Supplies",
                            "Condiments and Spices"
                        ];

                        // Build the select box options
                        let selectBox = "<option value=\"" + excludedCategory + "\">" + excludedCategory.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase()) + "</option>";
                        for (const category of categories) {
                            if (category !== excludedCategory) {
                                selectBox += "<option value=\"" + category + "\">" + category.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase()) + "</option>";
                            }
                        }
                        
                        // Fill in the form fields with data
                        document.getElementById('name_edit').value = data.Name;
                        document.getElementById('quantity_edit').value = data.Quantity;
                        document.getElementById('retail_price_edit').value = data.Retail_Price;
                        document.getElementById('selling_price_edit').value = data.Selling_Price;
                        document.getElementById('supplier_edit').value = data.Supplier;
                        document.getElementById('category_edit').innerHTML = selectBox;
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