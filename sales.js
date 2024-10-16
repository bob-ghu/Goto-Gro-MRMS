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
        Array.from(document.getElementsByClassName("member-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("member-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }
    
    // Payment Method
    var paymentmethod = document.getElementById("payment_method").value;
    var payment_error = document.getElementById("payment_error");
    payment_error.innerHTML = "";
    if (paymentmethod == "") {
        payment_error.innerHTML = "Please select a payment method.\n";
        Array.from(document.getElementsByClassName("payment-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("payment-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // Staff
    var staff = document.getElementById("staff_id").value;
    var staff_error = document.getElementById("staff_error");
    staff_error.innerHTML = "";
    if (staff == "") {
        staff_error.innerHTML = "Please select a staff.\n";
        Array.from(document.getElementsByClassName("staff-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("staff-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }   

    // Validate products and quantities
    for (let i = 1; i <= productCount; i++) {
        const selectId = 'product_' + i; // Construct the ID for the select element
        const selectElement = document.getElementById(selectId); // Reference the select element
        const productError = document.getElementById(`product_${i}_error`); // Error message element

        const quantityId = 'quantity_' + i; // Construct the ID for the quantity input
        const quantityElement = document.getElementById(quantityId); // Reference the quantity input
        const quantityError = document.getElementById(`quantity_${i}_error`); // Error message element for quantity

        // Clear previous error messages
        productError.innerHTML = "";
        quantityError.innerHTML = "";

        // Validate product selection
        if (selectElement.value === "") {
            productError.innerHTML = "Please select a product.";
            Array.from(document.getElementsByClassName("product-box")).forEach(function(element) {
                element.style.border = "1px solid red";
            });
            result = false; // Update result if validation fails
        } else {
            Array.from(document.getElementsByClassName("product-box")).forEach(function(element) {
                element.style.border = "1px solid green";
            });
        }

        // Validate quantity
        const quantity = parseInt(quantityElement.value, 10);
        if (isNaN(quantity) || quantity < 1) {
            quantityError.innerHTML = "Quantity must be a positive number.";
            quantityElement.style.borderColor = "red";
            result = false; // Update result if validation fails
        } else {
            quantityElement.style.borderColor = "green"; // Valid quantity
        }
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