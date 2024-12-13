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
    $sql = "INSERT INTO feedback (name, email, feedback_type, comments, created_at)
            VALUES ('$name', '$email', '$feedback_type', '$comments', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Set a success message and redirect
        $_SESSION['success'] = "Feedback submitted successfully!";
        header("Location: ./feedback.php?add=success");
        exit();
    } else {
        // Set an error message and redirect
        $_SESSION['error'] = "Error submitting feedback: " . $conn->error;
        header("Location: ./feedback.php?add=error");
        exit();
    }
}

// Close the database connection
$conn->close();
?>