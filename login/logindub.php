<h1>Registration Form</h1>
                <form id="add" method="post" action="./Process_Add.php" class="member-form" novalidate="novalidate">
                    <!--Full Name-->
                    <div class="input-box">
                        <label>Full Name</label>
                        <input type="text" name="fullname" id="fullname" maxlength="50" pattern="^[a-zA-Z ]+$"
                            placeholder="Example: John Doe" required />
                        <section id="fullname_error" class="error"></section>
                    </div>
                    <!--Email Address-->
                    <div class="input-box">
                        <label>Email Address</label>
                        <input type="text" name="email" id="email" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                            placeholder="Example: name@domain.com" required />
                        <section id="email_error" class="error"></section>
                    </div>

                    <!--Merge Column-->
                    <div class="column">
                        <!--Phone Number-->
                        <div class="input-box">
                            <label>Phone Number</label>
                            <input type="tel" name="phonenum" id="phonenum" maxlength="12" pattern="[0-9 ]{8,12}"
                                placeholder="Example: 012 1234567" required />
                            <section id="phonenum_error" class="error"></section>
                        </div>

                        <!--Birth Date-->
                        <div class="input-box">
                            <label>Birth Date</label>
                            <input type="text" name="dob" id="dob" placeholder="dd/mm/yyyy" pattern="\d{1,2}\/\d{1,2}\/\d{4}"
                                placeholder="dd/mm/yyyy" required />
                            <section id="dob_error" class="error"></section>
                        </div>
                    </div>

                    <!--Gender Box-->
                    <div class="gender-box">
                        <h3>Gender</h3>
                        <div class="gender-option">
                            <!--Male-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-male" value="Male" />
                                <label for="check-male">Male</label>
                            </div>

                            <!--Female-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-female" value="Female" />
                                <label for="check-female">Female</label>
                            </div>

                            <!--Not to Say-->
                            <div class="gender">
                                <input type="radio" name="gender" id="check-others" value="Not-say" />
                                <label for="check-others">Prefer Not To Say</label>
                            </div>
                            <section id="gender_error" class="error"></section>
                        </div>
                    </div>

                    <!--Address Column-->
                    <div class="input-box address">

                        <!--Street Address-->
                        <label>Address</label>
                        <input type="text" name="streetaddress" id="streetaddress" maxlength="50" size="50"
                            pattern="[a-zA-Z ]{1,40}" placeholder="Enter your street address" required />
                        <section id="streetaddress_error" class="error"></section>

                    </div>
                    <div class="input-box">
                        <div class="column">
                            <div class="input-box">
                                <label>Country</label>
                                <div class="select-box addcountry-box">
                                    <select name="country" id="country" required>
                                        <option value="">Select your country</option>
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
                            </div>
                            <!--Country-->


                            <div class="input-box">
                                <label>State</label>
                                <input type="text" name="state" id="state" maxlength="50" size="50" placeholder="Example: Selangor" pattern="[a-zA-Z ]{1,40}" required />
                                <section id="state_error" class="error"></section>
                            </div>
                        </div>


                        <div class="column">
                            <!--City-->
                            <div class="input-box">
                                <label>City</label>
                                <input type="text" name="city" id="city" maxlength="50" size="50" pattern="[a-zA-Z ]{1,40}" placeholder="Example: Kuala Lumpur" required />
                                <section id="city_error" class="error"></section>
                            </div>
                            <!--Postcode-->
                            <div class="input-box">
                                <label>Postal Code</label>
                                <input type="text" name="postalcode" id="postalcode" maxlength="5" size="5" pattern="\d{5}" placeholder="Example: 45600" required />
                                <section id="postalcode_error" class="error"></section>
                            </div>
                        </div>
                    </div>
                    <button>Submit</button>
                </form>