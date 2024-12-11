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

// Fetch students and their grades along with subject names
$query = "SELECT 
            s.id AS student_id, 
            s.first_name, 
            s.last_name, 
            sub.name AS subject_name, 
            g.grade
          FROM students s
          LEFT JOIN grades g ON s.id = g.student_id
          LEFT JOIN subjects sub ON g.subject_id = sub.id";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_manage_students.php">Manage Students</a>
    <a href="test.php">Manage Grades</a>
    <a href="admin_attendance.php">Manage Attendance</a>
    <a href="logout.php">Logout</a>
</div>

<h2>Manage Grades</h2>

<table>
    <tr>
        <th>Student ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Subject</th>
        <th>Grade</th>
        <th>Actions</th>
    </tr>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['student_id']); ?></td>
                <td><?= htmlspecialchars($row['first_name']); ?></td>
                <td><?= htmlspecialchars($row['last_name']); ?></td>
                <td><?= htmlspecialchars($row['subject_name']); ?></td>
                <td><?= htmlspecialchars($row['grade']); ?></td>
                <td>
                    <a href="edit_grade.php?student_id=<?= $row['student_id']; ?>&subject_name=<?= urlencode($row['subject_name']); ?>">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No grades found.</td>
        </tr>
    <?php endif; ?>
</table>

<h3>Add Grade</h3>
<form method="POST" action="add_grade.php">
    <label for="student_id">Student ID:</label>
    <input type="text" id="student_id" name="student_id" required>

    <label for="subject_id">Subject:</label>
    <select id="subject_id" name="subject_id" required>
        <option value="">-- Select Subject --</option>
        <option value="1">Math</option>
        <option value="2">Science</option>
        <option value="3">English</option>
        <option value="4">Physical Education</option>
        <option value="5">History</option>
    </select>

    <label for="grade">Grade:</label>
    <input type="text" id="grade" name="grade" required>

    <button type="submit" name="add_grade">Add Grade</button>
</form>

</body>
</html>
