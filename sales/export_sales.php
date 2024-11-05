<?php
require_once('../database/settings.php');

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers to download the file as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=sales_report.csv');

// Open output stream to write CSV
$output = fopen('php://output', 'w');

// Write the CSV column headers
fputcsv($output, [
    'Sales ID', 
    'Member Name', 
    'Item Name', 
    'Quantity', 
    'Price per Unit', 
    'Total Price', 
    'Sale Date', 
    'Payment Method', 
    'Staff ID'
]);

// Query to fetch sales details
$sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, 
        sales.Quantity, sales.Price_per_Unit, sales.Total_Price, sales.Sale_Date, 
        sales.Payment_Method, sales.Staff_ID 
        FROM sales 
        JOIN members ON sales.Member_ID = members.Member_ID 
        JOIN inventory ON sales.Item_ID = inventory.Item_ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each row and write it to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['Sales_ID'],
            $row['Member_Name'],
            $row['Item_Name'],
            $row['Quantity'],
            $row['Price_per_Unit'],
            $row['Total_Price'],
            $row['Sale_Date'],
            $row['Payment_Method'],
            $row['Staff_ID']
        ]);
    }
} else {
    echo "No sales data available.";
}

// Close the output stream
fclose($output);

// Close database connection
$conn->close();
?>