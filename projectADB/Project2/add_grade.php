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

if (isset($_POST['add_grade'])) {
    $student_id = mysqli_real_escape_string($db, $_POST['student_id']);
    $subject_id = mysqli_real_escape_string($db, $_POST['subject_id']);
    $grade = mysqli_real_escape_string($db, $_POST['grade']);

    // Ensure the grade fields are not empty
    if (empty($student_id) || empty($subject_id) || empty($grade)) {
        echo "All fields are required!";
    } else {
        // Insert the grade into the grades table
        $insert_grade_query = "INSERT INTO grades (student_id, subject_id, grade) 
                               VALUES ('$student_id', '$subject_id', '$grade')";

        if (mysqli_query($db, $insert_grade_query)) {
            echo "Grade added successfully!";
            header('Location: admin_manage_grades.php'); // Redirect back to the grades page
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

mysqli_close($db);
?>
