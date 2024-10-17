<?php
    $updateInventory = file_get_contents("php://input");
    require_once('settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    $data = json_decode($updateInventory, true);

    $Previous_Item_ID = $data['Previous_Item_ID'];
    $Previous_Quantity = $data['Previous_Quantity'];
    $Current_Item_ID = $data['Current_Item_ID'];
    $Current_Quantity = $data['Current_Quantity'];

    $sql_add = "UPDATE inventory SET Quantity = Quantity + $Previous_Quantity WHERE Item_ID = '$Previous_Item_ID'";
    $sql_remove = "UPDATE inventory SET Quantity = Quantity - $Current_Quantity WHERE Item_ID = '$Current_Item_ID'";

    if ($conn->query($sql_add) && $conn->query($sql_remove)) {
        echo "Removed " . $Previous_Quantity . " of (" . $Previous_Item_ID . ") and Added " . $Current_Quantity . " of (" . $Current_Item_ID . ")";
    } else {
        echo "sql Failed";
    }
?>