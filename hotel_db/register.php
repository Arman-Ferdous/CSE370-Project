<?php
// register.php
include 'dbconnect.php';
$register_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $pass2 = $_POST['confirm_password'];
  $fname = $_POST['first_name'];
  $lname = $_POST['last_name'];

  if ($password != $pass2) {
    $register_error = "Passwords do not match.";
  } 
  else {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, fname, lname, role) VALUES (?, ?, ?, ?, 'customer')");
    $stmt->bind_param("ssss", $username, $password, $fname, $lname);
    if ($stmt->execute()) {
      header("Location: login.php");
      exit();
    } else {
      $register_error = "Error: Username already exists.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Hotel Management System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-container">
    <h2>Register</h2>

    <?php if (!empty($register_error)): ?>
      <p style="color: red; text-align: center;"><strong><?= htmlspecialchars($register_error) ?></strong></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="first_name" required>
      </div>

      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="last_name" required>
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirm_password" required>
      </div>

      <button type="submit" class="login-btn">Register</button>
    </form>

    <div class="bottom-text">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

</body>
</html>
