<?php
    $salesID = file_get_contents("php://input");
    require_once('settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    $sql = "SELECT sales.Sales_ID, members.Full_Name AS Member_Name, inventory.Name AS Item_Name, sales.Quantity, sales.Payment_Method, sales.Staff_ID 
            FROM sales JOIN members ON sales.Member_ID = members.Member_ID JOIN inventory ON sales.Item_ID = inventory.Item_ID WHERE Sales_ID = $salesID";
    $sql_members = "SELECT Full_Name FROM members";
    $sql_inventory = "SELECT Name FROM inventory";
    $result = $conn->query($sql);
    $result_members = $conn->query($sql_members);
    $result_inventory = $conn->query($sql_inventory);

    if ($result->num_rows > 0 && $result_members->num_rows > 0 && $result_inventory->num_rows > 0) {

        $row = $result->fetch_assoc();
        // Encode the data as JSON
        $json_request = json_encode([
            'Member_Name' => $row['Member_Name'],
            'Item_Name' => $row['Item_Name'],
            'Quantity' => $row['Quantity'],
            'Payment_Method' => $row['Payment_Method'],
            'Staff_ID' => $row['Staff_ID']
        ]);

        // Fetch the data as an associative array
        $rows_members = array();
        while($row_members = $result_members->fetch_assoc()) {
            $rows_members[] = $row_members;
        }
        $json_members = json_encode($rows_members, JSON_PRETTY_PRINT);

        $rows_inventory = array();
        while($row_inventory = $result_inventory->fetch_assoc()) {
            $rows_inventory[] = $row_inventory;
        }
        $json_inventory = json_encode($rows_inventory, JSON_PRETTY_PRINT);

        $response = [
            'Request_Data' => json_decode($json_request),
            'Members_Table' => json_decode($json_inventory),
            'Inventory_Table' => json_decode($json_members)
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);

    } else {
        echo json_encode([
            'error' => 'No sales found'
        ]);
    }
?>