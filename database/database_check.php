<?php
    require_once('settings.php');

    $mysqli = new mysqli($host, $user, $pwd);

    if ($mysqli->connect_error) {
        die("Database Connection failed: " . $mysqli->connect_error);
    }

    $sqlFile = '../database/gotogro_mrms.sql';

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
    } else {
        echo "Error executing SQL file: " . $conn->error;
    }

    // Close the connection
    $mysqli->close();
?>