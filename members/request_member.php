<?php
    $memberID = file_get_contents("php://input");
    require_once('../database/settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    $sql = "SELECT * FROM members WHERE Member_ID = $memberID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the data as an associative array
        $row = $result->fetch_assoc();

        // Return the data as a JSON response
        echo json_encode([
            'Full_Name' => $row['Full_Name'],
            'Email_Address' => $row['Email_Address'],
            'Phone_Number' => $row['Phone_Number'],
            'DOB' => $row['DOB'],
            'Gender' => $row['Gender'],
            'Street_Address' => $row['Street_Address'],
            'Country' => $row['Country'],
            'State' => $row['State'],
            'City' => $row['City'],
            'Postal_Code' => $row['Postal_Code']
        ]);
    } else {
        echo json_encode([
            'error' => 'No member found'
        ]);
    }
?>