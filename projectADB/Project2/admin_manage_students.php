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

// Fetch subjects and students enrolled in each subject
$subjects_query = "SELECT s.id, s.subject_name 
                   FROM subjects s";
$subjects_result = mysqli_query($db, $subjects_query);

// Fetch students in each subject
$students_in_subject_query = "SELECT ss.student_id, s.first_name, s.last_name, sub.subject_name 
                              FROM student_subjects ss 
                              JOIN students s ON ss.student_id = s.id 
                              JOIN subjects sub ON ss.subject_id = sub.id";
$students_in_subject_result = mysqli_query($db, $students_in_subject_query);

// Close the database connection
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px; /* Smaller font size */
        }

        table th, table td {
            padding: 5px; /* Smaller padding */
            text-align: left;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        h3 {
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="#" class="logo">Admin Dashboard</a>
        <div class="links">
            <a href="admin_dashboard.php">Home</a>
            <a href="admin_manage_students.php">Manage Students</a>
            <a href="test.php">Manage Grades</a>
            <a href="admin_attendance.php">Manage Attendance</a>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Manage Students by Subject</h2>
        
        <!-- Display Subjects -->
        <?php while($subject = mysqli_fetch_assoc($subjects_result)): ?>
            <h3><?php echo $subject['subject_name']; ?> Students</h3>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
                <?php
                // Display students enrolled in this subject
                mysqli_data_seek($students_in_subject_result, 0); // Reset pointer to fetch from the beginning
                while($row = mysqli_fetch_assoc($students_in_subject_result)) {
                    if($row['subject_name'] == $subject['subject_name']) { // Check if the student is in this subject
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                              </tr>";
                    }
                }
                ?>
            </table>
        <?php endwhile; ?>
    </div>

</body>
</html>

