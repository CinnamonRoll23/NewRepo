<!DOCTYPE html>
<html>
<body>

<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check for connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// SQL query to fetch user data
$sql = "SELECT id, username, email, img FROM users";
$result = $db->query($sql);

if ($result === false) {
    // Handle query failure
    die("Error: " . $db->error);
}

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Use htmlspecialchars to escape user inputs for security
        echo "<br>id: " . htmlspecialchars($row["id"]) . "<br>";
        echo "- Name: " . htmlspecialchars($row["username"]) . "<br>";
        echo "- Email: " . htmlspecialchars($row["email"]) . "<br>";
        
        // Check if an image path is available
        if (!empty($row["img"])) {
            echo "<img src='" . htmlspecialchars($row["img"]) . "' alt='User Image'>";
        } else {
            echo "<p>No image available</p>";
        }
    }
} else {
    echo "0 results";
}

// Close the database connection
$db->close();   
?> 

</body>
</html>
