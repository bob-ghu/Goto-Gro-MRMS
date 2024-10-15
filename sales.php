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
    $items= [];
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

    <!-- Registration Form in Modal -->
    <div class="modal">
      <div class="sales-form-container">
        <!--Add Members Form-->
        <h1>Add Sales</h1>
        <form id="add" method="post" action="Sales_Add.php" class="sales-form" novalidate="novalidate">
          <!--Member Selection-->
          <div class="input-box">
            <label for="member_id">Member</label>
            <div class="select-box">
              <select name="member_id" id="member_id" required>
                <option value="">Select a member</option>
                <!-- Populate this with dynamic options based on members in the system -->
                <?php
                // Loop through the members and populate the options dynamically
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
              <div class="select-box">
                <select name="payment_method" id="payment_method" required>
                  <option value="">Select payment method</option>
                  <option value="cash">Cash</option>
                  <option value="card">Card</option>
                </select>
              </div>

              <section id="payment_error" class="error"></section>
            </div>

            <!--Staff ID (Optional)-->
            <div class="input-box">
              <label for="staff_id">Processed By (Staff)</label>
              <div class="select-box">
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

    <div class="edit-modal-overlay"></div>
    <!-- Edit Member Modal -->

    <div class="edit-modal">
      <div class="sales-form-container">
        <header>Edit Sales</header>
        <form id="add" method="post" action="Sales_Edit.php" class="sales-form" novalidate="novalidate">
          <input type="hidden" name="Sales_ID" id="editSalesID">
          <!--Full Name-->
          <div class="input-box">
            <label>Full Name</label>
            <input type="text" name="fullname_edit" id="fullname_edit" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Example: John Doe" value="" required />
            <section id="fullname_error" class="error"></section>
          </div>

          <!--Email Address-->
          <div class="input-box">
            <label>Email Address</label>
            <input type="text" name="email" id="email_edit" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" placeholder="Example: name@domain.com" value="" required />
            <section id="email_error" class="error"></section>
          </div>

          <!--Merge Column-->
          <div class="column">
            <!--Phone Number-->
            <div class="input-box">
              <label>Phone Number</label>
              <input type="tel" name="phonenum" id="phonenum_edit" maxlength="12" pattern="[0-9 ]{8,12}" placeholder="Example: 012 1234567" value="" required />
              <section id="phonenum_error" class="error"></section>
            </div>

            <!--Birth Date-->
            <div class="input-box">
              <label>Birth Date</label>
              <input type="text" name="dob" id="dob_edit" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}" placeholder="dd/mm/yyyy" value="" required />
              <section id="dob_error" class="error"></section>
            </div>
          </div>

          <!--Gender Box-->
          <div class="gender-box">
            <h3>Gender</h3>
            <div class="gender-option">
              <!--Male-->
              <div class="gender">
                <input type="radio" name="gender" id="check-male_edit" value="male" />
                <label for="check-male">Male</label>
              </div>

              <!--Female-->
              <div class="gender">
                <input type="radio" name="gender" id="check-female_edit" value="female" />
                <label for="check-female">Female</label>
              </div>

              <!--Not to Say-->
              <div class="gender">
                <input type="radio" name="gender" id="check-others_edit" value="not-say" />
                <label for="check-others">Prefer Not To Say</label>
              </div>
              <section id="gender_error" class="error"></section>
            </div>
          </div>

          <!--Address Column-->
          <div class="input-box address">

            <!--Street Address-->
            <label>Street Address</label>
            <input type="text" name="streetaddress" id="streetaddress_edit" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: 123 Jalan Sultan" value="" required />
            <section id="streetaddress_error" class="error"></section>

            <br>

            <!--Country-->
            <label>Country</label>
            <div class="select-box">
              <select name="country" id="country_edit" required>
                <!--A select box of countries will be dynamically inserted here-->
              </select>
            </div>
            <section id="country_error" class="error"></section>
            <div class="column">
              <!--City-->
              <div class="input-box">
                <label>City</label>
                <input type="text" name="city" id="city_edit" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: Kuala Lumpur" value="" required />
                <section id="city_error" class="error"></section>
              </div>
              <!--Postcode-->
              <div class="input-box">
                <label>Postal Code</label>
                <input type="text" name="postalcode" id="postalcode_edit" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" value="" required />
                <section id="postalcode_error" class="error"></section>
              </div>
            </div>
          </div>
          <button>Save Changes</button>
        </form>
      </div>
    </div>

    <script src="./index.js"></script>
    <script src="salesform.js"></script>
    <script src="sales.js"></script>

    <?php
      // Generate product options in PHP
      $productOptions = '';
      foreach ($items as $product) {
          $productOptions .= '<option value="' . $product['Item_ID'] . '">' . htmlspecialchars($product['Name']) . '</option>';
      }
    ?>

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
            <div class="select-box">
              <select name="inventory_id[]" id="product_${productCount}" required>
          
                <option value="">Select a product</option>
                ${productOptions}
              </select>
            </div>
          </div>

          <div class="input-box">
            <label for="quantity_${productCount}">Quantity</label>
            <input type="number" name="quantity[]" id="quantity_${productCount}" min="1" placeholder="Enter quantity" required />
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
        });
      });
    </script>
  </div>

  <script src="./index.js"></script>
  <script src="sales.js"></script>
  </div>
</body>

</html>