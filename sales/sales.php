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

// Pagination settings
$limit = 8; // Number of rows per page

// Get total count of sales for pagination
$sql_count = "SELECT COUNT(*) AS total FROM sales";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_sales = $row_count['total'];

// Calculate total pages
$total_pages = ceil($total_sales / $limit);

if (isset($_POST['page_input']) && $_POST['page_input'] <= $total_pages) {
  $page = $_POST['page_input'];
} else {
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page or default to 1
}
$offset = ($page - 1) * $limit; // Calculate the offset for SQL query

// SQL query to fetch paginated sales details
$sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, 
        sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, sales.Payment_Method, staff.Full_Name AS Staff_Name
        FROM sales 
        JOIN members ON sales.Member_ID = members.Member_ID 
        JOIN inventory ON sales.Item_ID = inventory.Item_ID 
        JOIN staff ON sales.Staff_ID = staff.Staff_ID
        ORDER BY sales.Sales_ID
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Fetch members from the database
$query2 = "SELECT Member_ID, Full_Name FROM members";
$result2 = $conn->query($query2);
$members = $result2->num_rows > 0 ? $result2->fetch_all(MYSQLI_ASSOC) : [];

// Fetch products from the database
$query3 = "SELECT Item_ID, Name FROM inventory";
$result3 = $conn->query($query3);
$items = $result3->num_rows > 0 ? $result3->fetch_all(MYSQLI_ASSOC) : [];

// Notification count
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$recentNoti = mysqli_query($conn, $query2);

// Total Sales
$sales_count_query = "SELECT COUNT(*) as total_sales FROM sales";
$sales_count_result = $conn->query($sales_count_query);
$sales_count = $sales_count_result->fetch_assoc()['total_sales'];

// Total Revenue
$total_revenue_query = "SELECT SUM(Total_Price) as total_revenue FROM sales";
$total_revenue_result = $conn->query($total_revenue_query);
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'];

// Total Profit Calculation
$total_profit_query = "
    SELECT SUM((i.Selling_Price - i.Retail_Price) * s.Quantity) as total_profit
    FROM sales s
    JOIN inventory i ON s.Item_ID = i.Item_ID"; // Ensure Item_ID matches your database schema
$total_profit_result = $conn->query($total_profit_query);
$total_profit = $total_profit_result->fetch_assoc()['total_profit'];

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

