<?php 
if (!isset($_POST["member_id"])) {
    header("Location: sales.php"); 
    exit; 
}

require_once('settings.php');

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failure</p>";
    exit;
} else {
    // Function to sanitize input
    function sanitise_input($data) {
        if (is_array($data)) {
            return array_map('sanitise_input', $data);
        } else {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }

    $errMsg = "";

    // Handling member
    if (isset($_POST["member_id"])) {
        $Member_ID = sanitise_input($_POST["member_id"]);
    }
    if ($Member_ID == "") {
        $errMsg .= "<p>You must select a member.</p>";
    }

    // Handling payment method
    if (isset($_POST["payment_method"])) {
        $Payment_Method = sanitise_input($_POST["payment_method"]);
    }
    if ($Payment_Method == "") {
        $errMsg .= "<p>You must select a payment method.</p>";
    }

    // Handling staff ID
    if (isset($_POST["staff_id"])) {
        $Staff_ID = sanitise_input($_POST["staff_id"]);
    }
    if ($Staff_ID == "") {
        $errMsg .= "<p>You must select a staff member.</p>";
    }

    // Handling products and quantities
    if (isset($_POST["inventory_id"]) && isset($_POST["quantity"])) {
        $inventoryIds = $_POST["inventory_id"]; // Array of product IDs
        $quantities = $_POST["quantity"]; // Array of quantities

        // Check that products and quantities have the same number of entries
        if (count($inventoryIds) !== count($quantities)) {
            $errMsg .= "<p>Product and quantity mismatch.</p>";
        } else {
            // Validate each product and its corresponding quantity
            foreach ($inventoryIds as $index => $productId) {
                $productId = sanitise_input($productId);
                $quantity = sanitise_input($quantities[$index]);

                // Check if the product is selected
                if (empty($productId)) {
                    $errMsg .= "<p>Product at row " . ($index + 1) . " is not selected.</p>";
                    continue;
                }

                // Check if the quantity is valid
                if (empty($quantity) || $quantity < 1) {
                    $errMsg .= "<p>Quantity for product at row " . ($index + 1) . " must be a number and at least 1.</p>";
                    continue;
                }

                // Query to check available stock in inventory
                $sql2 = "SELECT Quantity, Reorder_Level FROM inventory WHERE Item_ID = $productId";
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0) {
                    // Fetch the available stock and reorder level from the result
                    $row2 = $result2->fetch_assoc();
                    $availableStock = $row2['Quantity'];
                    $reorder_Level = $row2['Reorder_Level'];

                    // Check if the available stock is enough
                    if ($availableStock < $quantity) {
                        $errMsg .= "<p>Insufficient stock for product at row " . ($index + 1) . ". Available: $availableStock, requested: $quantity.</p>";
                    }
                } else {
                    $errMsg .= "<p>Product at row " . ($index + 1) . " does not exist.</p>";
                }
            }
        }
    } else {
        $errMsg .= "<p>You must select at least one product and enter a quantity.</p>";
    }

    // Error Handling
    if ($errMsg != "") {
        echo $errMsg;
    } else {
        // Loop through each selected product
        for ($i = 0; $i < count($inventoryIds); $i++) {
            $Item_ID = $inventoryIds[$i];
            $Quantity = $quantities[$i];

            // Debugging: Check Item_ID and Quantity
            echo "<p>Processing Item ID: '$Item_ID', Quantity: '$Quantity'</p>";

            // Check for empty Item_ID and Quantity before proceeding
            if (empty($Item_ID) || empty($Quantity) || $Quantity < 1) {
                echo "<p>Error: Item ID or quantity is invalid. Skipping this entry.</p>";
                continue; // Skip to the next iteration
            }

            // Fetch the Price per Unit for the selected Item_ID from the inventory table
            $sql = "SELECT Selling_Price, Quantity, Reorder_Level FROM inventory WHERE Item_ID = '$Item_ID'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    $Price_per_Unit = $row['Selling_Price'];
                    $AvailableStock = $row['Quantity'];
                    $Reorder_Level = $row['Reorder_Level'];

                    // Calculate Total Price
                    $Total_Price = $Price_per_Unit * $Quantity;

                    // Insert into the sales table
                    $sql = "INSERT INTO sales (Member_ID, Item_ID, Quantity, Price_per_Unit, Total_Price, Payment_Method, Staff_ID, Sale_Date)
                            VALUES ('$Member_ID', '$Item_ID', '$Quantity', '$Price_per_Unit', '$Total_Price', '$Payment_Method', '$Staff_ID', NOW())";

                    $query = mysqli_query($conn, $sql);
                    if($query) {
                        // Correct update query for stock
                        $sql_update = "UPDATE inventory SET Quantity = Quantity - $Quantity WHERE Item_ID = '$Item_ID'";
                        $update_query = mysqli_query($conn, $sql_update);
                        if($update_query)
                        {
                            $latest_Quantity = $AvailableStock - $Quantity;
                            if($latest_Quantity <= $Reorder_Level)
                            {
                                echo "<p>Warning: Stock for Item ID $Item_ID is below reorder level. Current stock: $latest_Quantity, Reorder level: $Reorder_Level.</p>";
                            }
                        } else {
                            echo "<p>Error updating stock for Item ID: $Item_ID. ", mysqli_error($conn), "</p>";
                            break; // Exit the loop on error
                        }
                    } else {
                        echo "<p>Error adding sale: ", mysqli_error($conn), "</p>";
                        break; // Exit the loop on error
                    }
                } else {
                    echo "<p>Error: Product not found for Item ID '$Item_ID'.</p>";
                }
            } else {
                echo "<p>Error fetching product price: ", mysqli_error($conn), "</p>";
            }
        }
        echo "<h3>Sales successfully added!</h3>";
    }

    mysqli_close($conn);
}
?>

<a href="sales.php">
    <button>Return</button>
</a>