<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Delete the student based on the ID
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete query
    $delete_query = "DELETE FROM students WHERE id = '$student_id'";

    if (mysqli_query($db, $delete_query)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
