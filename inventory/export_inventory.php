<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
    exit;
}

require_once('../database/settings.php');

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers to download the file as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventory_report.csv');

// Open output stream to write CSV
$output = fopen('php://output', 'w');

// Write the CSV column headers
fputcsv($output, [
    'Item ID', 
    'Name', 
    'Quantity', 
    'Retail Price', 
    'Selling Price', 
    'Supplier', 
    'Category', 
    'Brand', 
    'Reorder Level'
]);

// Query to fetch all inventory details
$sql = "SELECT Item_ID, Name, Quantity, Retail_Price, Selling_Price, Supplier, Category, Brand, Reorder_Level FROM inventory";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each row and write it to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['Item_ID'],
            $row['Name'],
            $row['Quantity'],
            $row['Retail_Price'],
            $row['Selling_Price'],
            $row['Supplier'],
            $row['Category'],
            $row['Brand'],
            $row['Reorder_Level']
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