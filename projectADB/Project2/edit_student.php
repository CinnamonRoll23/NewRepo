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

// Fetch the student to be edited
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id = $student_id";
    $result = mysqli_query($db, $query);
    $student = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_student'])) {
    // Get the student data from the form
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    
    // Update the student in the database
    $update_query = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE id = $student_id";

    if (mysqli_query($db, $update_query)) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body>

    <h2>Edit Student</h2>
    <form method="POST" action="edit_student.php?id=<?php echo $student['id']; ?>">
        <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required>
        <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required>
        <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
        <button type="submit" name="update_student">Update Student</button>
    </form>

</body>
</html>
