<?php
if (!isset($_GET['id']))  {
    header("Location: members.php"); 
    exit; 
}
    
require_once('settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate your input data here
    $member_id = $_POST['id'];
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phonenum = $conn->real_escape_string($_POST['phonenum']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $streetaddress = $conn->real_escape_string($_POST['streetaddress']);
    $country = $conn->real_escape_string($_POST['country']);
    $city = $conn->real_escape_string($_POST['city']);
    $postalcode = $conn->real_escape_string($_POST['postalcode']);

    // Update the member in the database
    $sql = "UPDATE members SET 
            Full_Name = '$fullname', 
            Email_Address = '$email', 
            Phone_Number = '$phonenum', 
            DOB = '$dob', 
            Gender = '$gender', 
            Street_Address = '$streetaddress', 
            Country = '$country', 
            City = '$city', 
            Postal_Code = '$postalcode' 
            WHERE Member_ID = $member_id";

        if ($conn->query($sql) === TRUE) {
            header("Location: members.php");
            exit;
        } else {
            echo "Error updating record: " . $conn->error . "<br>SQL: " . $sql;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GotoGro</title>
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="member.css" />
  <link rel="stylesheet" href="form.css">
  <script src="members.js"></script>
</head>
<body>
    <div class="container">
        <aside>
            <!-- Start Top -->
            <div class="top">
                <div class="logo">
                    <img src="./images/logo.png" alt="Logo" />
                    <h2>Goto<span class="danger">Gro</span></h2>
                </div>
                <div class="close" id="close_btn">
                    <span class="material-icons-sharp"> close </span>
                </div>
            </div>
            <!-- End Top -->

            <!--Sidebar-->
            <div class="sidebar">
                <!--Dashboard-->
                <a href="index.php">
                    <span class="material-icons-sharp">dashboard</span>
                    <h3>Dashbord</h3>
                </a>
                <!--Members-->
                <a href="members.php" class="active">
                    <span class="material-icons-sharp">person_outline</span>
                    <h3>Members</h3>
                </a>
                <!--Sales-->
                <a href="#">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Sales</h3>
                </a>
                <!--Inventory-->
                <a href="#">
                    <span class="material-icons-sharp">inventory_2</span>
                    <h3>Inventory</h3>
                </a>
                <!--Notification-->
                <a href="#">
                    <span class="material-icons-sharp">notifications</span>
                    <h3>Notification</h3>
                    <span class="message-count">26</span>
                </a>

                <a href="#">
                    <span class="material-icons-sharp"> insights </span>
                    <h3>Analytics</h3>
                  </a>
                <!--Customer Feedback-->
                <a href="#">
                    <span class="material-icons-sharp"> feedback </span>
                    <h3>Feedback</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">logout </span>
                    <h3>logout</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> report_gmailerrorred </span>
                    <h3>Reports</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">settings </span>
                    <h3>Settings</h3>
                </a>
            </div>
        </aside>

        <?php
            $member_id = $_GET['id'];
            $sql = "SELECT * FROM members WHERE Member_ID = $member_id";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
        ?>

        <main>
            <div class="member-container">
                <!--Add Members Form-->
            <h1>Registration Form</h1>
            <form id="add" method="post" action="" class="member-form" novalidate="novalidate">
            <input type="hidden" name="id" value="<?php echo $row['Member_ID']; ?>" />
                <!--Full Name-->
                <div class="input-box">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" maxlength="50" pattern="^[a-zA-Z ]+$" placeholder="Example: John Doe" value="<?php echo $row['Full_Name'];?>" required/>
                    <section id="fullname_error" class="error"></section>
                </div>

                <!--Email Address-->
                <div class="input-box">
                    <label>Email Address</label>
                    <input type="text" name="email" id="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" placeholder="Example: name@domain.com" value="<?php echo $row['Email_Address'];?>" required/>
                    <section id="email_error" class="error"></section>
                </div>
                
                <!--Merge Column-->
                <div class="column">
                    <!--Phone Number-->
                    <div class="input-box">
                        <label>Phone Number</label>
                        <input type="tel" name="phonenum" id="phonenum" maxlength="12" pattern="[0-9 ]{8,12}" placeholder="Example: 012 1234567" value="<?php echo $row['Phone_Number'];?>" required/>
                        <section id="phonenum_error" class="error"></section>
                    </div>

                    <!--Birth Date-->
                    <div class="input-box">
                        <label>Birth Date</label>
                        <input type="text" name="dob" id="dob" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}" placeholder="dd/mm/yyyy" value="<?php echo $row['DOB'];?>" required/>
                        <section id="dob_error" class="error"></section>
                    </div>
                </div>

                <!--Gender Box-->
                <div class="gender-box">
                    <h3>Gender</h3>
                    <div class="gender-option">
                        <!--Male-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-male" value="male" <?php if ($row['Gender'] == "male") echo "checked" ?>/>
                            <label for="check-male">Male</label>
                        </div>

                        <!--Female-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-female" value="female" <?php if ($row['Gender'] == "female") echo "checked" ?>/>
                            <label for="check-female">Female</label>
                        </div>

                        <!--Not to Say-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-others" value="not-say" <?php if ($row['Gender'] == "not-say") echo "checked" ?>/>
                            <label for="check-others">Prefer Not To Say</label>
                        </div>
                        <section id="gender_error" class="error"></section>
                    </div>
                </div>

                <!--Address Column-->
                <div class="input-box address">

                    <!--Street Address-->
                    <label>Street Address</label>
                    <input type="text" name="streetaddress" id="streetaddress" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: 123 Jalan Sultan" value="<?php echo $row['Street_Address'];?>" required/>
                    <section id="streetaddress_error" class="error"></section>
                    
                    <br>

                    
                    <!--Country-->
                    <?php
                        $excludeCountry = lcfirst($row['Country']);  // The country you want to exclude
                        $countries = [
                            "canada" => "Canada",
                            "usa" => "USA",
                            "japan" => "Japan",
                            "india" => "India",
                            "malaysia" => "Malaysia",
                            "singapore" => "Singapore",
                            "southkorea" => "South Korea",
                            "myanmar" => "Myanmar",
                            "vietnam" => "Vietnam",
                            "brunei" => "Brunei",
                            "china" => "China",
                            "sweden" => "Sweden",
                            "france" => "France",
                            "germany" => "Germany"
                        ];
                    ?>
                    <label>Country</label>
                    <div class="select-box">
                        <select name="country" id="country" required>
                            <?php
                                echo "<option value=\"" . $row['Country'] . "\">" . ucfirst($row['Country']) . "</option>";
                                foreach ($countries as $value => $label) {
                                    if ($value != $excludeCountry) {
                                        echo "<option value=\"$value\">" . ucfirst($label) . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <section id="country_error" class="error"></section>
                    <div class="column">
                        <!--City-->
                        <div class="input-box">
                            <label>City</label>
                            <input type="text" name="city" id="city" maxlength="50" size="50" pattern="[a-zA-Z ]{1,50}" placeholder="Example: Kuala Lumpur" value="<?php echo $row['City'];?>" required />
                            <section id="city_error" class="error"></section>
                        </div>
                        <!--Postcode-->
                        <div class="input-box">
                            <label>Postal Code</label>
                            <input type="text" name="postalcode" id="postalcode" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" value="<?php echo $row['Postal_Code'];?>" required/>
                            <section id="postalcode_error" class="error"></section>
                        </div>
                        
                    </div>
                </div>
            <button>Submit</button>
        </form>
            </div>
        </main>
    </div>
</body>
</html>