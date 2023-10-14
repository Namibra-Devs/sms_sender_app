<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page you prefer
    header("location: login.php");
    exit();
} else {
    // If the user is not logged in, you can handle it accordingly
    // Redirect to the login page or display a message
    header("location: login.php");
    exit();
}
