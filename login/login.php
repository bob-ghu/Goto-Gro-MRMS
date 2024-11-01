<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Goto Gro MRMS</title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="../styles/style.css" />
</head>
<body>
    <h1>Staff Login</h1>
    <form action="login.php" method="post" class="login-form">
        <legend>Login</legend>
        <section class="username-password">
            <section>
                <p><label for="username">Username:</label></p>
                <p><input type="text" id="username" name="Username" maxlength="20" size="24" placeholder="SiewMG" required></p>
                <p class="hint">Hint: SiewMG</p>
            </section>
            <section>
                <p><label for="password">Password:</label></p>
                <p><input type="password" id="password" name="Password" maxlength="20" size="24" placeholder="Password" required></p>
                <p class="hint">Hint: Password</p>
            </section>
        </section>
        <section class="login-submit">
            <input id=login type="submit" value="Login">
        </section>
    </form>
    <?php
        session_start(); // Start the session

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { // If user has logged in once during session, they don't have to login again
            header('Location: ../index/index.php');
            exit;
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_SESSION['login_attempts'] < 5) {
                // Get the form data
                $username = sanitize($_POST['Username']);
                $password = sanitize($_POST['Password']);

                require_once ("../database/settings.php"); // Connection info

                $conn = @mysqli_connect($host, $user, $pwd, $sql_db); // Create a connection to the database
                
                if (!$conn) { 
                    echo "<p class=\"error\">Database connection failure.</p>";
                }
                else {
                    // Query to search for records matching both Username and Password
                    $query = "SELECT * FROM staff WHERE Username = '$username' AND Password = '$password'";
                    $result =  @mysqli_query($conn, $query);
                    if (!$result) {
                        echo "<p>Something went wrong in the query.</p>";
                    } else {
                        if ($result->num_rows > 0) { // Checks if any records match the query
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {
                                // Store session data
                                $_SESSION["id"] = $row["id"];
                            }
                            $_SESSION['loggedin'] = true; // User won't have to login again until they logout in the same session

                            header("Location: ../index/index.php"); // Redirect to EOI Management Page
                            exit;
                        } else {
                            echo "Invalid username or password.";
                            $_SESSION['login_attempts']++;
                        }
                    }
                    mysqli_close($conn); // Close the database connection
                }
            }
            else {
                if (!isset($_SESSION['loginBlockedTime'])) {
                    $_SESSION['loginBlockedTime'] = time();
                }
                else if (abs($_SESSION['loginBlockedTime'] - time()) >= 30) {
                    $_SESSION['login_attempts'] = 0;
                    unset($_SESSION['loginBlockedTime']);
                }
                else {
                    echo "login blocked";
                    echo "Wait for" . abs($_SESSION['loginBlockedTime'] + 30 - time()) . "seconds";
                }
            }
        }

        function sanitize($str) { // Removes trailing/trailing spaces, backslashes and HTML control characters.
            $str = trim($str); // Removes leading and trailing spaces,
            $str = stripslashes($str); // Removes backslashes
            $str = preg_replace('/[[:cntrl:]]/', '', $str); // Removes HTML control characters
            $sanitized_str = preg_replace("/'/", '', $str); // Removes quotation marks
            return $sanitized_str;
        }
    ?>
</body>
</html>