// Fetch the staff names from the staff table
$staffQuery = "SELECT Staff_ID, Full_Name FROM staff";  // Adjust table/column names if needed
$staffResult = $conn->query($staffQuery);
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
  <link rel="stylesheet" href="../styles/sales.css" />
  <link rel="stylesheet" href="../styles/salesform.css" />
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
        <a href="../sales/sales.php" class="active">
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
      <h1>Sales</h1>

      <div class="insights">
        <!-- Total Sales -->
        <div class="total-sales">
          <span class="material-icons-sharp">shopping_cart</span>
          <div class="middle">
            <div class="left">
              <h3>Total Sales</h3>
              <h1 id="total-sales-count"><?php echo $sales_count; ?></h1> <!-- Dynamically set total sales count -->
            </div>
          </div>
        </div>

        <!-- Total Revenue -->
        <div class="total-revenue">
          <span class="material-icons-sharp">attach_money</span>
          <div class="middle">
            <div class="left">
              <h3>Total Revenue</h3>
              <h1 id="total-revenue-amount">$ <?php echo number_format($total_revenue, 2); ?></h1> <!-- Format as currency -->
            </div>
          </div>
        </div>

        <!-- Total Profit -->
        <div class="total-profit">
          <span class="material-icons-sharp">monetization_on</span>
          <div class="middle">
            <div class="left">
              <h3>Total Profit</h3>
              <h1 id="total-profit-amount">$ <?php echo number_format($total_profit, 2); ?></h1> <!-- Format as currency -->
            </div>
          </div>
        </div>
      </div>

      <div class="sales-container">

        <div class="sales-detail">
          <div class="sales-detail-header">
            <h2>Sales' Detail</h2>

            <form method="get" action="">
              <div class="search-row">

                <div class="search-select-box">
                  <p>Search by:</p>
                  <select name="search-column" id="search-column">
                    <?php
                    $excludedOption = "";
                    if (isset($_GET['search-column'])) {
                      echo "<option value=\"" . $_GET['search-column'] . "\">";
                      if ($_GET['search-column'] == "members.Full_Name") {
                        echo "Member Name";
                      } elseif ($_GET['search-column'] == "inventory.Name") {
                        echo "Item Name";
                      } elseif ($_GET['search-column'] == "sales.Quantity") {
                        echo "Quantity";
                      } else {
                        echo str_replace('_', ' ', $_GET['search-column']);
                      }
                      echo "</option>";
                      $excludedOption = $_GET['search-column'];
                    }

                    $options = [
                      "Sales_ID",
                      "members.Full_Name",
                      "inventory.Name",
                      "sales.Quantity",
                      "Price_per_Unit",
                      "Total_Price",
                      "Sale_Date",
                      "Payment_Method",
                      "Staff_ID"
                    ];

                    foreach ($options as $option) {
                      $optionDisplay = "";
                      if ($option == "members.Full_Name") {
                        $optionDisplay = "Member Name";
                      } elseif ($option == "inventory.Name") {
                        $optionDisplay = "Item Name";
                      } elseif ($option == "sales.Quantity") {
                        $optionDisplay = "Quantity";
                      } else {
                        $optionDisplay = str_replace('_', ' ', $option);
                      }
                      // Exclude the specified country
                      if (strcmp($option, $excludedOption) !== 0) {
                        echo '<option value="' . $option . '">' . $optionDisplay . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>

                <div class="search-container">
                  <input type="text" name="search_bar" id="search_bar" maxlength="40" placeholder="Search" value="<?php
                                                                                                                  if (isset($_GET['search_bar'])) {
                                                                                                                    echo $_GET['search_bar'];
                                                                                                                  } ?>" />
                  <button type="submit"><span class="material-icons-sharp">search</span></button>
                </div>
                <div id="current-filter">
                  <a><button type="button" onclick="window.location.href=window.location.pathname;">Clear Filter</button></a>
                </div>
              </div>
            </form>

            <div class="add-sales">
              <div>
                <span class="material-icons-sharp">add</span>
                <h3>Add Sales</h3>
              </div>
            </div>
          </div>

          <table id="sales-detail--table">
            <thead>
              <tr>
                <th>Sales ID</th>
                <th>Member Name</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price per Unit</th>
                <th>Total Price</th>
                <th>Sale Date</th>
                <th>Payment Method</th>
                <th>Staff Name</th>
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
                $sql_search = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, 
                sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, sales.Payment_Method, staff.Full_Name AS Staff_Name FROM sales 
                JOIN members ON sales.Member_ID = members.Member_ID JOIN inventory ON sales.Item_ID = inventory.Item_ID JOIN staff ON sales.Staff_ID = staff.Staff_ID
                WHERE $search_column LIKE '%$search_term%'";

                $result_search = $conn->query($sql_search);

                // Check if any results were found
                if ($result_search->num_rows > 0) {
                  $total_pages = 1;
                  while ($row = $result_search->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Sales_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Member_Name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Item_Name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Price_per_Unit']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Total_Price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Sale_Date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Payment_Method']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Staff_Name']) . "</td>";
                    echo "<td><a class='edit-sales' onclick=\"requestSalesInfo(this)\" data-sales-id='" . $row["Sales_ID"] . "'>Edit</a></td>";
                    echo "</tr>";
                  }
                } else {
                  echo "No results found.";
                }
              } else {
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["Sales_ID"] . "</td>";
                    echo "<td>" . $row["Member_Name"] . "</td>";
                    echo "<td>" . $row["Item_Name"] . "</td>";
                    echo "<td>" . $row["Quantity"] . "</td>";
                    echo "<td>" . $row["Price_per_Unit"] . "</td>";
                    echo "<td>" . $row["Total_Price"] . "</td>";
                    echo "<td>" . $row["Sale_Date"] . "</td>";
                    echo "<td>" . $row["Payment_Method"] . "</td>";
                    echo "<td>" . $row["Staff_Name"] . "</td>";
                    echo "<td><a class='edit-sales' onclick=\"requestSalesInfo(this)\" data-sales-id='" . $row["Sales_ID"] . "'>Edit</a></td>";
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
            <?php echo "<form id=\"page-form\" method=\"post\" action=\"./sales.php\" class=\"page-form\" novalidate=\"novalidate\">
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

      <div class="report-generate">
        <h2>Export Sales's CSV</h2>
        <a href="export_sales.php" class="download-button">
          <div class="report-generate-button">
            <!-- Button to generate CSV report -->
            <span class="material-icons-sharp">print</span>
            <!-- Button to generate CSV report -->
            Download Sales Report as CSV
          </div>
        </a>
      </div>
    </div>

    <!-- Modal Overlay (Form is moved outside the container) -->
    <div class="modal-overlay"></div>

    <div class="modal">
      <div class="sales-form-container">
        <h1>Add Sales</h1>

        <form id="add" method="post" action="./Sales_Add.php" class="sales-form" novalidate="novalidate">
          <div class="input-box">
            <label for="member_id">Member</label>
            <div class="select-box addmember-box">
              <select name="member_id" id="member_id" required>
                <option value="">Select a member</option>

                <?php

                foreach ($members as $member) {
                  echo '<option value="' . $member['Member_ID'] . '">' . htmlspecialchars($member['Full_Name']) . '</option>';
                }
                ?>
              </select>
            </div>

            <section id="member_error" class="error"></section>
          </div>

          <div class="column">
            <!--Payment Method-->
            <div class="input-box">
              <label for="payment_method">Payment Method</label>
              <div class="select-box payment-box">
                <select name="payment_method" id="payment_method" required>
                  <option value="">Select payment method</option>
                  <option value="Cash">Cash</option>
                  <option value="Card">Card</option>
                </select>
              </div>

              <section id="payment_error" class="error"></section>
            </div>

            <!--Staff ID (Optional)-->
            <div class="input-box">
              <label for="staff_id">Processed By (Staff)</label>
              <div class="select-box staff-box">
                <select name="staff_id" id="staff_id">
                  <?php
                  if ($staffResult->num_rows > 0) {
                    echo '<option hidden="hidden">Select staff</option>';
                    // Loop through the staff results and generate options dynamically
                    while ($staffRow = $staffResult->fetch_assoc()) {
                      $staff_id = $staffRow['Staff_ID'];
                      $staff_name = $staffRow['Full_Name'];
                      echo "<option value='$staff_id'>$staff_name</option>";
                    }
                  } else {
                    echo "<p>No staff members found.</p>";
                  }
                  ?>
                </select>
              </div>

              <section id="staff_error" class="error"></section>
            </div>
          </div>

          <!--Product List Section (Dynamically Generated)-->
          <div class="product-list" id="product-list">
            <h2>Item List:</h2>
            <!-- Product items will be appended here -->
          </div>

          <!--Button to Add Product-->
          <div class="input-box">
            <button type="button" class="add-product" id="add-product-btn">+ Add Product</button>
          </div>


          <button class="submit">Submit</button>
        </form>
      </div>
    </div>

    <?php
    if (isset($_GET['success']) && $_GET['success'] === '1') {
      echo "<script>alert('Sales successfully added!');</script>";
    }
    ?>

    <div class="edit-modal-overlay"></div>
    <!-- Edit Member Modal -->

    <div class="edit-modal">
      <div class="sales-form-container">
        <h1>Edit Sales</h1>
        <form id="edit" method="post" action="./Sales_Edit.php" class="sales-form" novalidate="novalidate">
          <input type="hidden" name="Sales_ID" id="editSalesID">
          <!--Member Selection-->
          <div class="input-box">
            <label for="member_id">Member</label>
            <div class="select-box editmember-box">
              <select name="member_edit" id="member_edit" required>
                <!-- Populate this with dynamic options based on members in the system -->
              </select>
            </div>

            <section id="member_edit_error" class="error"></section>
          </div>

          <div class="column">
            <!--Payment Method-->
            <div class="input-box">
              <label for="payment_method">Payment Method</label>
              <div class="select-box">
                <select name="payment_method_edit" id="payment_method_edit" required>

                </select>
              </div>

              <section id="payment_error" class="error"></section>
            </div>

            <!--Staff ID (Optional)-->
            <div class="input-box">
              <label for="staff_id">Processed By (Staff)</label>
              <div class="select-box">
                <select name="staff_edit" id="staff_edit">
                  <!-- Populate this with dynamic options based on employees -->
                </select>
              </div>

              <section id="staff_error" class="error"></section>
            </div>
          </div>

          <div class="column">
            <div class="input-box">
              <label>Product</label>
              <div class="select-box">
                <select name="product_edit" id="product_edit" required>
                  <!-- Populate this with dynamic options based on products -->
                </select>
              </div>
              <section id="product_error" class="error"></section>
            </div>

            <div class="input-box">
              <label>Quantity</label>
              <input type="number" name="quantity_edit" id="quantity_edit" min="1" placeholder="Enter quantity" required />
              <section id="quantity_edit_error" class="error"></section>
            </div>
          </div>
          <button class="edit">Save Changes</button>
        </form>
      </div>
    </div>

    <?php if (isset($_GET['add']) && $_GET['add'] === 'success'): ?>
      <div class="status success">
        Sale added successfully!
      </div>
    <?php elseif (isset($_GET['add']) && $_GET['add'] === 'error'): ?>
      <div class="status error">
        There was an error adding the sale. Please try again.
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['edit']) && $_GET['edit'] === 'success'): ?>
      <div class="status success">
        Sale edited successfully!
      </div>
    <?php elseif (isset($_GET['edit']) && $_GET['edit'] === 'error'): ?>
      <div class="status error">
        There was an error editing the sale. Please try again.
      </div>
    <?php endif; ?>

    <script>
      // Hide the notification after 3 seconds
      setTimeout(function() {
        const status = document.querySelector('.status');
        if (status) {
          status.style.display = 'none';
        }
      }, 3000);
    </script>

    <?php
    // Generate product options in PHP
    $productOptions = '';
    foreach ($items as $product) {
      $productOptions .= '<option value="' . $product['Item_ID'] . '">' . htmlspecialchars($product['Name']) . '</option>';
    }
    ?>
  </div>

  <script>
    let productCount = 0; // To keep track of the number of products added

    const productOptions = `<?php echo $productOptions; ?>`;

    document.getElementById('add-product-btn').addEventListener('click', function() {
      productCount++;

      // Create a new product and quantity selection block
      const productBlock = document.createElement('div');
      productBlock.classList.add('input-box');
      productBlock.innerHTML = `
    <div class="product-item">
      <div class="column">
        <div class="input-box">
          <label for="product_${productCount}">Product</label>
          <div class="select-box product-box">
            <select name="inventory_id[]" id="product_${productCount}" required>
        
              <option value="">Select a product</option>
              ${productOptions}
            </select>
          </div>
          <section id="product_${productCount}_error" class="error"></section>
        </div>

        <div class="input-box">
          <label for="quantity_${productCount}">Quantity</label>
          <input type="number" name="quantity[]" id="quantity_${productCount}" min="1" placeholder="Enter quantity" required />
          <section id="quantity_${productCount}_error" class="error"></section>
        </div>
      </div>
      <button type="button" class="remove-product-btn">Remove</button>
    </div>
    `;

      // Append the new product block to the product list section
      document.getElementById('product-list').appendChild(productBlock);

      // Add event listener to the remove button
      productBlock.querySelector('.remove-product-btn').addEventListener('click', function() {
        productBlock.remove();
        productCount--;
      });
    });
  </script>
  <script>
    // JavaScript function to send AJAX request to PHP
    function requestSalesInfo(record) {
      // Make a POST request to the PHP script using fetch()
      fetch('request_sales.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json', // Specify plain text format
          },
          body: record.getAttribute('data-sales-id') // Send the Sales_ID as the body
        })
        .then(response => response.json()) // Expect a JSON response
        .then(data => {
          if (data.error) {
            console.log("Error fetching data"); // Debugging
          } else {
            const excludedMember = data.Request_Data.Member_Name;

            // Build Member Dropdown
            var selectBox_members = "";
            selectBox_members += "<option value=\"" + data.Request_Data.Member_ID + "\">" + excludedMember + "</option>";
            data.Members_Table.forEach(member => {
              if (member.Full_Name !== excludedMember) {
                selectBox_members += "<option value=\"" + member.Member_ID + "\">" + member.Full_Name + "</option>";
              }
            });

            // Build Payment Method Dropdown
            const excludedPayment = data.Request_Data.Payment_Method;
            var selectBox_payment = "";
            selectBox_payment += "<option value=\"" + excludedPayment + "\">" + excludedPayment + "</option>";
            if (excludedPayment === "Cash") {
              selectBox_payment += "<option value=Card>Card</option>";
            } else {
              selectBox_payment += "<option value=Cash>Cash</option>";
            }

            // Build Staff Dropdown
            const excludedStaff = data.Request_Data.Staff_ID;

            var selectBox_staff = "";
            selectBox_staff += "<option value=\"" + excludedStaff + "\">" + data.Request_Data.Staff_Name + "</option>";
            data.Staff_Table.forEach(staff => {
              if (staff.Staff_ID !== excludedStaff) {
                selectBox_staff += "<option value=\"" + staff.Staff_ID + "\">" + staff.Full_Name + "</option>";
              }
            });

            // Build Inventory Dropdown
            const excludedItem = data.Request_Data.Item_Name;
            var selectBox_inventory = "";
            selectBox_inventory += "<option value=\"" + data.Request_Data.Item_ID + "\">" + excludedItem + "</option>";
            data.Inventory_Table.forEach(item => {
              if (item.Name !== excludedItem) {
                selectBox_inventory += "<option value=\"" + item.Item_ID + "\">" + item.Name + "</option>";
              }
            });

            // Populate the dropdowns
            document.getElementById('member_edit').innerHTML = selectBox_members;
            document.getElementById('payment_method_edit').innerHTML = selectBox_payment;
            document.getElementById('staff_edit').innerHTML = selectBox_staff;
            document.getElementById('product_edit').innerHTML = selectBox_inventory;

            // Set quantity
            document.getElementById('quantity_edit').value = data.Request_Data.Quantity;

            // Call previous item details function (if needed)
            previousSalesItem(data.Request_Data.Item_ID, data.Request_Data.Quantity);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>
  <script>
    function previousSalesItem(itemID, quantity) {
      sessionStorage.setItem('previousItemID', itemID);
      sessionStorage.setItem('previousQuantity', quantity);
    }

    const form = document.getElementById('edit');

    // Add a 'submit' event listener
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      if (validateQuantity(form)) {
        // Set current item ID and quantity in session storage
        sessionStorage.setItem('currentItemID', document.getElementById('product_edit').value);
        sessionStorage.setItem('currentQuantity', document.getElementById('quantity_edit').value);

        form.submit(); // Submit the form only if validation passes
      }
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('edit') === 'success') {
      const data = {
        Previous_Item_ID: sessionStorage.getItem('previousItemID'),
        Previous_Quantity: sessionStorage.getItem('previousQuantity'),
        Current_Item_ID: sessionStorage.getItem('currentItemID'),
        Current_Quantity: sessionStorage.getItem('currentQuantity')
      };

      fetch('../inventory/update_inventory.php', {
          method: 'POST', // Use POST method
          headers: {
            'Content-Type': 'application/json', // Specify the content type
          },
          body: JSON.stringify(data) // Convert data to JSON string
        })
        .then(response => response.text()) // Parse the JSON response
        .then(data => {
          console.log('Success:', data); // Handle success
        })
        .catch((error) => {
          console.error('Error:', error); // Handle error
        });

      urlParams.delete('edit');
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  </script>
  <script src="../index/index.js"></script>
  <script src="./salesform.js"></script>
  <script src="./sales.js"></script>

  </div>
</body>

</html>