//const validation = false;

"use strict";

function validate() { // Function to validate all data

    var result = true; // Initialise result as true

    // Full Name
    var fullname = document.getElementById("fullname").value;
    var fullname_error = document.getElementById("fullname_error");
    fullname_error.innerHTML = "";
    if (fullname == "") {
        fullname_error.innerHTML = "Please enter your full name.\n";
        document.getElementById("fullname").style.borderColor = "red";
        result = false;
    }
    else if (!fullname.match(/^[a-zA-Z ]+$/)) {
        fullname_error.innerHTML = "Invalid name. Please use letters only.\n";
        document.getElementById("fullname").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("fullname").style.borderColor = "green";
    }

    // Email
    var email = document.getElementById("email").value;
    var email_error = document.getElementById("email_error");
    email_error.innerHTML = "";
    if (email == "") {
        email_error.innerHTML = "Please enter your email.\n";
        document.getElementById("email").style.borderColor = "red";
        result = false;
    }
    else if (!email.match(/[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/)) {
        email_error.innerHTML = "Email is invalid.\n";
        document.getElementById("email").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("email").style.borderColor = "green";
    }

    // Phone Number
    var phonenum = document.getElementById("phonenum").value;
    var phonenum_error = document.getElementById("phonenum_error");
    phonenum_error.innerHTML = "";
    if (phonenum == "") {
        phonenum_error.innerHTML = "Please enter your phone number.\n";
        document.getElementById("phonenum").style.borderColor = "red";
        result = false;
    }
    else if (!phonenum.match(/[0-9 ]{8,12}/)) {
        phonenum_error.innerHTML = "Phone number is invalid.\n";
        document.getElementById("phonenum").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("phonenum").style.borderColor = "green";
    }

    // Date of Birth
    var dob = document.getElementById("dob").value;
    var dob_error = document.getElementById("dob_error");
    dob_error.innerHTML = "";
    if (!dob.match(/^(0[1-9]|[12][0-9]|3[01])[\/\-](0[1-9]|1[0-2])[\/\-]\d{4}$/)) {
        dob_error.innerHTML = "Date of birth is invalid.\n";
        document.getElementById("dob").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("dob").style.borderColor = "green";
    }

    // Gender
    var male = document.getElementById("check-male").checked;
    var female = document.getElementById("check-female").checked;
    var others = document.getElementById("check-others").checked;
    var gender_error = document.getElementById("gender_error");
    gender_error.innerHTML = "";
    if(!(male || female || others)) {
        gender_error.innerHTML = "Please select a gender.\n";
        document.getElementById("check-male").style.borderColor = "red";
        document.getElementById("check-female").style.borderColor = "red";
        document.getElementById("check-others").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("check-male").style.borderColor = "green";
        document.getElementById("check-female").style.borderColor = "green";
        document.getElementById("check-others").style.borderColor = "green";
    }

    // Street Address
    var streetaddress = document.getElementById("streetaddress").value;
    var streetaddress_error = document.getElementById("streetaddress_error");
    streetaddress_error.innerHTML = "";
    if (streetaddress == "") {
        streetaddress_error.innerHTML = "Please enter your street address.\n";
        document.getElementById("streetaddress").style.borderColor = "red";
        result = false;
    }
    else if (!streetaddress.match(/[a-zA-Z0-9 ]{1,40}/)) {
        streetaddress_error.innerHTML = "Street address is invalid.\n";
        document.getElementById("streetaddress").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("streetaddress").style.borderColor = "green";
    }

    // Country
    var country = document.getElementById("country").value;
    var country_error = document.getElementById("country_error");
    country_error.innerHTML = "";
    if (country == "") {
        country_error.innerHTML = "You must select a country.\n";
        Array.from(document.getElementsByClassName("select-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("select-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // City
    var city = document.getElementById("city").value;
    var city_error = document.getElementById("city_error");
    city_error.innerHTML = "";
    if (city == "") {
        city_error.innerHTML = "Please enter your city.\n";
        document.getElementById("city").style.borderColor = "red";
        result = false;
    }
    else if (!city.match(/[a-zA-Z ]{1,40}/)) {
        city_error.innerHTML = "City is invalid.\n";
        document.getElementById("city").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("city").style.borderColor = "green";
    }

    // Postal Code
    var postalcode = document.getElementById("postalcode").value;
    var postalcode_error = document.getElementById("postalcode_error");
    postalcode_error.innerHTML = "";
    if (postalcode == "") {
        postalcode_error.innerHTML = "Please enter your postal code.\n";
        document.getElementById("postalcode").style.borderColor = "red";
        result = false;
    }
    else if (!postalcode.match(/^\d{5}$/)) {
        postalcode_error.innerHTML = "Postal code is invalid. Please use 5 digits only.\n";
        document.getElementById("postalcode").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("postalcode").style.borderColor = "green";
    }

    return result; // Return the overall validation result
}

function init() {
    var applyForm = document.getElementById("add");
    applyForm.onsubmit = validate;

    /*if (validation) {
        var applyForm = document.getElementById("add");
        applyForm.onsubmit = validate;
    }*/
}

window.onload = init;