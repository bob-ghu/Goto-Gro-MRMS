<?php
session_start();  // Start the session

// Store data for the first context (e.g., for page 1)
if (!isset($_SESSION['database_exist'])) {

    require_once('settings.php');

    $mysqli = new mysqli($host, $user, $pwd, $sql_db);

    if ($mysqli->connect_error) {
        die("Database Connection failed: " . $mysqli->connect_error);
    }

    $sqlFile = 'gotogro_mrms.sql';  // Adjust to the actual path

    // Read SQL file content
    $sql = file_get_contents($sqlFile);

    if ($sql === false) {
        die("Error reading the SQL file.");
    }

    // Execute the SQL commands from the file
    if ($mysqli->multi_query($sql)) {
        do {
            // Move to the next result in case of multiple queries
        } while ($mysqli->next_result());
        echo "SQL file executed successfully!";
    } else {
        echo "Error executing SQL file: " . $conn->error;
    }

    // Close the connection
    $mysqli->close();

    $_SESSION['database_exist'] = true;
}
?>