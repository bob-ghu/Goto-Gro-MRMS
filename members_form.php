<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GotoGro</title>
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
  <link rel="stylesheet" href="form.css">
  <script src="members.js"></script>
</head>
<body>
    <div class="container">
        <aside>
            <!-- Start Top -->
            <div class="top">
                <div class="logo">
                    <img src="./images/logo.png" alt="Logo" />
                    <h2>Goto<span class="danger">Gro</span></h2>
                </div>
                <div class="close" id="close_btn">
                    <span class="material-icons-sharp"> close </span>
                </div>
            </div>
            <!-- End Top -->

            <!--Sidebar-->
            <div class="sidebar">
                <!--Dashboard-->
                <a href="index.php">
                    <span class="material-icons-sharp">dashboard</span>
                    <h3>Dashbord</h3>
                </a>
                <!--Members-->
                <a href="members.php" class="active">
                    <span class="material-icons-sharp">person_outline</span>
                    <h3>Members</h3>
                </a>
                <!--Sales-->
                <a href="#">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Sales</h3>
                </a>
                <!--Inventory-->
                <a href="#">
                    <span class="material-icons-sharp">inventory_2</span>
                    <h3>Inventory</h3>
                </a>
                <!--Notification-->
                <a href="#">
                    <span class="material-icons-sharp">notifications</span>
                    <h3>Notification</h3>
                    <span class="message-count">26</span>
                </a>

                <a href="#">
                    <span class="material-icons-sharp"> insights </span>
                    <h3>Analytics</h3>
                  </a>
                <!--Customer Feedback-->
                <a href="#">
                    <span class="material-icons-sharp"> feedback </span>
                    <h3>Feedback</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">logout </span>
                    <h3>logout</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp"> report_gmailerrorred </span>
                    <h3>Reports</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">settings </span>
                    <h3>Settings</h3>
                </a>
            </div>
        </aside>

        <main>
            <div class="member-container">
                <!--Add Members Form-->
            <h1>Registration Form</h1>
            <form id="add" method="post" action="Process_Add.php" class="member-form" novalidate="novalidate">
                <!--Full Name-->
                <div class="input-box">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" maxlength="40" pattern="^[a-zA-Z ]+$" placeholder="Example: John Doe" required/>
                    <section id="fullname_error" class="error"></section>
                </div>

                <!--Email Address-->
                <div class="input-box">
                    <label>Email Address</label>
                    <input type="text" name="email" id="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" placeholder="Example: name@domain.com" required/>
                    <section id="email_error" class="error"></section>
                </div>
                
                <!--Merge Column-->
                <div class="column">
                    <!--Phone Number-->
                    <div class="input-box">
                        <label>Phone Number</label>
                        <input type="tel" name="phonenum" id="phonenum" maxlength="12" pattern="[0-9 ]{8,12}" placeholder="Example: 012 1234567" required/>
                        <section id="phonenum_error" class="error"></section>
                    </div>

                    <!--Birth Date-->
                    <div class="input-box">
                        <label>Birth Date</label>
                        <input type="text" name="dob" id="dob" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}" placeholder="dd/mm/yyyy" required/>
                        <section id="dob_error" class="error"></section>
                    </div>
                </div>

                <!--Gender Box-->
                <div class="gender-box">
                    <h3>Gender</h3>
                    <div class="gender-option">
                        <!--Male-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-male" value="male" />
                            <label for="check-male">Male</label>
                        </div>

                        <!--Female-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-female" value="female"/>
                            <label for="check-female">Female</label>
                        </div>

                        <!--Not to Say-->
                        <div class="gender">
                            <input type="radio" name="gender" id="check-others" value="not-say"/>
                            <label for="check-others">Prefer Not To Say</label>
                        </div>
                        <section id="gender_error" class="error"></section>
                    </div>
                </div>

                <!--Address Column-->
                <div class="input-box address">

                    <!--Street Address-->
                    <label>Address</label>
                    <input type="text" name="streetaddress1" id="streetaddress1" maxlength="40" size="40" pattern="[a-zA-Z ]{1,40}" placeholder="Street address line 1" required/>
                    <input type="text" name="streetaddress2" id="streetaddress2" maxlength="40" size="40" pattern="[a-zA-Z ]{1,40}" placeholder="Street address line 2"/>
                    <section id="streetaddress_error" class="error"></section>
                    
                    <br>
                    <!--Country-->
                    <label>Country</label>
                    <div class="select-box">
                        <select name="country" id="country" required>
                            <option value="">Select a Country</option>
                            <option value="canada">Canada</option>
                            <option value="usa">USA</option>
                            <option value="japan">Japan</option>
                            <option value="india">India</option>
                            <option value="malaysia">Malaysia</option>
                            <option value="singapore">Singapore</option>
                            <option value="southkorea">South Korea</option>
                            <option value="myanmar">Myanmar</option>
                            <option value="vietnam">Vietnam</option>
                            <option value="brunei">Brunei</option>
                            <option value="china">China</option>
                            <option value="sweden">Sweden</option>
                            <option value="france">France</option>
                            <option value="germany">Germany</option>
                        </select>
                    </div>
                    <section id="country_error" class="error"></section>
                    <div class="column">
                        <!--City-->
                        <div class="input-box">
                            <label>City</label>
                            <input type="text" name="city" id="city" maxlength="40" size="40" pattern="[a-zA-Z ]{1,40}" placeholder="Example: Kuala Lumpur" required />
                            <section id="city_error" class="error"></section>
                        </div>
                        <!--Postcode-->
                        <div class="input-box">
                            <label>Postal Code</label>
                            <input type="text" name="postalcode" id="postalcode" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" required/>
                            <section id="postalcode_error" class="error"></section>
                        </div>
                        
                    </div>
                </div>
            <button>Submit</button>
        </form>
            </div>
        </main>
    </div>
</body>
</html>
    