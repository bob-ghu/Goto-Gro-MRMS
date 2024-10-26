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
    
    $item_id = $_POST['Item_ID']; // Hidden field with the member's ID
    $stockname = $conn->real_escape_string($_POST['name_edit']);
    $quantity = $conn->real_escape_string($_POST['quantity_edit']);
    $retailprice = $conn->real_escape_string($_POST['retail_price_edit']);
    $sellingprice = $conn->real_escape_string($_POST['selling_price_edit']);
    $supplier = $conn->real_escape_string($_POST['supplier_edit']);
    $category = $conn->real_escape_string($_POST['category_edit']);
    $brand = $conn->real_escape_string($_POST['brand_edit']);
    $reorder = $conn->real_escape_string($_POST['reorder_edit']);

    
    // Validate the input (you can add more validation as needed)
    if (empty($stockname) || empty($quantity) || empty($retailprice) || empty($sellingprice) || empty($supplier) || empty($category) || empty($brand) || empty($reorder)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ./inventory.php");
        exit();
    }

    // Prepare an SQL statement to update the member's details
    $sql = "UPDATE inventory 
            SET Name = '$stockname', 
                Quantity = '$quantity', 
                Retail_Price = '$retailprice', 
                Selling_Price = '$sellingprice', 
                Supplier = '$supplier', 
                Category = '$category', 
                Brand = '$brand', 
                Reorder_Level = '$reorder'
            WHERE Item_ID = '$item_id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Set a success message in the session and redirect to members page
        $_SESSION['success'] = "Inventory details updated successfully.";
        header("Location: ./inventory.php");
        exit();
    } else {
        // Set an error message in the session if the query fails
        $_SESSION['error'] = "Error updating record: " . $conn->error;
        header("Location: ./inventory.php");
        exit();
    }
} else {
    // Redirect to members page if the request method is not POST
    header("Location: ./inventory.php");
    exit();
}

// Close the connection
$conn->close();
?>