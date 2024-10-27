"use strict";

function validateAdd() { // Function to validate all data

    var result = true; // Initialise result as true

    // Full Name
    var fullname = document.getElementById("fullname").value.trim();
    var fullname_error = document.getElementById("fullname_error");
    fullname_error.innerHTML = "";
    if (fullname == "") {
        fullname_error.innerHTML = "Please enter your full name.\n";
        document.getElementById("fullname").style.borderColor = "red";
        result = false;
    }
    else if (!fullname.match(/^[a-zA-Z ]{1,50}$/)) {
        fullname_error.innerHTML = "Invalid name. Please use letters only.\n";
        document.getElementById("fullname").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("fullname").style.borderColor = "green";
    }

    // Email
    var email = document.getElementById("email").value.trim();
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
    var phonenum = document.getElementById("phonenum").value.trim();
    var phonenum_error = document.getElementById("phonenum_error");
    phonenum_error.innerHTML = "";
    if (phonenum == "") {
        phonenum_error.innerHTML = "Please enter your phone number.\n";
        document.getElementById("phonenum").style.borderColor = "red";
        result = false;
    }
    else if (!phonenum.match(/^[0-9 ]{8,12}$/)) {
        phonenum_error.innerHTML = "Phone number is invalid.\n";
        document.getElementById("phonenum").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("phonenum").style.borderColor = "green";
    }

    // Date of Birth
    var dob = document.getElementById("dob").value.trim();
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
    var streetaddress = document.getElementById("streetaddress").value.trim();
    var streetaddress_error = document.getElementById("streetaddress_error");
    streetaddress_error.innerHTML = "";
    if (streetaddress == "") {
        streetaddress_error.innerHTML = "Please enter your street address.\n";
        document.getElementById("streetaddress").style.borderColor = "red";
        result = false;
    }
    else if (!streetaddress.match(/[a-zA-Z0-9 ]{1,50}/)) {
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
        Array.from(document.getElementsByClassName("addcountry-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("addcountry-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // State
    var state = document.getElementById("state").value.trim();
    var state_error = document.getElementById("state_error");
    state_error.innerHTML = "";
    if (state == "") {
        state_error.innerHTML = "Please enter your state.\n";
        document.getElementById("state").style.borderColor = "red";
        result = false;
    }
    else if (!state.match(/^[a-zA-Z ]{1,50}$/)) {
        state_error.innerHTML = "State is invalid.\n";
        document.getElementById("state").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("state").style.borderColor = "green";
    }

    // City
    var city = document.getElementById("city").value.trim();
    var city_error = document.getElementById("city_error");
    city_error.innerHTML = "";
    if (city == "") {
        city_error.innerHTML = "Please enter your city.\n";
        document.getElementById("city").style.borderColor = "red";
        result = false;
    }
    else if (!city.match(/^[a-zA-Z ]{1,50}$/)) {
        city_error.innerHTML = "City is invalid.\n";
        document.getElementById("city").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("city").style.borderColor = "green";
    }

    // Postal Code
    var postalcode = document.getElementById("postalcode").value.trim();
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

function validateEdit() { // Function to validate all data
    
    var result = true; // Initialise result as true

    // Full Name
    var fullname = document.getElementById("fullname_edit").value.trim();
    var fullname_error = document.getElementById("fullname_edit_error");
    fullname_error.innerHTML = "";
    if (fullname == "") {
        fullname_error.innerHTML = "Please enter your full name.\n";
        document.getElementById("fullname_edit").style.borderColor = "red";
        result = false;
    }
    else if (!fullname.match(/^[a-zA-Z ]{1,50}$/)) {
        fullname_error.innerHTML = "Invalid name. Please use letters only.\n";
        document.getElementById("fullname_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("fullname_edit").style.borderColor = "green";
    }

    // Email
    var email = document.getElementById("email_edit").value.trim();
    var email_error = document.getElementById("email_edit_error");
    email_error.innerHTML = "";
    if (email == "") {
        email_error.innerHTML = "Please enter your email.\n";
        document.getElementById("email_edit").style.borderColor = "red";
        result = false;
    }
    else if (!email.match(/[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/)) {
        email_error.innerHTML = "Email is invalid.\n";
        document.getElementById("email_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("email_edit").style.borderColor = "green";
    }

    // Phone Number
    var phonenum = document.getElementById("phonenum_edit").value.trim();
    var phonenum_error = document.getElementById("phonenum_edit_error");
    phonenum_error.innerHTML = "";
    if (phonenum == "") {
        phonenum_error.innerHTML = "Please enter your phone number.\n";
        document.getElementById("phonenum_edit").style.borderColor = "red";
        result = false;
    }
    else if (!phonenum.match(/^[0-9 ]{8,12}$/)) {
        phonenum_error.innerHTML = "Phone number is invalid.\n";
        document.getElementById("phonenum_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("phonenum_edit").style.borderColor = "green";
    }

    // Date of Birth
    var dob = document.getElementById("dob_edit").value.trim();
    var dob_error = document.getElementById("dob_edit_error");
    dob_error.innerHTML = "";
    if (!dob.match(/^(0[1-9]|[12][0-9]|3[01])[\/\-](0[1-9]|1[0-2])[\/\-]\d{4}$/)) {
        dob_error.innerHTML = "Date of birth is invalid.\n";
        document.getElementById("dob_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("dob_edit").style.borderColor = "green";
    }

    // Gender
    var male = document.getElementById("check-male_edit").checked;
    var female = document.getElementById("check-female_edit").checked;
    var others = document.getElementById("check-others_edit").checked;
    var gender_error = document.getElementById("gender_edit_error");
    gender_error.innerHTML = "";
    if(!(male || female || others)) {
        gender_error.innerHTML = "Please select a gender.\n";
        document.getElementById("check-male_edit").style.borderColor = "red";
        document.getElementById("check-female_edit").style.borderColor = "red";
        document.getElementById("check-others_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("check-male_edit").style.borderColor = "green";
        document.getElementById("check-female_edit").style.borderColor = "green";
        document.getElementById("check-others_edit").style.borderColor = "green";
    }

    // Street Address
    var streetaddress = document.getElementById("streetaddress_edit").value.trim();
    var streetaddress_error = document.getElementById("streetaddress_edit_error");
    streetaddress_error.innerHTML = "";
    if (streetaddress == "") {
        streetaddress_error.innerHTML = "Please enter your street address.\n";
        document.getElementById("streetaddress_edit").style.borderColor = "red";
        result = false;
    }
    else if (!streetaddress.match(/[a-zA-Z0-9 ]{1,50}/)) {
        streetaddress_error.innerHTML = "Street address is invalid.\n";
        document.getElementById("streetaddress_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("streetaddress_edit").style.borderColor = "green";
    }

    // Country
    var country = document.getElementById("country_edit").value;
    var country_error = document.getElementById("country_edit_error");
    country_error.innerHTML = "";
    if (country == "") {
        country_error.innerHTML = "You must select a country.\n";
        Array.from(document.getElementsByClassName("editcountry-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("editcountry-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // State
    var state = document.getElementById("state_edit").value.trim();
    var state_error = document.getElementById("state_edit_error");
    state_error.innerHTML = "";
    if (state == "") {
        state_error.innerHTML = "Please enter your state.\n";
        document.getElementById("state_edit").style.borderColor = "red";
        result = false;
    }
    else if (!state.match(/^[a-zA-Z ]{1,50}$/)) {
        state_error.innerHTML = "State is invalid.\n";
        document.getElementById("state_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("state_edit").style.borderColor = "green";
    }

    // City
    var city = document.getElementById("city_edit").value.trim();
    var city_error = document.getElementById("city_edit_error");
    city_error.innerHTML = "";
    if (city == "") {
        city_error.innerHTML = "Please enter your city.\n";
        document.getElementById("city_edit").style.borderColor = "red";
        result = false;
    }
    else if (!city.match(/^[a-zA-Z ]{1,50}$/)) {
        city_error.innerHTML = "City is invalid.\n";
        document.getElementById("city_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("city_edit").style.borderColor = "green";
    }

    // Postal Code
    var postalcode = document.getElementById("postalcode_edit").value.trim();
    var postalcode_error = document.getElementById("postalcode_edit_error");
    postalcode_error.innerHTML = "";
    if (postalcode == "") {
        postalcode_error.innerHTML = "Please enter your postal code.\n";
        document.getElementById("postalcode_edit").style.borderColor = "red";
        result = false;
    }
    else if (!postalcode.match(/^\d{5}$/)) {
        postalcode_error.innerHTML = "Postal code is invalid. Please use 5 digits only.\n";
        document.getElementById("postalcode_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("postalcode_edit").style.borderColor = "green";
    }

    return result; // Return the overall validation result
}

function init() {
    var applyForm = document.getElementById("add");
    if (applyForm) {
        applyForm.onsubmit = validateAdd;
    }

    var editForm = document.getElementById("edit");
    if (editForm) {
        editForm.onsubmit = validateEdit;
    }
}

window.onload = init;