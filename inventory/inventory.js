"use strict";

function validateAdd() { // Function to validate all data

    var result = true; // Initialise result as true

    // Stock Name
    var stockname = document.getElementById("name").value.trim();
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
    var supplier = document.getElementById("supplier_input").value.trim();
    var supplier_error = document.getElementById("supplier_error");
    supplier_error.innerHTML = "";
    if (supplier == "") {
        supplier_error.innerHTML = "Please enter or select a supplier's name.\n";
        Array.from(document.getElementsByClassName("addsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else if (!supplier.match(/^[a-zA-Z ]+$/)) {
        supplier_error.innerHTML = "Invalid supplier name. Please use letters only.\n";
        Array.from(document.getElementsByClassName("addsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("addsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // Category
    var category = document.getElementById("category_input").value.trim();
    var category_error = document.getElementById("category_error");
    category_error.innerHTML = "";
    if (category == "") {
        category_error.innerHTML = "Please enter or select a category.\n";
        Array.from(document.getElementsByClassName("addcategory-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else if (!category.match(/^[a-zA-Z ]+$/)) {
        category_error.innerHTML = "Invalid category name. Please use letters only.\n";
        Array.from(document.getElementsByClassName("addcategory-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("addcategory-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // Brand Name
    var brand = document.getElementById("brand").value.trim();
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

function validateEdit() { // Function to validate all data

    var result = true; // Initialise result as true

    // Stock Name
    var stockname = document.getElementById("name_edit").value.trim();
    var stockname_error = document.getElementById("name_edit_error");
    stockname_error.innerHTML = "";
    if (stockname == "") {
        stockname_error.innerHTML = "Please enter the stock name.\n";
        document.getElementById("name_edit").style.borderColor = "red";
        result = false;
    }
    else if (!stockname.match(/^[a-zA-Z ]+$/)) {
        stockname_error.innerHTML = "Invalid stock name. Please use letters only.\n";
        document.getElementById("name_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("name_edit").style.borderColor = "green";
    }

    // Quantity
    var quantity = document.getElementById("quantity_edit").value;
    var quantity_error = document.getElementById("quantity_edit_error");
    quantity_error.innerHTML = "";
    if (quantity == "") {
        quantity_error.innerHTML = "Please enter the quantity.\n";
        document.getElementById("quantity_edit").style.borderColor = "red";
        result = false;
    }
    else if (!quantity.match(/^\d{1,5}$/)) {
        quantity_error.innerHTML = "Quantity is invalid, maximum of 5 number.\n";
        document.getElementById("quantity_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("quantity_edit").style.borderColor = "green";
    }

    // Retail price
    var retailprice = document.getElementById("retail_price_edit").value;
    var retail_price_error = document.getElementById("retail_price_edit_error");
    retail_price_error.innerHTML = "";
    if (retailprice == "") {
        retail_price_error.innerHTML = "Please enter the retail price.\n";
        document.getElementById("retail_price_edit").style.borderColor = "red";
        result = false;
    }
    else if (!retailprice.match(/^\d{1,8}(\.\d{2})?$/)) {
        retail_price_error.innerHTML = "Retail price entered is invalid.\n";
        document.getElementById("retail_price_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("retail_price_edit").style.borderColor = "green";
    }

    // Selling price
    var sellingprice = document.getElementById("selling_price_edit").value;
    var selling_price_error = document.getElementById("selling_price_edit_error");
    selling_price_error.innerHTML = "";
    if (sellingprice == "") {
        selling_price_error.innerHTML = "Please enter the selling price.\n";
        document.getElementById("selling_price_edit").style.borderColor = "red";
        result = false;
    }
    else if (!sellingprice.match(/^\d{1,8}(\.\d{2})?$/)) {
        selling_price_error.innerHTML = "Selling price entered is invalid.\n";
        document.getElementById("selling_price_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("selling_price_edit").style.borderColor = "green";
    }

    // Supplier Name
    var supplier = document.getElementById("supplier_input_edit").value.trim();
    var supplier_error = document.getElementById("supplier_edit_error");
    supplier_error.innerHTML = "";
    if (supplier == "") {
        supplier_error.innerHTML = "Please enter the supplier's name.\n";
        Array.from(document.getElementsByClassName("editsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else if (!supplier.match(/^[a-zA-Z ]+$/)) {
        supplier_error.innerHTML = "Invalid supplier name. Please use letters only.\n";
        Array.from(document.getElementsByClassName("editsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("editsupplier-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // Category
    var category = document.getElementById("category_input_edit").value.trim();
    var category_error = document.getElementById("category_edit_error");
    category_error.innerHTML = "";
    if (category == "") {
        category_error.innerHTML = "You must enter or select a category.\n";
        Array.from(document.getElementsByClassName("editcategory-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else if (!category.match(/^[a-zA-Z ]+$/)) {
        category_error.innerHTML = "Invalid category name. Please use letters only.\n";
        Array.from(document.getElementsByClassName("addcategory-box")).forEach(function(element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("editcategory-box")).forEach(function(element) {
            element.style.border = "1px solid green";
        });
    }

    // Brand Name
    var brand = document.getElementById("brand_edit").value.trim();
    var brand_error = document.getElementById("brand_edit_error");
    brand_error.innerHTML = "";
    if (brand == "") {
        brand_error.innerHTML = "Please enter the brand name.\n";
        document.getElementById("brand_edit").style.borderColor = "red";
        result = false;
    }
    else if (!brand.match(/^[a-zA-Z ]+$/)) {
        brand_error.innerHTML = "Invalid brand name. Please use letters only.\n";
        document.getElementById("brand_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("brand_edit").style.borderColor = "green";
    }

    // Reorder Level
    var reorder = document.getElementById("reorder_edit").value;
    var reorder_error = document.getElementById("reorder_edit_error");
    reorder_error.innerHTML = "";
    if (reorder == "") {
        reorder_error.innerHTML = "Please enter the reorder level.\n";
        document.getElementById("reorder_edit").style.borderColor = "red";
        result = false;
    }
    else if (!reorder.match(/^\d{1,5}$/)) {
        reorder_error.innerHTML = "Reorder level is invalid, maximum of 5 number.\n";
        document.getElementById("reorder_edit").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("reorder_edit").style.borderColor = "green";
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