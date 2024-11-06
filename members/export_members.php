<?php
require_once('../database/settings.php');

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the headers to download the file as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=members_report.csv');

// Open output stream to write CSV
$output = fopen('php://output', 'w');

// Write the CSV column headers
fputcsv($output, [
    'Member ID', 
    'Full Name', 
    'Email Address', 
    'Phone Number', 
    'DOB', 
    'Gender', 
    'Street Address', 
    'Country', 
    'State', 
    'City', 
    'Postal Code', 
    'Status'
]);

// Query to fetch all member details
$sql = "SELECT Member_ID, Full_Name, Email_Address, Phone_Number, DOB, Gender, Street_Address, Country, State, City, Postal_Code, Status FROM members";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each row and write it to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['Member_ID'],
            $row['Full_Name'],
            $row['Email_Address'],
            $row['Phone_Number'],
            $row['DOB'],
            $row['Gender'],
            $row['Street_Address'],
            $row['Country'],
            $row['State'],
            $row['City'],
            $row['Postal_Code'],
            $row['Status']
        ]);
    }
} else {
    echo "No data available.";
}

// Close the output stream
fclose($output);

// Close database connection
$conn->close();
?>