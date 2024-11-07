<?php

session_start();

require_once('../database/settings.php');

$conn = new mysqli($host, $user, $pwd, $sql_db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the member ID from the form
    $member_id = $conn->real_escape_string($_POST['Member_ID']);
    
    // Check the current status of the member
    $status_check_sql = "SELECT Status FROM members WHERE Member_ID = '$member_id'";
    $status_result = $conn->query($status_check_sql);

    if ($status_result && $status_result->num_rows > 0) {
        $row = $status_result->fetch_assoc();
        $current_status = $row['Status'];

        if ($current_status === 'inactive') {
            // Reactivate the member
            $update_member_sql = "UPDATE members SET Status = 'active' WHERE Member_ID = '$member_id'";
            if ($conn->query($update_member_sql) === TRUE) {
                $_SESSION['success'] = "Member reactivated successfully.";
                header("Location: ./members.php");
                exit();
            } else {
                $_SESSION['error'] = "Error reactivating member: " . $conn->error;
                header("Location: ./members.php");
                exit();
            }
        } else {
            $update_member_sql = "UPDATE members SET Status = 'inactive' WHERE Member_ID = '$member_id'";
            if ($conn->query($update_member_sql) === TRUE) {
                $_SESSION['success'] = "Member marked as inactive successfully.";
                header("Location: ./members.php");
                exit();
            } else {
                $_SESSION['error'] = "Error marking member as inactive: " . $conn->error;
                header("Location: ./members.php");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "Member not found.";
        header("Location: ./members.php");
        exit();
    }
} else {
    header("Location: ./members.php");
    exit();
}

// Close the connection
$conn->close();
?>
