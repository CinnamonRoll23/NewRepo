<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login System</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
    <h2>Login</h2>
  </div>

  <form method="post" action="login.php">
    <?php include('errors.php'); ?>
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" required>
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div class="input-group">
      <label>Select Role:</label>
      <select name="role" required>
          <option value="admin">Admin</option>
          <option value="student">Student</option>
      </select>
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="login_user">Login</button>
    </div>
    <p>
      Not yet registered? <a href="register.php">Sign up</a>
    </p>
  </form>
</body>
</html>
