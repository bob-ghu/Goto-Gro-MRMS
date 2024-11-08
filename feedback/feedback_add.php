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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $feedback_type = $conn->real_escape_string($_POST['feedback_type']);
    $comments = $conn->real_escape_string($_POST['comments']);


    // Insert the feedback into the database
    $sql = "INSERT INTO feedback (name, email, feedback_type, comments,created_at)
            VALUES ('$name', '$email', '$feedback_type', '$comments', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Redirect to a thank you page or feedback success message
        echo "Feedback submitted successfully!";
        // You can use header("Location: thank_you.php"); to redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>