<?php
session_start();

// initializing variables
$username = "";
$email = "";
$role = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // Receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    
    // Check if role is set
    if (isset($_POST['role'])) {
        $role = mysqli_real_escape_string($db, $_POST['role']);
    } else {
        array_push($errors, "Role is required");
    }

    // Check if passwords match
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // Check if user already exists
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    // If there are no errors, save the user to the database
    if (count($errors) == 0) {
        $password = password_hash($password_1, PASSWORD_DEFAULT); // Encrypt password

        $query = "INSERT INTO users (username, email, password, role) 
                  VALUES('$username', '$email', '$password', '$role')";
        mysqli_query($db, $query);

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header('location: index.php'); // Redirect to homepage after registration
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    // Receive input values
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Check if role is set
    if (isset($_POST['role'])) {
        $role = mysqli_real_escape_string($db, $_POST['role']);
    } else {
        array_push($errors, "Role is required");
    }

    // If there are no errors, proceed with login
    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);
            // Check if password is correct
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($role == 'admin') {
                    header('location: admin_dashboard.php');
                } else {
                    header('location: student_dashboard.php');
                }
            } else {
                array_push($errors, "Wrong password. Try again.");
            }
        } else {
            array_push($errors, "No user found with the given role.");
        }
    }
}

?>
