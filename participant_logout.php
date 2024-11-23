<?php
session_start(); // Start the session

// Destroy the session to log the user out
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page (admin_login.php or any other login page)
header("Location: participant_login.php");
exit(); // Ensure no further code is executed after the redirect
?>
