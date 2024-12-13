<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
    exit;
}

if (!isset($_POST["fullname"])) {
    header("Location: ./members.php"); 
    exit; 
}

require_once('../database/settings.php');

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    $_SESSION['error'] = "Database connection failure";
    header("Location: ./members.php?add=error");
    exit;
}

function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errMsg = "";

// Full Name
if (isset($_POST["fullname"])) {
    $Full_Name = $_POST["fullname"];
    $Full_Name = sanitise_input($Full_Name);
}
if ($Full_Name == "") {
    $errMsg .= "<p>You must enter your full name.</p>";
} else if (!preg_match("/^[a-zA-Z ]*$/", $Full_Name)) {
    $errMsg .= "<p>Only letters are allowed in your name.</p>";
}

// Email
if (isset($_POST["email"])) {
    $Email_Address = $_POST["email"];
    $Email_Address = sanitise_input($Email_Address);
}
if ($Email_Address == "") {
    $errMsg .= "<p>You must enter your email.</p>";
} else if (!preg_match("/^[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/", $Email_Address)) {
    $errMsg .= "<p>Email is invalid.</p>";
}

// Phone number
if (isset($_POST["phonenum"])) {
    $Phone_Number = $_POST["phonenum"];
    $Phone_Number = sanitise_input($Phone_Number);
}
if ($Phone_Number == "") {
    $errMsg .= "<p>You must enter your phone number.</p>";
} else if (!preg_match("/^[0-9 ]{8,12}$/", $Phone_Number)) {
    $errMsg .= "<p>Phone number is invalid.</p>";
}

// Birth Date
if (isset($_POST["dob"])) {
    $DOB = $_POST["dob"];
    $DOB = sanitise_input($DOB);
}
if ($DOB == "") {
    $errMsg .= "<p>You must enter your date of birth.</p>";
} else if (!preg_match("/^(0[1-9]|[12][0-9]|3[01])[\/\-](0[1-9]|1[0-2])[\/\-]\d{4}$/", $DOB)) {
    $errMsg .= "<p>Date of birth is invalid.</p>";
}

// Gender
$Gender = ""; // Initialize with a default value
if (isset($_POST["gender"])) {
    $Gender = $_POST["gender"];
    $Gender = sanitise_input($Gender);
}
if (!($Gender == "Male" || $Gender == "Female" || $Gender == "Not-say")) {
    $errMsg .= "<p>You must select a gender.</p>";
}

// Street Address
$Street_Address = "";
if (isset($_POST["streetaddress"])) {
    $Street_Address = $_POST["streetaddress"];
    $Street_Address = sanitise_input($Street_Address);
}
if ($Street_Address == "") {
    $errMsg .= "<p>You must enter your street address.</p>";
} else if (!preg_match("/^[a-zA-Z0-9 ]{1,50}$/", $Street_Address)) {
    $errMsg .= "<p>Street address is invalid.</p>";
}

// Country
if (isset($_POST["country"])) {
    $Country = $_POST["country"];
    $Country = sanitise_input($Country);
}
if ($Country == "") {
    $errMsg .= "<p>You must select a country.</p>";
}

// State
if (isset($_POST["state"])) {
    $State = $_POST["state"];
    $State = sanitise_input($State);
}
if ($State == "") {
    $errMsg .= "<p>You must enter your state.</p>";
} else if (!preg_match("/^[a-zA-Z ]{1,50}$/", $State)) {
    $errMsg .= "<p>State is invalid.</p>";
}

// City
if (isset($_POST["city"])) {
    $City = $_POST["city"];
    $City = sanitise_input($City);
}
if ($City == "") {
    $errMsg .= "<p>You must enter your city.</p>";
} else if (!preg_match("/^[a-zA-Z ]{1,50}$/", $City)) {
    $errMsg .= "<p>City is invalid.</p>";
}

// Postal Code
if (isset($_POST["postalcode"])) {
    $Postal_Code = $_POST["postalcode"];
    $Postal_Code = sanitise_input($Postal_Code);
}
if ($Postal_Code == "") {
    $errMsg .= "<p>You must enter your postal code.</p>";
} else if (strlen($Postal_Code) != 5) {
    $errMsg .= "<p>Postal Code is invalid. Please use 5 digits only.</p>";
}

// Display Error Message
if ($errMsg != "") {
    $_SESSION['error'] = $errMsg;
    header("Location: ./members.php?add=error");
    exit;
} else {
    // Insert
    $sql = "INSERT INTO members (Full_name, Email_Address, Phone_Number, DOB, Gender, Street_Address, City, State, Country, Postal_Code) 
            VALUES ('$Full_Name', '$Email_Address', '$Phone_Number', '$DOB', '$Gender', '$Street_Address', '$City', '$State', '$Country', '$Postal_Code')";

    $query = mysqli_query($conn, $sql);
    
    if(!$query) {
        $_SESSION['error'] = "Something is wrong with the query: " . mysqli_error($conn);
        header("Location: ./members.php?add=error");
    } else {
        $_SESSION['success'] = "Application successfully submitted!";
        header("Location: ./members.php?add=success");
    }

    mysqli_close($conn);
    exit;
}
?>
