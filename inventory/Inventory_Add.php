<?php 
    if (!isset($_POST["name"])){
        header("Location: ./inventory.php"); 
        exit; 
    }

    require_once('../database/settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        echo "<p>Database connection failure</p>";
        exit;
    } else {
        function sanitise_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $errMsg = "";

        // Stock Name
        if (isset($_POST["name"])) {
            $Stock_Name = $_POST["name"];
            $Stock_Name = sanitise_input($Stock_Name);
        }
        if ($Stock_Name == "") {
            $errMsg .= "<p>You must enter the stock name.</p>";
        }
        else if (!preg_match("/^[a-zA-Z ]*$/", $Stock_Name)) {
            $errMsg .= "<p>Only letters are allowed in the stock name.</p>";
        }

        // Quantity
        if (isset($_POST["quantity"])) {
            $Quantity = $_POST["quantity"];
            $Quantity = sanitise_input($Quantity);
        }
        
        if ($Quantity == "") {
            $errMsg .= "<p>You must enter the quantity.</p>";
        }
        else if (!preg_match("/^\d{1,5}$/", $Quantity)) {
            $errMsg .= "<p>Quantity is invalid.</p>";
        }

        // Retail Price
        if (isset($_POST["retail_price"])) {
            $Retail_Price = $_POST["retail_price"];
            $Retail_Price = sanitise_input($Retail_Price);
        }

        if ($Retail_Price == "") {
            $errMsg .= "<p>You must enter the retail price.</p>";
        }
        else if (!preg_match("/^\d{1,8}(\.\d{2})?$/", $Retail_Price)) {
            $errMsg .= "<p>Retail price is invalid. It must be up to 8 digits with optional 2 decimal places.</p>";
        }

        // Selling Price
        if (isset($_POST["selling_price"])) {
            $Selling_Price = $_POST["selling_price"];
            $Selling_Price = sanitise_input($Selling_Price);
        }

        if ($Selling_Price == "") {
            $errMsg .= "<p>You must enter the selling price.</p>";
        }
        else if (!preg_match("/^\d{1,8}(\.\d{2})?$/", $Selling_Price)) {
            $errMsg .= "<p>Selling price is invalid. It must be up to 8 digits with optional 2 decimal places.</p>";
        }

        // Supplier Name
        if (isset($_POST["supplier_input"])) {
            $Supplier = $_POST["supplier_input"];
            $Supplier = sanitise_input($Supplier);
        }
        if ($Supplier == "") {
            $errMsg .= "<p>You must enter the supplier name.</p>";
        }
        else if (!preg_match("/^[a-zA-Z ]*$/", $Supplier)) {
            $errMsg .= "<p>Only letters are allowed in the supplier name.</p>";
        }

        // Category
        if (isset($_POST["category_input"])) {
            $Category = $_POST["category_input"];
            $Category = sanitise_input($Category);
        }        

        if ($Category == "") {
            $errMsg .= "<p>You must select a category.</p>";
        }
        else if (!preg_match("/^[a-zA-Z ]*$/", $Category)) {
            $errMsg .= "<p>Only letters are allowed in the category.</p>";
        }

        // Brand Name
        if (isset($_POST["brand"])) {
            $Brand = $_POST["brand"];
            $Brand = sanitise_input($Brand);
        }
        if ($Brand == "") {
            $errMsg .= "<p>You must enter the brand name.</p>";
        }
        else if (!preg_match("/^[a-zA-Z ]*$/", $Brand)) {
            $errMsg .= "<p>Only letters are allowed in the brand name.</p>";
        }

        // Reorder Level
        if (isset($_POST["reorder"])) {
            $Reorder = $_POST["reorder"];
            $Reorder = sanitise_input($Reorder);
        }
        
        if ($Reorder == "") {
            $errMsg .= "<p>You must enter the reorder level.</p>";
        }
        else if (!preg_match("/^\d{1,5}$/", $Reorder)) {
            $errMsg .= "<p>Reorder level is invalid.</p>";
        }

        // Display Error Message
        if ($errMsg != "") {
            echo $errMsg;
        }
        else {
            // Insert
            $sql = "INSERT INTO inventory (Name, Quantity, Retail_Price, Selling_Price, Supplier, Category, Brand, Reorder_Level) 
            VALUES ('$Stock_Name', '$Quantity', '$Retail_Price', '$Selling_Price', '$Supplier', '$Category', '$Brand', '$Reorder')";

            $Item_ID = mysqli_insert_id($conn);
            $query = mysqli_query($conn, $sql);
            if(!$query) {
                echo "<p class=\"wrong\">Something is wrong with the query: ", mysqli_error($conn), "</p>";
            } else {
                echo "<h3 class=\"ok\">Application is successfully submitted.</h3>";
                
                $sql = "SELECT * FROM inventory WHERE Item_ID = '$Item_ID'";
                $query = mysqli_query($conn, $sql);
                
                if (!$query) {
                    die('Invalid query: ' . mysqli_error($conn));
                }
                
                $row = mysqli_fetch_array($query);
                
                if($row === false) {
                    die('No rows returned.');
                }
            }
        }
        mysqli_close($conn);
    }
?>

<a href="./inventory.php">
    <button>Return</button>
</a>
