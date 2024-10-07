<?php 

    if (!isset ($_POST["fullname"])){
        header ("Location: members.html"); 
        exit; 
    }

    require_once('settings.php');

    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        echo "<p>Database connection failure</p>";
        exit;
    }

    $Full_Name = $_POST['fullname'];
    $Email_Address = $_POST["email"];
    $Phone_Number = $_POST["phonenum"];
    $DOB = $_POST["dob"];
    $Gender = $_POST["gender"];
    $Street_Address1 = $_POST["streetaddress1"];
    $Street_Address2 = isset($_POST["streetaddress2"]) ? $_POST["streetaddress2"] : '';
    $City = $_POST["city"];
    $Country = $_POST["country"];
    $Region = $_POST["region"];
    $Postal_Code = $_POST["postalcode"];

    $Street_Address = $Street_Address1;
    if (!empty($Street_Address2)) {
        $Street_Address .= ' ' . $Street_Address2;
    }

    $sql = "INSERT INTO member (Full_name, Email_Address, Phone_Number, DOB, Gender, Street_Address, City, Country, Region, Postal_Code) 
    VALUES ('$Full_Name', '$Email_Address', '$Phone_Number', '$DOB', '$Gender', '$Street_Address', '$City', '$Country', '$Region', '$Postal_Code')";

    $Member_ID = mysqli_insert_id($conn);
    $query = mysqli_query($conn, $sql);
    if(!$query) {
        echo "<p class=\"wrong\">Something is wrong with the query: ", mysqli_error($conn), "</p>";
    } else {
        echo "<h3 class=\"ok\">Application is successfully submitted.</h3>";

    
        
        $sql = "SELECT * FROM member WHERE Member_ID = '$Member_ID'";
        $query = mysqli_query($conn, $sql);
        
        if (!$query) {
            die('Invalid query: ' . mysqli_error($conn));
        }
        
        $row = mysqli_fetch_array($query);
        
        if($row === false) {
            die('No rows returned.');
        }

    

    }

    mysqli_close($conn);
?>

