<?php
session_start();

// Destroy session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header('location: login.php');
exit();
?>
