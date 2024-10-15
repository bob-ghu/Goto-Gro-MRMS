//const validation = false;

"use strict";

function validate() { // Function to validate all data

    var result = true; // Initialise result as true

    // Member Name/ID
    var member = document.getElementById("member_id").value;
    var member_error = document.getElementById("member_error");
    member_error.innerHTML = "";
    if (member == "") {
        member_error.innerHTML = "Please select a member.\n";
        document.getElementById("member_id").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("member_id").style.borderColor = "green";
    }

    // Payment Method
    var paymentmethod = document.getElementById("payment_method").value;
    var payment_error = document.getElementById("payment_error");
    payment_error.innerHTML = "";
    if (paymentmethod == "") {
        payment_error.innerHTML = "Please select a payment method.\n";
        document.getElementById("payment_method").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("payment_method").style.borderColor = "green";
    }

    // Staff
    var staff = document.getElementById("staff_id").value;
    var staff_error = document.getElementById("staff_error");
    staff_error.innerHTML = "";
    if (staff == "") {
        staff_error.innerHTML = "Please select a staff.\n";
        document.getElementById("staff_id").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("staff_id").style.borderColor = "green";
    }

    // Product

    // Quantity

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