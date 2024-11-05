<?php
require_once("../database/settings.php"); // Connection info
$conn = @mysqli_connect($host, $user, $pwd);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to check if the database exists
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'gotogro_mrms'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    require_once '../database/database_check.php';
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Goto Gro MRMS</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="../styles/login.css" />
</head>

<body>
    <main>
        <div class="login-form-container">
            <h1>Staff Login</h1>
            <form action="login.php" method="post" id="loginform" class="login-form">
                <fieldset>
                    <div class="input-box">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="Username" maxlength="20" placeholder="Guest" required>
                        <small class="text-muted">Hint: Guest</small>
                    </div>
                    <div class="input-box">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="Password" maxlength="20" placeholder="guess" required>
                        <small class="text-muted">Hint: guess</small>
                    </div>
                    <div class="login-submit">
                        <button type="submit">Login</button>
                    </div>

                </fieldset>
            </form>
        </div>

        <?php
        session_start();

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        // Redirect if already logged in
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            header('Location: ../index/index.php');
            exit;
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $blockedDuration = 8; // Block duration in seconds

            if ($_SESSION['login_attempts'] >= 5) {
                if (!isset($_SESSION['loginBlockedTime'])) {
                    $_SESSION['loginBlockedTime'] = time(); // Start block timer
                }

                $timeElapsed = time() - $_SESSION['loginBlockedTime'];
                if ($timeElapsed >= $blockedDuration) {
                    // Reset attempts after block duration passes
                    $_SESSION['login_attempts'] = 0;
                    unset($_SESSION['loginBlockedTime']);
                } else {
                    // Display timer if within block duration
                    echo "<section class=\"form-timer\">
                            <p>Too many failed login attempts. Please try again in: </p>
                            <span id=\"minutes\">00</span>:
                            <span id=\"seconds\">00</span>
                        </section>";
                    echo "<script>formTimer(" . $blockedDuration - $timeElapsed . ")</script>";
                    exit;
                }
            }

            // Proceed with login check if not blocked
            $username = sanitize($_POST['Username']);
            $password = sanitize($_POST['Password']);

            require_once("../database/settings.php"); // Connection info
            $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

            if (!$conn) {
                echo "<p class=\"error\">Database connection failure.</p>";
            } else {
                // Query to search for records matching both Username and Password
                $query = "SELECT * FROM staff WHERE Username = '$username' AND Password = '$password'";
                $result = @mysqli_query($conn, $query);

                if (!$result) {
                    echo "<p>Something went wrong in the query.</p>";
                } else {
                    if ($result->num_rows > 0) { // Login successful
                        while ($row = $result->fetch_assoc()) {
                            $_SESSION["id"] = $row["id"];
                        }
                        $_SESSION['loggedin'] = true;
                        $_SESSION['login_attempts'] = 0;
                        header("Location: ../index/index.php");
                        exit;
                    } else { // Login failed
                        $_SESSION['login_attempts']++;
                        if ($_SESSION['login_attempts'] < 5) {
                            echo "<div class=\"error\">Invalid username or password.</div>"; // Enhanced error message
                        } else {
                            // Display timer immediately when reaching 5 attempts
                            $_SESSION['loginBlockedTime'] = time(); // Start block timer
                            echo "<div class=\"form-timer\">
                    <p>Too many failed login attempts. Please try again in:</p>
                    <span id=\"minutes\">00</span>:<span id=\"seconds\">00</span>
                </div>";
                            echo "<script>formTimer($blockedDuration)</script>";
                            exit;
                        }
                    }
                }
                mysqli_close($conn);
            }
        }

        // Sanitization function
        function sanitize($str)
        {
            $str = trim($str);
            $str = stripslashes($str);
            $str = preg_replace('/[[:cntrl:]]/', '', $str);
            $sanitized_str = preg_replace("/'/", '', $str);
            return $sanitized_str;
        }
        ?>


    </main>


    <script>
        function formTimer(seconds) {
            const minutesElement = document.getElementById("minutes"); // Get elements to change its content
            const secondsElement = document.getElementById("seconds");

            setInterval(updateTimer, 1000); // Set interval to every 1 second

            function updateTimer() {
                seconds--;
                if (seconds === 0) { // Timer runs out
                    window.location.href = window.location.href;
                }
                updateDisplay(); // Updates timer display every second
            }

            function updateDisplay() {
                let minutes = Math.floor(seconds / 60); // Takes only the integer part of the value
                let remainingSeconds = seconds % 60; // Takes the remainder of the value
                minutesElement.textContent = String(minutes).padStart(2, "0"); // Pads the rest of the value with 0 until 2 digits and displays the text
                secondsElement.textContent = String(remainingSeconds).padStart(2, "0");
            }
        }
    </script>

</body>

</html>