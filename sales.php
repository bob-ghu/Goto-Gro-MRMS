<?php
require_once('settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch sales details
$limit = 10;
// Check if the "show all" parameter is set
if (isset($_GET['show']) && $_GET['show'] === 'all') {
  // SQL query to fetch all member details
  $sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, 
  sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, sales.Payment_Method, sales.Staff_ID 
  FROM sales JOIN members ON sales.Member_ID = members.Member_ID JOIN inventory ON sales.Item_ID = inventory.Item_ID";
  $show_all = true;
} else {
  // SQL query to fetch limited member details
  $sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, 
  sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, sales.Payment_Method, sales.Staff_ID 
  FROM sales JOIN members ON sales.Member_ID = members.Member_ID JOIN inventory ON sales.Item_ID = inventory.Item_ID LIMIT $limit";
  $show_all = false;
}

$result = $conn->query($sql);

$sql_count = "SELECT COUNT(*) AS total FROM sales";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_members = $row_count['total'];

// Fetch members from the database
$query2 = "SELECT Member_ID, Full_Name FROM members";
$result2 = $conn->query($query2);

if ($result2->num_rows > 0) {
  // Store members in an array
  $members = [];
  while ($row = $result2->fetch_assoc()) {
    $members[] = $row;
  }
} else {
  $members = [];
}

// Fetch products from the database
$query3 = "SELECT Item_ID, Name FROM inventory";
$result3 = $conn->query($query3);

if ($result3->num_rows > 0) {
  // Store products in an array
  $items = [];
  while ($row = $result3->fetch_assoc()) {
    $items[] = $row;
  }
} else {
  $items = [];
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
  <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="./sales.css" />
  <link rel="stylesheet" href="./salesform.css" />
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
        <a href="inventory.php">
          <span class="material-icons-sharp"> inventory_2 </span>
          <h3>Inventory </h3>
        </a>
        <a href="sales.php" class="active">
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
      <h1>Sales</h1>

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

      <div class="sales-container">
        <!--Add Members Form-->
        <div class="sales-detail">
          <div class="sales-detail-header">
            <h2>Sales' Detail</h2>

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
                <th>Staff ID</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
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
                  echo "<td>" . $row["Staff_ID"] . "</td>";
                  echo "<td><a class='edit-sales' onclick=\"requestSalesInfo(this)\" data-sales-id='" . $row["Sales_ID"] . "'>Edit</a></td>";
                  echo "</tr>";
                }
              }
              ?>
            </tbody>

          </table>
          <?php if ($total_members > $limit): ?>
            <?php if ($show_all): ?>
              <a href="sales.php" class="show-all">Show Less</a>
            <?php else: ?>
              <a href="sales.php?show=all" class="show-all">Show All</a>
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
    <div class="modal-overlay"></div>

    <div class="modal">
      <div class="sales-form-container">
        <h1>Add Sales</h1>

        <form id="add" method="post" action="Sales_Add.php" class="sales-form" novalidate="novalidate">
          <div class="input-box">
            <label for="member_id">Member</label>
            <div class="select-box member-box">
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
                  <option value="">Select staff</option>
                  <!-- Populate this with dynamic options based on employees -->
                  <option value="1">Staff 1</option>
                  <option value="2">Staff 2</option>
                  <option value="3">Staff 3</option>
                  <option value="4">Staff 4</option>
                  <option value="5">Staff 5</option>
                  <!-- More options dynamically loaded -->
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
        <form id="add" method="post" action="Sales_Edit.php" class="sales-form" novalidate="novalidate">
          <input type="hidden" name="Sales_ID" id="editSalesID">
          <!--Member Selection-->
          <div class="input-box">
            <label for="member_id">Member</label>
            <div class="select-box">
              <select name="member_id" id="member_edit" required>
                <!-- Populate this with dynamic options based on members in the system -->
              </select>
            </div>

            <section id="member_error" class="error"></section>
          </div>

          <div class="column">
            <!--Payment Method-->
            <div class="input-box">
              <label for="payment_method">Payment Method</label>
              <div class="select-box">
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
              <div class="select-box">
                <select name="staff_id" id="staff_edit">
                  <!-- Populate this with dynamic options based on employees -->
                </select>
              </div>

              <section id="staff_error" class="error"></section>
            </div>
          </div>

          <div class="column">
            <div class="input-box">
              <label for="product_select">Product</label>
              <div class="select-box">
                <select name="inventory_id" id="product_select" required>
                  <option value="">Select a product</option>
                </select>
              </div>
              <section id="product_error" class="error"></section>
            </div>

            <div class="input-box">
              <label for="quantity">Quantity</label>
              <input type="number" name="quantity" id="quantity" min="1" placeholder="Enter quantity" required />
              <section id="quantity_error" class="error"></section>
            </div>
          </div>
          <button class="edit">Save Changes</button>
        </form>
      </div>
    </div>

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
      // Make a GET request to the PHP script using fetch()
      fetch('request_sales.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json', // Specify plain text format
          },
          body: record.getAttribute('data-sales-id')
      })
      .then(response => response.json()) // Expect a json response
      .then(data => {
        if (data.error) {
            console.log("error"); // Debugging
        } else {
            const excludedMember = data.Request_Data.Member_Name;

            var selectBox_members = "";
            selectBox_members += "<option value=\"" + excludedMember + "\">" + excludedMember + "</option>";
            data.Members_Table.forEach(member => {
              if (member.Full_Name !== excludedMember) {
                  selectBox_members += "<option value=\"" + member.Full_Name + "\">" + member.Full_Name + "</option>";
                }
            });

            const excludedStaff = "Staff " + data.Request_Data.Staff_ID;
            const staffs = [
                "Staff 1",
                "Staff 2",
                "Staff 3",
                "Staff 4",
                "Staff 5"
            ];

            var selectBox_staff = "";
            selectBox_staff += "<option value=\"" + excludedStaff + "\">" + excludedStaff + "</option>";
            for (const staff of staffs) {
                if (staff !== excludedStaff) {
                  selectBox_staff += "<option value=\"" + staff + "\">" + staff + "</option>";
                }
            }

            document.getElementById('member_edit').innerHTML = selectBox_members;
            //document.getElementById('payment_method_edit').value = data.Payment_Method;
            document.getElementById('staff_edit').innerHTML = selectBox_staff;
            //document.getElementById('product_select').value = data.Item_Name;
        }
      })
      .catch(error => {
          console.error('Error:', error);
      });
    }
  </script>
  <script src="./index.js"></script>
  <script src="salesform.js"></script>
  <script src="sales.js"></script>

  </div>

</body>

</html>