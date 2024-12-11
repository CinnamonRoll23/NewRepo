<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
    <h2>Register</h2>
  </div>
  
  <form method="post" action="register.php">
    <?php include('errors.php'); ?>
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
    </div>
    <div class="input-group">
      <label>Email</label>
      <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password_1" required>
    </div>
    <div class="input-group">
      <label>Confirm password</label>
      <input type="password" name="password_2" required>
    </div>
    <div class="input-group">
      <label>Select your role:</label><br>
      <input type="radio" name="role" value="admin" required> Admin<br>
      <input type="radio" name="role" value="student" required> Student
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="reg_user">Register</button>
    </div>
    <p>
      Already a member? <a href="login.php">Sign in</a>
    </p>
  </form>
</body>
</html>
