<?php
// Start the session
session_start();

// Include database connection settings
require_once('../database/settings.php');

// Create a connection to the database
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    
    $member_id = $_POST['Member_ID']; // Hidden field with the member's ID
    $fullname = $conn->real_escape_string($_POST['fullname_edit']);
    $email = $conn->real_escape_string($_POST['email']);
    $phonenum = $conn->real_escape_string($_POST['phonenum']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $streetaddress = $conn->real_escape_string($_POST['streetaddress']);
    $city = $conn->real_escape_string($_POST['city']);
    $country = $conn->real_escape_string($_POST['country']);
    $postalcode = $conn->real_escape_string($_POST['postalcode']);
    
    // Validate the input (you can add more validation as needed)
    if (empty($fullname) || empty($email) || empty($phonenum) || empty($dob) || empty($streetaddress) || empty($city) || empty($country) || empty($postalcode)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ./members.php");
        exit();
    }

    // Prepare an SQL statement to update the member's details
    $sql = "UPDATE members 
            SET Full_Name = '$fullname', 
                Email_Address = '$email', 
                Phone_Number = '$phonenum', 
                DOB = '$dob', 
                Gender = '$gender', 
                Street_Address = '$streetaddress', 
                City = '$city', 
                Country = '$country', 
                Postal_Code = '$postalcode' 
            WHERE Member_ID = '$member_id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Set a success message in the session and redirect to members page
        $_SESSION['success'] = "Member details updated successfully.";
        header("Location: ./members.php");
        exit();
    } else {
        // Set an error message in the session if the query fails
        $_SESSION['error'] = "Error updating record: " . $conn->error;
        header("Location: ./members.php");
        exit();
    }
} else {
    // Redirect to members page if the request method is not POST
    header("Location: ./members.php");
    exit();
}

// Close the connection
$conn->close();
?>