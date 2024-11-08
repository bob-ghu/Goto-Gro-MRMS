<?php
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../login/login.php');
        exit;
    }
    $itemID = file_get_contents("php://input");
    require_once('../database/settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
        exit;
    }
    $sql = "SELECT * FROM inventory WHERE Item_ID = $itemID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the data as an associative array
        $row = $result->fetch_assoc();

        // Return the data as a JSON response
        echo json_encode([
            'Item_ID' => $row['Item_ID'],
            'Name' => $row['Name'],
            'Quantity' => $row['Quantity'],
            'Retail_Price' => $row['Retail_Price'],
            'Selling_Price' => $row['Selling_Price'],
            'Supplier' => $row['Supplier'],
            'Category' => $row['Category'],
            'Brand' => $row['Brand'],
            'Reorder_Level' => $row['Reorder_Level']
        ]);
    } else {
        echo json_encode([
            'error' => 'No inventory found'
        ]);
    }
?>