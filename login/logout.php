<?php
    session_start();
    unset($_SESSION['loggedin']);
    header('Location: ../login/login.php');
    exit;
?>