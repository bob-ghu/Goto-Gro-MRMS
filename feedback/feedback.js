"use strict";

function validateAdd() {
    var result = true;

    // Name
    var name = document.getElementById("name").value.trim();
    var name_error = document.getElementById("name_error");
    name_error.innerHTML = "";
    if (name == "") {
        name_error.innerHTML = "Please enter your full name.\n";
        document.getElementById("name").style.borderColor = "red";
        result = false;
    }
    else if (!name.match(/^[a-zA-Z ]{1,50}$/)) {
        name_error.innerHTML = "Invalid name. Please use letters only.\n";
        document.getElementById("name").style.borderColor = "red";
        result = false;
    }
    else {
        document.getElementById("name").style.borderColor = "green";
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

    // Feedback
    var feedback_type = document.getElementById("feedback_type").value;
    var feedback_type_error = document.getElementById("feedback_type_error");
    feedback_type_error.innerHTML = "";
    if (feedback_type == "") {
        feedback_type_error.innerHTML = "You must select a feedback type.\n";
        Array.from(document.getElementsByClassName("addfeedbacktype-box")).forEach(function (element) {
            element.style.border = "1px solid red";
        });
        result = false;
    }
    else {
        Array.from(document.getElementsByClassName("addfeedbacktype-box")).forEach(function (element) {
            element.style.border = "1px solid green";
        });
    }

    // Comments
    var comments = document.getElementById("comments").value.trim();
    var comments_error = document.getElementById("comments_error");
    comments_error.innerHTML = "";
    if (comments == "") {
        comments_error.innerHTML = "Please enter your comments.\n";
        document.getElementById("comments").style.border = "1px solid red";
        result = false;
    }
    else {
        document.getElementById("comments").style.border = "1px solid green";
    }


    return result;
}

function init() {
    var applyForm = document.getElementById("add");
    applyForm.onsubmit = validateAdd;
}

window.onload = init;