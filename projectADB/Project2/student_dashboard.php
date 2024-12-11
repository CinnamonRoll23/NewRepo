<?php
// Assuming you have session started already
session_start();
if (!isset($_SESSION['username'])) {
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_dashboard.css"> <!-- Include the new CSS -->
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h2>Welcome, Admin!</h2>
        </div>

        <div class="content">
            <h3>Admin Actions</h3>

            <div class="admin-actions">
                <a href="admin_users.php" class="btn">Manage Users</a>
                <a href="admin_reports.php" class="btn">View Reports</a>
                <a href="admin_settings.php" class="btn">Settings</a>
            </div>

            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <footer>
            <p>&copy; 2024 Your Company</p>
        </footer>
    </div>
</body>
</html>
