<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
    exit;
}

// Include database connection settings
require_once('../database/settings.php');

// Create a connection to the database
$conn = new mysqli($host, $user, $pwd, $sql_db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for editing the sale
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Sales_ID = $_POST['Sales_ID'];
    $Sales_ID = $conn->real_escape_string($Sales_ID);
    $Member_ID = $conn->real_escape_string($_POST['member_edit']);
    $Payment_Method = $conn->real_escape_string($_POST['payment_method_edit']);
    $Staff_ID = $conn->real_escape_string($_POST['staff_edit']);
    $Item_ID = $conn->real_escape_string($_POST['product_edit']);
    $Quantity = $conn->real_escape_string($_POST['quantity_edit']);

    // Validation for required fields
    if (empty($Member_ID) || empty($Item_ID) || empty($Quantity) || empty($Payment_Method) || empty($Staff_ID)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ./sales.php");
        exit();
    }

    // Query to get the price of the selected item
    $itemPriceQuery = "SELECT Selling_Price FROM inventory WHERE Item_ID = '$Item_ID'";
    $itemPriceResult = $conn->query($itemPriceQuery);

    // Check if the item price was found
    if ($itemPriceResult->num_rows > 0) {
        $itemPriceRow = $itemPriceResult->fetch_assoc();
        $Item_Price = $itemPriceRow['Selling_Price'];
    } else {
        $_SESSION['error'] = "Item not found in inventory.";
        header("Location: ./sales.php?edit_id=error");
        exit();
    }

    // SQL query to update the sale record
    $updateSalesQuery = "UPDATE sales 
                         SET Member_ID = '$Member_ID', 
                             Item_ID = '$Item_ID', 
                             Quantity = '$Quantity', 
                             Price_per_Unit = '$Item_Price',
                             Payment_Method = '$Payment_Method', 
                             Staff_ID = '$Staff_ID' 
                         WHERE Sales_ID = '$Sales_ID'";

    // Debugging SQL Query (Optional - can be commented out after testing)
    // echo "Debugging SQL Query: " . $updateSalesQuery;

    // Execute the update query and check for errors
    if ($conn->query($updateSalesQuery) === TRUE) {
        $_SESSION['success'] = "Sale record updated successfully!";
        header("Location: ./sales.php?edit=success");
        exit();
    } else {
        // Log the specific error message for debugging purposes
        $_SESSION['error'] = "Error updating sale: " . $conn->error;
        header("Location: ./sales.php?edit=error");
        exit();
    }
} else {
    // Redirect to sales page if the request method is not POST
    header("Location: ./sales.php");
    exit();
}

// Close the database connection
$conn->close();
?>