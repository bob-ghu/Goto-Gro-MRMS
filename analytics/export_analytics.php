<?php
require_once('../database/settings.php'); // Include your database settings

// Create a connection
$conn = new mysqli($host, $user, $pwd, $sql_db);
date_default_timezone_set('Asia/Kuala_Lumpur');
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CSV generation based on user selection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['report_type'])) {
        $report_type = $_POST['report_type'];
        $range = $_POST['range'];
        
        $specific = "";
        if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $_POST['specific'])) {
            $specific = "month";
        }
        elseif (preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $_POST['specific'])) {
            $specific = "date";
        }

        $timestamp = date('Ymd'); // Format: YYYYMMDD
        if ($report_type == 'member_sales') {
            $filename = "member_sales_report_$timestamp.csv";
        } elseif ($report_type == 'time_period_sales') {
            $filename = "time_period_sales_report_$timestamp.csv";
        }

        // Set headers to download the file as CSV
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename");

        // Open output stream to write CSV
        $output = fopen('php://output', 'w');

        // ----------------------------------------------------------------------------
        // Generate CSV for member sales data
        if ($report_type == 'member_sales') {
            // Write the first CSV table column headers for member sales data
            fputcsv($output, [
                'Member Name', 
                'Total Sales', 
                'Total Quantity Sold', 
                'First Sale Date', 
                'Last Sale Date', 
                'Most Purchased Item', 
                'Item Quantity', 
                'Item Category'
            ]);

            // Query to fetch aggregated sales data for members
            $sql = "
            SELECT 
                members.Full_Name AS Member_Name, 
                SUM(sales.Total_Price) AS Total_Sales, 
                SUM(sales.Quantity) AS Total_Quantity_Sold, 
                MIN(sales.Sale_Date) AS First_Sale_Date, 
                MAX(sales.Sale_Date) AS Last_Sale_Date
            FROM 
                sales 
            JOIN 
                members ON sales.Member_ID = members.Member_ID 
            GROUP BY 
                members.Full_Name
            ";

            $result = $conn->query($sql);

            // Fetching the first table data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // For each member, fetch the most purchased item and its quantity
                    $item_sql = "
                    SELECT 
                        inventory.Name AS Most_Purchased_Item,
                        SUM(sales.Quantity) AS Item_Quantity,
                        inventory.Category AS Item_Category
                    FROM 
                        sales 
                    JOIN 
                        inventory ON sales.Item_ID = inventory.Item_ID 
                    WHERE 
                        sales.Member_ID = (SELECT Member_ID FROM members WHERE Full_Name = ?) 
                    GROUP BY 
                        inventory.Name 
                    ORDER BY 
                        Item_Quantity DESC 
                    LIMIT 1";

                    $stmt = $conn->prepare($item_sql);
                    $stmt->bind_param("s", $row['Member_Name']);
                    $stmt->execute();
                    $item_result = $stmt->get_result();

                    if ($item_row = $item_result->fetch_assoc()) {
                        fputcsv($output, [
                            $row['Member_Name'],
                            $row['Total_Sales'],
                            $row['Total_Quantity_Sold'],
                            $row['First_Sale_Date'],
                            $row['Last_Sale_Date'],
                            $item_row['Most_Purchased_Item'],
                            $item_row['Item_Quantity'],
                            $item_row['Item_Category']
                        ]);
                    } else {
                        // Fill with empty values if no items found
                        fputcsv($output, [
                            $row['Member_Name'],
                            $row['Total_Sales'],
                            $row['Total_Quantity_Sold'],
                            $row['First_Sale_Date'],
                            $row['Last_Sale_Date'],
                            'None',
                            0,
                            'N/A'
                        ]);
                    }
                }
            } else {
                fputcsv($output, ['No analytics data available.']);
            }

            // Add an empty row to separate sections
            fputcsv($output, []);
        }

        // ----------------------------------------------------------------------------
        // Generate CSV for time period sales data
        elseif ($report_type == 'time_period_sales') {
            if ($range == "daily") {
                // Daily Sales Data
                fputcsv($output, ['Daily Sales Data']);
                fputcsv($output, ['Item Name', 'Total Sold Quantity', 'Total Revenue', 'Category']);

                // Query to fetch daily sales data
                $date_filter_daily = "DATE(sales.Sale_Date) = CURDATE()"; // Today's sales
                $sql_daily = "
                SELECT 
                    inventory.Name AS Item_Name, 
                    SUM(sales.Quantity) AS Total_Sold_Quantity, 
                    SUM(sales.Total_Price) AS Total_Revenue,
                    inventory.Category AS Category
                FROM 
                    sales 
                JOIN 
                    inventory ON sales.Item_ID = inventory.Item_ID 
                WHERE 
                    $date_filter_daily
                GROUP BY 
                    inventory.Name
                ORDER BY 
                    Total_Sold_Quantity DESC
                ";

                $item_result_daily = $conn->query($sql_daily);

                // Fetching the daily sales data
                if ($item_result_daily->num_rows > 0) {
                    while ($item_row_daily = $item_result_daily->fetch_assoc()) {
                        fputcsv($output, [
                            $item_row_daily['Item_Name'],
                            $item_row_daily['Total_Sold_Quantity'],
                            $item_row_daily['Total_Revenue'],
                            $item_row_daily['Category']
                        ]);
                    }
                } else {
                    fputcsv($output, ['No daily sales data available.', '', '', '']);
                }

                // Add an empty row to separate sections
                fputcsv($output, []);
            }

            if ($range == "weekly") {
                // Weekly Sales Data
                fputcsv($output, ['Weekly Sales Data']);
                fputcsv($output, ['Item Name', 'Total Sold Quantity', 'Total Revenue', 'Category']);

                // Query to fetch weekly sales data
                $date_filter_weekly = "WEEK(sales.Sale_Date, 1) = WEEK(CURDATE(), 1) AND YEAR(sales.Sale_Date) = YEAR(CURDATE())"; // This week's sales (Week starting on Monday)
                $sql_weekly = "
                SELECT 
                    inventory.Name AS Item_Name, 
                    SUM(sales.Quantity) AS Total_Sold_Quantity, 
                    SUM(sales.Total_Price) AS Total_Revenue,
                    inventory.Category AS Category
                FROM 
                    sales 
                JOIN 
                    inventory ON sales.Item_ID = inventory.Item_ID 
                WHERE 
                    $date_filter_weekly
                GROUP BY 
                    inventory.Name
                ORDER BY 
                    Total_Sold_Quantity DESC
                ";

                $item_result_weekly = $conn->query($sql_weekly);

                // Fetching the weekly sales data
                if ($item_result_weekly->num_rows > 0) {
                    while ($item_row_weekly = $item_result_weekly->fetch_assoc()) {
                        fputcsv($output, [
                            $item_row_weekly['Item_Name'],
                            $item_row_weekly['Total_Sold_Quantity'],
                            $item_row_weekly['Total_Revenue'],
                            $item_row_weekly['Category']
                        ]);
                    }
                } else {
                    fputcsv($output, ['No weekly sales data available.', '', '', '']);
                }

                // Add an empty row to separate sections
                fputcsv($output, []);
            }

            if ($range == "monthly") {
                // Monthly Sales Data
                fputcsv($output, ['Monthly Sales Data']);
                fputcsv($output, ['Item Name', 'Total Sold Quantity', 'Total Revenue', 'Category']);

                // Query to fetch monthly sales data
                $date_filter_monthly = "MONTH(sales.Sale_Date) = MONTH(CURDATE()) AND YEAR(sales.Sale_Date) = YEAR(CURDATE())"; // This month's sales
                $sql_monthly = "
                SELECT 
                    inventory.Name AS Item_Name, 
                    SUM(sales.Quantity) AS Total_Sold_Quantity, 
                    SUM(sales.Total_Price) AS Total_Revenue,
                    inventory.Category AS Category
                FROM 
                    sales 
                JOIN 
                    inventory ON sales.Item_ID = inventory.Item_ID 
                WHERE 
                    $date_filter_monthly
                GROUP BY 
                    inventory.Name
                ORDER BY 
                    Total_Sold_Quantity DESC
                ";

                $item_result_monthly = $conn->query($sql_monthly);

                // Fetching the monthly sales data
                if ($item_result_monthly->num_rows > 0) {
                    while ($item_row_monthly = $item_result_monthly->fetch_assoc()) {
                        fputcsv($output, [
                            $item_row_monthly['Item_Name'],
                            $item_row_monthly['Total_Sold_Quantity'],
                            $item_row_monthly['Total_Revenue'],
                            $item_row_monthly['Category']
                        ]);
                    }
                } else {
                    fputcsv($output, ['No monthly sales data available.', '', '', '']);
                }
            }
            
            if ($specific == "date") {
                fputcsv($output, ['Specific Date Sales Data']);
                fputcsv($output, ['Item Name', 'Total Sold Quantity', 'Total Revenue', 'Category']);

                // Query to fetch sales data for specific date
                $sql_date = "
                SELECT 
                    inventory.Name AS Item_Name, 
                    SUM(sales.Quantity) AS Total_Sold_Quantity, 
                    SUM(sales.Total_Price) AS Total_Revenue,
                    inventory.Category AS Category
                FROM 
                    sales 
                JOIN 
                    inventory ON sales.Item_ID = inventory.Item_ID 
                WHERE 
                    DATE(sales.Sale_Date) = ?
                GROUP BY 
                    inventory.Name
                ORDER BY 
                    Total_Sold_Quantity DESC
                ";

                $stmt = $conn->prepare($sql_date);
                $stmt->bind_param("s", $_POST['specific']);  // Binding the specific date
                $stmt->execute();
                $item_result_date = $stmt->get_result();

                // Fetching the specific date sales data
                if ($item_result_date->num_rows > 0) {
                    while ($item_row_date = $item_result_date->fetch_assoc()) {
                        fputcsv($output, [
                            $item_row_date['Item_Name'],
                            $item_row_date['Total_Sold_Quantity'],
                            $item_row_date['Total_Revenue'],
                            $item_row_date['Category']
                        ]);
                    }
                } else {
                    fputcsv($output, ['No sales data available for the selected date.', '', '', '']);
                }

                // Add an empty row to separate sections
                fputcsv($output, []);
            }

            if ($specific == "month") {
                fputcsv($output, ['Specific Month Sales Data']);
                fputcsv($output, ['Item Name', 'Total Sold Quantity', 'Total Revenue', 'Category']);

                // Extract the month and year from the 'yyyy-mm' format
                list($year, $month) = explode('-', $_POST['specific']);

                // SQL query with the extracted month and year
                $sql_month = "
                    SELECT 
                        inventory.Name AS Item_Name, 
                        SUM(sales.Quantity) AS Total_Sold_Quantity, 
                        SUM(sales.Total_Price) AS Total_Revenue,
                        inventory.Category AS Category
                    FROM 
                        sales 
                    JOIN 
                        inventory ON sales.Item_ID = inventory.Item_ID 
                    WHERE 
                        MONTH(sales.Sale_Date) = ? AND YEAR(sales.Sale_Date) = ?
                    GROUP BY 
                        inventory.Name
                    ORDER BY 
                        Total_Sold_Quantity DESC
                ";

                // Prepare the statement and bind the extracted month and year
                $stmt = $conn->prepare($sql_month);
                $stmt->bind_param("ii", $month, $year);  // Bind as integers
                $stmt->execute();
                $item_result_month = $stmt->get_result();

                // Fetch and output the results
                if ($item_result_month->num_rows > 0) {
                    while ($item_row_month = $item_result_month->fetch_assoc()) {
                        fputcsv($output, [
                            $item_row_month['Item_Name'],
                            $item_row_month['Total_Sold_Quantity'],
                            $item_row_month['Total_Revenue'],
                            $item_row_month['Category']
                        ]);
                    }
                } else {
                    fputcsv($output, ['No sales data available for the selected month.', '', '', '']);
                }

                // Add an empty row to separate sections
                fputcsv($output, []);
            }
        }

        // Close the output stream
        fclose($output);
    }
}

// Close database connection
$conn->close();
?>