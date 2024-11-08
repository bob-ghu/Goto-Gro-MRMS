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

// Pagination setup
$limit = 7;

// Get total number of records for pagination calculation
$sql_count = "SELECT COUNT(*) AS total FROM members";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_members = $row_count['total'];
$total_pages = ceil($total_members / $limit); // Total pages based on records

if (isset($_POST['page_input']) && $_POST['page_input'] <= $total_pages) {
    $page = $_POST['page_input'];
} else {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page or default to 1
}
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM members LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Notification Count
$unread = "SELECT message, notification_type FROM notifications WHERE is_read = 0";
$notiCount = mysqli_query($conn, $unread);
$query2 = "SELECT noti, created_at, notification_type FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 3";
$result2 = mysqli_query($conn, $query2);

// Fetch active member count
$sql_active_count = "SELECT COUNT(*) AS active_total FROM members WHERE status = 'active'";
$active_result = $conn->query($sql_active_count);
$active_count = $active_result->fetch_assoc()['active_total'];

// Calculate percentage of active members
$active_percentage = ($total_members > 0) ? ($active_count / $total_members) * 100 : 0;

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
    <link rel="stylesheet" href="../styles/member.css" />
    <link rel="stylesheet" href="../styles/memberform.css" />
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

                <a href="../members/members.php" class="active">
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
            <h1>Members</h1>

            <div class="insights">
                <!-- Total Members -->
                <div class="total-members">
                    <span class="material-icons-sharp">groups</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Members</h3>
                            <h1 id="total-members-count"><?php echo $total_members; ?></h1> <!-- Dynamically set total members count -->
                        </div>
                        <div class="progress">
                        </div>
                    </div>

                </div>

                <!-- Active Members -->
                <div class="active-members">
                    <span class="material-icons-sharp">data_usage</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Active Members</h3>
                            <h1><?php echo $active_count; ?> &nbsp;Out of&nbsp; <?php echo $total_members; ?></h1>
                        </div>
                        <div class="progress">
                            <div class="pie-chart-container">
                                <canvas id="membersPieChart"></canvas>
                                <div class="legend">
                                    <div class="legend-item">
                                        <div class="active"></div>
                                        <h4>Active</h4>
                                    </div>
                                    <div class="legend-item">
                                        <div class="inactive"></div>
                                        <h4>Inactive</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pie Chart for Active and Inactive Members -->
                    </div>
                    <small class="text-muted">Active Members | Total Members</small>
                </div>

            </div>

            <div class="member-container">
                <!--Add Members Form-->
                <div class="member-detail">
                    <div class="member-detail-header">
                        <h2>Member's Detail</h2>

                        <form method="get" action="">
                            <div class="search-row">

                                <div class="search-select-box">
                                    <p>Search by:</p>
                                    <select name="search-column" id="search-column">
                                        <?php
                                        $excludedOption = "";
                                        if (isset($_GET['search-column'])) {
                                            echo "<option value=\"" . $_GET['search-column'] . "\">";
                                            if ($_GET['search-column'] == "DOB") {
                                                echo "Date of Birth";
                                            } else {
                                                echo str_replace('_', ' ', $_GET['search-column']);
                                            }
                                            echo "</option>";
                                            $excludedOption = $_GET['search-column'];
                                        }

                                        $options = [
                                            "Member_ID",
                                            "Full_Name",
                                            "Email_Address",
                                            "Phone_Number",
                                            "DOB",
                                            "Gender",
                                            "Street_Address",
                                            "Country",
                                            "State",
                                            "City",
                                            "Postal_Code",
                                            "Status"
                                        ];

                                        foreach ($options as $option) {
                                            $optionDisplay = "";
                                            if ($option == "DOB") {
                                                $optionDisplay = "Date of Birth";
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

                        <div class="add-member">
                            <div>
                                <span class="material-icons-sharp">add</span>
                                <h3>Add Members</h3>
                            </div>
                        </div>
                    </div>

                    <table id="member-detail--table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Street Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Postal Code</th>
                                <th>Status</th>
                                <th class="edit-btn"></th>
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
                                $sql_search = "SELECT * FROM members WHERE $search_column LIKE '%$search_term%'";

                                $result_search = $conn->query($sql_search);

                                // Check if any results were found
                                if ($result_search->num_rows > 0) {
                                    $total_pages = 1;
                                    while ($row = $result_search->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['Member_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Full_Name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Email_Address']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Phone_Number']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['DOB']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Street_Address']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Country']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['State']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['City']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Postal_Code']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                                        echo "<td><a class='edit-member' onclick=\"requestMemberInfo(this)\" data-member-id='" . htmlspecialchars($row["Member_ID"]) . "'>Edit</a></td>";
                                        echo "<td>";
                                        echo "<form action='./Process_Delete.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='Member_ID' value='" . htmlspecialchars($row["Member_ID"]) . "'>";

                                        if ($row['Status'] === 'inactive') {
                                            echo "<a><button onclick='return confirm(\"Are you sure you want to reactivate this member?\");' class='member-status'>Activate</button></a>";
                                        } else {
                                            echo "<a><button onclick='return confirm(\"Are you sure you want to mark this member as inactive?\");' class='member-status'>Deactivate</button></a>";
                                        }
                                        echo "</form></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "No results found.";
                                }
                            } else {
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row["Member_ID"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Full_Name"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Email_Address"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Phone_Number"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["DOB"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Gender"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Street_Address"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Country"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["State"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["City"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Postal_Code"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";


                                        echo "<td><a class='edit-member' onclick=\"requestMemberInfo(this)\" data-member-id='" . htmlspecialchars($row["Member_ID"]) . "'>Edit</a>" . "</td>";
                                        echo "<td>";
                                        echo "<form action='./Process_Delete.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='Member_ID' value='" . htmlspecialchars($row["Member_ID"]) . "'>";

                                        if ($row['Status'] === 'inactive') {
                                            echo "<a><button onclick='return confirm(\"Are you sure you want to reactivate this member?\");' class='member-status'>Activate</button></a>";
                                        } else {
                                            echo "<a><button onclick='return confirm(\"Are you sure you want to mark this member as inactive?\");' class='member-status'>Deactivate</button></a>";
                                        }
                                        echo "</form></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "No results found.";
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
                        <?php echo "<form id=\"page-form\" method=\"post\" action=\"./members.php\" class=\"page-form\" novalidate=\"novalidate\">
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

            <div class="report-generate">
                <h2>Export Member's CSV</h2>
                <a href="export_members.php" class="download-button">
                    <div class="report-generate-button">
                        <!-- Button to generate CSV report -->
                        <span class="material-icons-sharp">print</span>
                        Download Member Report as CSV
                    </div>
                </a>
            </div>

        </div>

        <!-- Modal Overlay (Form is moved outside the container) -->
        <div class="modal-overlay"></div>

        <!-- Registration Form in Modal -->
        <div class="modal">
            <div class="member-form-container">
                <!--Add Members Form-->
                <h1>Registration Form</h1>
                <form id="add" method="post" action="./Process_Add.php" class="member-form" novalidate="novalidate">
                    <!--Full Name-->
                    <div class="input-box">
                        <label>Full Name</label>
                        <input type="text" name="fullname" id="fullname" maxlength="50" pattern="^[a-zA-Z ]+$"
                            placeholder="Example: John Doe" required />
                        <section id="fullname_error" class="error"></section>
                    </div>
                    <!--Email Address-->
                    <div class="input-box">
                        <label>Email Address</label>
                        <input type="text" name="email" id="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                            placeholder="Example: name@domain.com" required />
                        <section id="email_error" class="error"></section>
                    </div>

                    <!--Merge Column-->
                    <div class="column">
                        <!--Phone Number-->
                        <div class="input-box">
                            <label>Phone Number</label>
                            <input type="tel" name="phonenum" id="phonenum" maxlength="12" pattern="[0-9 ]{8,12}"
                                placeholder="Example: 012 1234567" required />
                            <section id="phonenum_error" class="error"></section>
                        </div>

                        <!--Birth Date-->
                        <div class="input-box">
                            <label>Birth Date</label>
                            <input type="text" name="dob" id="dob" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}"
                                placeholder="dd/mm/yyyy" required />
                            <section id="dob_error" class="error"></section>
                        </div>
                    </div>

                    <!--Gender Box-->
                    <div class="gender-box">
                        <h3>Gender</h3>
                        <div class="gender-option">
                            <!--Male-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-male" value="Male" />
                                <label for="check-male">Male</label>
                            </div>

                            <!--Female-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-female" value="Female" />
                                <label for="check-female">Female</label>
                            </div>

                            <!--Not to Say-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-others" value="Not-say" />
                                <label for="check-others">Prefer Not To Say</label>
                            </div>
                            <section id="gender_error" class="error"></section>
                        </div>
                    </div>

                    <!--Address Column-->
                    <div class="input-box address">

                        <!--Street Address-->
                        <label>Address</label>
                        <input type="text" name="streetaddress" id="streetaddress" maxlength="50" size="50"
                            pattern="[a-zA-Z ]{1,40}" placeholder="Enter your street address" required />
                        <section id="streetaddress_error" class="error"></section>

                    </div>
                    <div class="input-box">
                        <div class="column">
                            <div class="input-box">
                                <label>Country</label>
                                <div class="select-box addcountry-box">
                                    <select name="country" id="country" required>
                                        <option value="">Select your country</option>
                                        <option value="canada">Canada</option>
                                        <option value="usa">USA</option>
                                        <option value="japan">Japan</option>
                                        <option value="india">India</option>
                                        <option value="malaysia">Malaysia</option>
                                        <option value="singapore">Singapore</option>
                                        <option value="southkorea">South Korea</option>
                                        <option value="myanmar">Myanmar</option>
                                        <option value="vietnam">Vietnam</option>
                                        <option value="brunei">Brunei</option>
                                        <option value="china">China</option>
                                        <option value="sweden">Sweden</option>
                                        <option value="france">France</option>
                                        <option value="germany">Germany</option>
                                    </select>
                                </div>
                                <section id="country_error" class="error"></section>
                            </div>
                            <!--Country-->


                            <div class="input-box">
                                <label>State</label>
                                <input type="text" name="state" id="state" maxlength="50" size="50" placeholder="Example: Selangor" pattern="[a-zA-Z ]{1,40}" required />
                                <section id="state_error" class="error"></section>
                            </div>
                        </div>


                        <div class="column">
                            <!--City-->
                            <div class="input-box">
                                <label>City</label>
                                <input type="text" name="city" id="city" maxlength="50" size="50" pattern="[a-zA-Z ]{1,40}" placeholder="Example: Kuala Lumpur" required />
                                <section id="city_error" class="error"></section>
                            </div>
                            <!--Postcode-->
                            <div class="input-box">
                                <label>Postal Code</label>
                                <input type="text" name="postalcode" id="postalcode" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" required />
                                <section id="postalcode_error" class="error"></section>
                            </div>
                        </div>
                    </div>
                    <button>Submit</button>
                </form>
            </div>
        </div>

        <div class="edit-modal-overlay"></div>
        <!-- Edit Member Modal -->

        <div class="edit-modal">
            <div class="member-form-container">
                <header>Edit Member</header>
                <form id="edit" method="post" action="./Process_Edit.php" class="member-form" novalidate="novalidate">
                    <input type="hidden" name="Member_ID" id="editMemberID">
                    <!--Full Name-->
                    <div class="input-box">
                        <label>Full Name</label>
                        <input type="text" name="fullname_edit" id="fullname_edit" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Example: John Doe" value="" required />
                        <section id="fullname_edit_error" class="error"></section>
                    </div>

                    <!--Email Address-->
                    <div class="input-box">
                        <label>Email Address</label>
                        <input type="text" name="email" id="email_edit" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" placeholder="Example: name@domain.com" value="" required />
                        <section id="email_edit_error" class="error"></section>
                    </div>

                    <!--Merge Column-->
                    <div class="column">
                        <!--Phone Number-->
                        <div class="input-box">
                            <label>Phone Number</label>
                            <input type="tel" name="phonenum" id="phonenum_edit" maxlength="12" pattern="[0-9 ]{8,12}" placeholder="Example: 012 1234567" value="" required />
                            <section id="phonenum_edit_error" class="error"></section>
                        </div>

                        <!--Birth Date-->
                        <div class="input-box">
                            <label>Birth Date</label>
                            <input type="text" name="dob" id="dob_edit" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}" placeholder="dd/mm/yyyy" value="" required />
                            <section id="dob_edit_error" class="error"></section>
                        </div>
                    </div>

                    <!--Gender Box-->
                    <div class="gender-box">
                        <h3>Gender</h3>
                        <div class="gender-option">
                            <!--Male-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-male_edit" value="Male" />
                                <label for="check-male">Male</label>
                            </div>

                            <!--Female-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-female_edit" value="Female" />
                                <label for="check-female">Female</label>
                            </div>

                            <!--Not to Say-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-others_edit" value="Not-say" />
                                <label for="check-others">Prefer Not To Say</label>
                            </div>
                            <section id="gender_edit_error" class="error"></section>
                        </div>
                    </div>

                    <!--Address Column-->
                    <div class="input-box address">

                        <!--Street Address-->
                        <label>Street Address</label>
                        <input type="text" name="streetaddress" id="streetaddress_edit" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: 123 Jalan Sultan" value="" required />
                        <section id="streetaddress_edit_error" class="error"></section>

                        <br>

                        <div class="column">
                            <div class="input-box">
                                <!--Country-->
                                <label>Country</label>
                                <div class="select-box editcountry-box">
                                    <select name="country" id="country_edit" required>
                                        <!--A select box of countries will be dynamically inserted here-->
                                    </select>
                                </div>
                                <section id="country_edit_error" class="error"></section>
                            </div>
                            <div class="input-box">
                                <label>State</label>
                                <input type="text" name="state" id="state_edit" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: Selangor" value="" required />
                                <section id="state_edit_error" class="error"></section>
                            </div>
                        </div>

                        <div class="column">
                            <!--City-->
                            <div class="input-box">
                                <label>City</label>
                                <input type="text" name="city" id="city_edit" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: Kuala Lumpur" value="" required />
                                <section id="city_edit_error" class="error"></section>
                            </div>
                            <!--Postcode-->
                            <div class="input-box">
                                <label>Postal Code</label>
                                <input type="text" name="postalcode" id="postalcode_edit" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" value="" required />
                                <section id="postalcode_edit_error" class="error"></section>
                            </div>
                        </div>
                    </div>
                    <button>Save Changes</button>
                </form>
            </div>
        </div>

        <script src="../index/index.js"></script>
        <script src="./memberform.js"></script>
        <script src="./members.js"></script>
        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const activeCount = <?php echo $active_count; ?>;
            const inactiveCount = <?php echo $total_members - $active_count; ?>;

            const ctx = document.getElementById('membersPieChart').getContext('2d');
            const membersPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: [activeCount, inactiveCount],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw || 0;
                                    return label + ': ' + value;
                                }
                            },
                            // Custom tooltip styles
                            bodyFont: {
                                size: 11, // Change this value for the desired font size
                            },
                            titleFont: {
                                size: 0 // Optional: change title font size if applicable
                            },
                        }
                    }
                }
            });
        </script>

        <script>
            // JavaScript function to send AJAX request to PHP
            function requestMemberInfo(record) {
                // Make a GET request to the PHP script using fetch()
                fetch('./request_member.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'text/plain', // Specify plain text format
                        },
                        body: record.getAttribute('data-member-id')
                    })
                    .then(response => response.json()) // Expect a json response
                    .then(data => {
                        if (data.error) {
                            console.log("error"); // Debugging
                        } else {
                            var gender;
                            if (data.Gender == "Male") {
                                gender = "check-male";
                            } else if (data.Gender == "Female") {
                                gender = "check-female";
                            } else {
                                gender = "check-others";
                            }

                            const excludedCountry = data.Country.charAt(0).toUpperCase() + data.Country.slice(1);
                            const countries = [
                                "Canada",
                                "USA",
                                "Japan",
                                "India",
                                "Malaysia",
                                "Singapore",
                                "South Korea",
                                "Myanmar",
                                "Vietnam",
                                "Brunei",
                                "China",
                                "Sweden",
                                "France",
                                "Germany"
                            ];

                            var selectBox = "";
                            selectBox += "<option value=\"" + excludedCountry + "\">" + excludedCountry + "</option>";
                            for (const country of countries) {
                                if (country !== excludedCountry) {
                                    selectBox += "<option value=\"" + country + "\">" + country + "</option>";
                                }
                            }

                            document.getElementById('fullname_edit').value = data.Full_Name;
                            document.getElementById('email_edit').value = data.Email_Address;
                            document.getElementById('phonenum_edit').value = data.Phone_Number;
                            document.getElementById('dob_edit').value = data.DOB;
                            document.getElementById(gender + '_edit').checked = true;
                            document.getElementById('streetaddress_edit').value = data.Street_Address;
                            document.getElementById('country_edit').innerHTML = selectBox;
                            document.getElementById('state_edit').value = data.State;
                            document.getElementById('city_edit').value = data.City;
                            document.getElementById('postalcode_edit').value = data.Postal_Code;
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