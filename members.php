<?php
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
    $sql = "SELECT * FROM members";
    $show_all = true;
} else {
    // SQL query to fetch limited member details
    $sql = "SELECT * FROM members LIMIT $limit";
    $show_all = false;
}

$result = $conn->query($sql);

$sql_count = "SELECT COUNT(*) AS total FROM members";
$count_result = $conn->query($sql_count);
$row_count = $count_result->fetch_assoc();
$total_members = $row_count['total'];
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
    <link rel="stylesheet" href="./member.css" />
    <link rel="stylesheet" href="./memberform.css" />
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

                <a href="members.php" class="active">
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
            <h1>Members</h1>

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

            <div class="member-container">
                <!--Add Members Form-->
                <div class="member-detail">
                    <div class="member-detail-header">
                        <h2>Member's Detail</h2>

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
                                <th>City</th>
                                <th>Postal Code</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["Member_ID"] . "</td>";
                                    echo "<td>" . $row["Full_Name"] . "</td>";
                                    echo "<td>" . $row["Email_Address"] . "</td>";
                                    echo "<td>" . $row["Phone_Number"] . "</td>";
                                    echo "<td>" . $row["DOB"] . "</td>";
                                    echo "<td>" . $row["Gender"] . "</td>";
                                    echo "<td>" . $row["Street_Address"] . "</td>";
                                    echo "<td>" . $row["Country"] . "</td>";
                                    echo "<td>" . $row["City"] . "</td>";
                                    echo "<td>" . $row["Postal_Code"] . "</td>";
                                    echo "<td><a class='edit-member' onclick=\"requestMemberInfo(this)\" data-member-id='" . $row["Member_ID"] . "'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>

                    </table>
                    <?php if ($total_members > $limit): ?>
                        <?php if ($show_all): ?>
                            <a href="members.php" class="show-all">Show Less</a>
                        <?php else: ?>
                            <a href="members.php?show=all" class="show-all">Show All</a>
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
            <div class="member-form-container">
                <!--Add Members Form-->
                <h1>Registration Form</h1>
                <form id="add" method="post" action="Process_Add.php" class="member-form" novalidate="novalidate">
                    <!--Full Name-->
                    <div class="input-box">
                        <label>Full Name</label>
                        <input type="text" name="fullname" id="fullname" maxlength="40" pattern="^[a-zA-Z ]+$"
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
                                <input type="radio" name="gender" id="check-male" value="male" />
                                <label for="check-male">Male</label>
                            </div>

                            <!--Female-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-female" value="female" />
                                <label for="check-female">Female</label>
                            </div>

                            <!--Not to Say-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-others" value="not-say" />
                                <label for="check-others">Prefer Not To Say</label>
                            </div>
                            <section id="gender_error" class="error"></section>
                        </div>
                    </div>

                    <!--Address Column-->
                    <div class="input-box address">

                        <!--Street Address-->
                        <label>Address</label>
                        <input type="text" name="streetaddress" id="streetaddress" maxlength="40" size="40"
                            pattern="[a-zA-Z ]{1,40}" placeholder="Enter your street address" required />
                        <section id="streetaddress_error" class="error"></section>

                    </div>
                    <div class="input-box">
                        <!--Country-->
                        <label>Country</label>
                        <div class="select-box">
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

                        <div class="column">
                            <!--City-->
                            <div class="input-box">
                                <label>City</label>
                                <input type="text" name="city" id="city" maxlength="40" size="40" pattern="[a-zA-Z ]{1,40}" placeholder="Example: Kuala Lumpur" required />
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
                <form id="add" method="post" action="Process_Edit.php" class="member-form" novalidate="novalidate">
                <input type= "hidden" name="Member_ID" id="editMemberID">
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
        <script src="memberform.js"></script>
        <script src="members.js"></script>

        <script>
            // JavaScript function to send AJAX request to PHP
            function requestMemberInfo(record) {
                // Make a GET request to the PHP script using fetch()
                fetch('request_member.php', {
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
                            if (data.Gender == "male") {
                                gender = "check-male";
                            } else if (data.Gender == "female") {
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