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

    $Sales_ID = $conn->real_escape_string($_POST['Sales_ID']);
    $Member_ID = $conn->real_escape_string($_POST['member_edit']);
    $Payment_Method = $conn->real_escape_string($_POST['payment_method_edit']);
    $Staff_ID = $conn->real_escape_string($_POST['staff_edit']);
    $Item_ID = $conn->real_escape_string($_POST['product_edit']);
    $New_Quantity = (int)$conn->real_escape_string($_POST['quantity_edit']);

    // Validation for required fields
    if (empty($Member_ID) || empty($Item_ID) || empty($New_Quantity) || empty($Payment_Method) || empty($Staff_ID)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ./sales.php");
        exit();
    }

    // Query to get the current sale quantity
    $currentSaleQuery = "SELECT Quantity FROM sales WHERE Sales_ID = '$Sales_ID'";
    $currentSaleResult = $conn->query($currentSaleQuery);
    
    if ($currentSaleResult->num_rows > 0) {
        $currentSaleRow = $currentSaleResult->fetch_assoc();
        $Old_Quantity = (int)$currentSaleRow['Quantity'];
    } else {
        $_SESSION['error'] = "Sale record not found.";
        header("Location: ./sales.php?edit_id=$Sales_ID");
        exit();
    }

    // Calculate the difference in quantity
    $quantityDifference = $New_Quantity - $Old_Quantity;

    // Query to get the current stock of the item in inventory
    $inventoryQuery = "SELECT Quantity, Selling_Price FROM inventory WHERE Item_ID = '$Item_ID'";
    $inventoryResult = $conn->query($inventoryQuery);

    if ($inventoryResult->num_rows > 0) {
        $inventoryRow = $inventoryResult->fetch_assoc();
        $Current_Stock = (int)$inventoryRow['Quantity'];
        $Item_Price = $inventoryRow['Selling_Price'];

        // Check if there is enough stock if quantity is increasing
        if ($quantityDifference > 0 && $quantityDifference > $Current_Stock) {
            echo json_encode(["status" => "error", "message" => "Not enough stock available for the requested quantity."]);
            exit();
        }

        // Update the sale record
        $updateSalesQuery = "UPDATE sales 
                             SET Member_ID = '$Member_ID', 
                                 Item_ID = '$Item_ID', 
                                 Quantity = '$New_Quantity', 
                                 Price_per_Unit = '$Item_Price',
                                 Payment_Method = '$Payment_Method', 
                                 Staff_ID = '$Staff_ID' 
                             WHERE Sales_ID = '$Sales_ID'";

        if ($conn->query($updateSalesQuery) === TRUE) {
            // Adjust the inventory quantity based on the difference
            $newInventoryQuantity = $Current_Stock - $quantityDifference; // Reduce if positive, increase if negative
            $updateInventoryQuery = "UPDATE inventory SET Quantity = '$newInventoryQuantity' WHERE Item_ID = '$Item_ID'";
            
            if ($conn->query($updateInventoryQuery) === TRUE) {
                $_SESSION['success'] = "Sale record and inventory updated successfully!";
                header("Location: ./sales.php?edit=success");
                exit();
            } else {
                $_SESSION['error'] = "Error updating inventory: " . $conn->error;
                header("Location: ./sales.php?edit=error");
                exit();
            }
        } else {
            $_SESSION['error'] = "Error updating sale: " . $conn->error;
            header("Location: ./sales.php?edit=error");
            exit();
        }
    } else {
        $_SESSION['error'] = "Item not found in inventory.";
        header("Location: ./sales.php?edit_id=$Sales_ID");
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
