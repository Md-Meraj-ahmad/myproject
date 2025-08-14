<?php
    session_start();
    session_unset();
    session_destroy();
    $success = [];
    $success[] = "You have been logged out.";
    header("Location: reg_login.php");
    exit();
?>