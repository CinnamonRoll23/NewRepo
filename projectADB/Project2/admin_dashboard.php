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

// Fetch subjects for automatic association
$subjects_query = "SELECT id FROM subjects";
$subjects_result = mysqli_query($db, $subjects_query);

// Handle form submission to add a new student
if (isset($_POST['add_student'])) {
    $student_id = mysqli_real_escape_string($db, $_POST['student_id']);
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    // Ensure the student fields are not empty
    if (empty($student_id) || empty($first_name) || empty($last_name) || empty($email)) {
        echo "All fields are required!";
    } else {
        // Insert the new student into the database
        $insert_student_query = "INSERT INTO students (id, first_name, last_name, email) 
                                  VALUES ('$student_id', '$first_name', '$last_name', '$email')";

        if (mysqli_query($db, $insert_student_query)) {
            // Get the student ID of the newly inserted student
            $student_id = mysqli_insert_id($db);

            // Insert the student into the 5 subjects
            while ($subject_row = mysqli_fetch_assoc($subjects_result)) {
                $subject_id = $subject_row['id'];
                $insert_subject_query = "INSERT INTO student_subjects (student_id, subject_id) 
                                          VALUES ('$student_id', '$subject_id')";
                mysqli_query($db, $insert_subject_query);
            }

            // Redirect to the dashboard after successful insertion
            header('Location: admin_dashboard.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($db); // Display the error message if insertion fails
        }
    }
}

// Fetch students for display
$students_query = "SELECT * FROM students";
$students_result = mysqli_query($db, $students_query);

// Close the database connection
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
    <a href="#" class="logo">Admin Dashboard</a>
    <div class="links">
        <a href="admin_dashboard.php">Home</a>
        <a href="admin_manage_students.php">Manage Students</a> <!-- Added Manage Students link -->
        <a href="admin_namage_grades.php">Manage Students</a>
        <a href="admin_attendance.php">Manage Attendance</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>


    <!-- Main Content -->
    <div class="content">
        <h2>Welcome, Admin!</h2>
        <p>This is your dashboard where you can manage students, grades, and attendance records.</p>

        <!-- Add Student Form -->
        <h3>Add New Student</h3>
        <form method="POST" action="admin_dashboard.php">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit" name="add_student">Add Student</button>
        </form>

        <!-- Students Section -->
        <h3>Student Records</h3>
        <?php if (mysqli_num_rows($students_result) > 0): ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($students_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_student.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No students found.</p>
        <?php endif; ?>

    </div>

</body>
</html>
