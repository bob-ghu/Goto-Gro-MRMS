//const validation = false;

"use strict";

function validate() { // Function to validate all data

    var result = true; // Initialise result as true

    // Stock Name
    var stockname = document.getElementById("name").value;
    var stockname_error = document.getElementById("name_error");
    stockname_error.innerHTML = "";
    if (stockname == "") {
        stockname_error.innerHTML = "Please enter the stock name.\n";
        document.getElementById("name").style.borderColor = "red";
        result = false;
    }
    else if (!stockname.match(/^[a-zA-Z ]+$/)) {
        stockname_error.innerHTML = "Invalid stock name. Please use letters only.\n";
        document.getElementById("name").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("name").style.borderColor = "green";
    }

    // Quantity
    var quantity = document.getElementById("quantity").value;
    var quantity_error = document.getElementById("quantity_error");
    quantity_error.innerHTML = "";
    if (quantity == "") {
        quantity_error.innerHTML = "Please enter the quantity.\n";
        document.getElementById("quantity").style.borderColor = "red";
        result = false;
    }
    else if (!quantity.match(/^\d{1,5}$/)) {
        quantity_error.innerHTML = "Quantity is invalid, maximum of 5 number.\n";
        document.getElementById("quantity").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("quantity").style.borderColor = "green";
    }

    // Retail price
    var retailprice = document.getElementById("retail_price").value;
    var retail_price_error = document.getElementById("retail_price_error");
    retail_price_error.innerHTML = "";
    if (retailprice == "") {
        retail_price_error.innerHTML = "Please enter the retail price.\n";
        document.getElementById("retail_price").style.borderColor = "red";
        result = false;
    }
    else if (!retailprice.match(/^\d{1,8}(\.\d{2})?$/)) {
        retail_price_error.innerHTML = "Retail price entered is invalid.\n";
        document.getElementById("retail_price").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("retail_price").style.borderColor = "green";
    }

    // Selling price
    var sellingprice = document.getElementById("selling_price").value;
    var selling_price_error = document.getElementById("selling_price_error");
    selling_price_error.innerHTML = "";
    if (sellingprice == "") {
        selling_price_error.innerHTML = "Please enter the selling price.\n";
        document.getElementById("selling_price").style.borderColor = "red";
        result = false;
    }
    else if (!sellingprice.match(/^\d{1,8}(\.\d{2})?$/)) {
        selling_price_error.innerHTML = "Selling price entered is invalid.\n";
        document.getElementById("selling_price").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("selling_price").style.borderColor = "green";
    }

    // Supplier Name
    var supplier = document.getElementById("supplier").value;
    var supplier_error = document.getElementById("supplier_error");
    supplier_error.innerHTML = "";
    if (supplier == "") {
        supplier_error.innerHTML = "Please enter the supplier's name.\n";
        document.getElementById("supplier").style.borderColor = "red";
        result = false;
    }
    else if (!supplier.match(/^[a-zA-Z ]+$/)) {
        supplier_error.innerHTML = "Invalid supplier name. Please use letters only.\n";
        document.getElementById("supplier").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("supplier").style.borderColor = "green";
    }

    // Category
    var category = document.getElementById("category").value;
    var category_error = document.getElementById("category_error");
    category_error.innerHTML = "";
    if (category == "") {
        category_error.innerHTML = "You must select a category.\n";
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

    // Brand Name
    var brand = document.getElementById("brand").value;
    var brand_error = document.getElementById("brand_error");
    brand_error.innerHTML = "";
    if (brand == "") {
        brand_error.innerHTML = "Please enter the brand name.\n";
        document.getElementById("brand").style.borderColor = "red";
        result = false;
    }
    else if (!brand.match(/^[a-zA-Z ]+$/)) {
        brand_error.innerHTML = "Invalid brand name. Please use letters only.\n";
        document.getElementById("brand").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("brand").style.borderColor = "green";
    }

    // Reorder Level
    var reorder = document.getElementById("reorder").value;
    var reorder_error = document.getElementById("reorder_error");
    reorder_error.innerHTML = "";
    if (reorder == "") {
        reorder_error.innerHTML = "Please enter the reorder level.\n";
        document.getElementById("reorder").style.borderColor = "red";
        result = false;
    }
    else if (!reorder.match(/^\d{1,5}$/)) {
        reorder_error.innerHTML = "Reorder level is invalid, maximum of 5 number.\n";
        document.getElementById("reorder").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("reorder").style.borderColor = "green";
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