<?php
require_once 'database_check.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Goto Gro MRMS</title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
  <div class="container">
    <aside>
      <div class="top">
        <div class="logo">
          <img src="./images/logo.png" alt="Logo" />
          <h2>Goto<span class="danger">Gro</span></h2>
        </div>
        <div class="close" id="close-btn">
          <span class="material-icons-sharp"> close </span>
        </div>
      </div>

      <div class="sidebar">
        <a href="index.php" class="active">
          <span class="material-icons-sharp"> dashboard </span>
          <h3>Dashboard</h3>
        </a>
        <a href="members.php">
          <span class="material-icons-sharp"> person_outline </span>
          <h3>Members</h3>
        </a>

        <a href="inventory.php">
          <span class="material-icons-sharp"> inventory_2 </span>
          <h3>Inventory </h3>
        </a>

        <a href="sales.php">
          <span class="material-icons-sharp"> receipt_long </span>
          <h3>Sales</h3>
        </a>

        <a href="#">
          <span class="material-icons-sharp"> notifications </span>
          <h3>Notifications</h3>
          <span class="message-count">26</span>
        </a>
        <a href="#">
          <span class="material-icons-sharp"> insights </span>
          <h3>Analytics</h3>
        </a>
        <a href="#">
          <span class="material-icons-sharp"> feedback </span>
          <h3>Feedback</h3>
        </a>
        <a href="#">
          <span class="material-icons-sharp"> logout </span>
          <h3>Logout</h3>
        </a>
        <!----- EXTRA ----->
        <a href="#">
          <span class="material-icons-sharp"> report_gmailerrorred </span>
          <h3>Reports</h3>
        </a>
        <a href="#">
          <span class="material-icons-sharp"> settings </span>
          <h3>Settings</h3>
        </a>
      </div>
    </aside>

    <main>
      <h1>Dashboard</h1>

      <div class="insights">
        <!-- SALES -->
        <div class="sales">
          <span class="material-icons-sharp"> analytics </span>
          <div class="middle">
            <div class="left">
              <h3>Total Sales</h3>
              <h1>$25,024</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>81%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
        </div>

        <!-- EXPENSES -->
        <div class="expenses">
          <span class="material-icons-sharp"> bar_chart </span>
          <div class="middle">
            <div class="left">
              <h3>Total Expenses</h3>
              <h1>$14,160</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>62%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
        </div>

        <!-- INCOME -->
        <div class="income">
          <span class="material-icons-sharp"> stacked_line_chart </span>
          <div class="middle">
            <div class="left">
              <h3>Total Income</h3>
              <h1>$10,864</h1>
            </div>
            <div class="progress">
              <svg>
                <circle cx="38" cy="38" r="36"></circle>
              </svg>
              <div class="number">
                <p>44%</p>
              </div>
            </div>
          </div>
          <small class="text-muted"> Last 24 hours </small>
        </div>
      </div>


    </main>

    <div class="right">
      <div class="top">
        <button id="menu-btn">
          <span class="material-icons-sharp"> menu </span>
        </button>
        <div class="profile">
          <div class="info">
            <p>Hey, <b>Meow</b></p>
            <small class="text-muted">Admin</small>
          </div>
          <div class="profile-photo">
            <img src="./images/profile-1.jpg" alt="Profile Picture" />
          </div>
        </div>
      </div>
    </div>
    <script src="./index.js"></script>
  </div>
</body>

</html>