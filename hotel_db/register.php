<?php
// register.php
include 'dbconnect.php';
$signup_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $fname = $_POST['first_name'];
  $lname = $_POST['last_name'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $mobile = $_POST['mobile'];
  $nid = $_POST['nid'];

  $stmt = $conn->prepare("INSERT INTO users (username, password, fname, lname, email, address, mobile, nid, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'customer')");
  $stmt->bind_param("ssssssss", $username, $password, $fname, $lname, $email, $address, $mobile, $nid);
  if ($stmt->execute()) {
    header("Location: login.php");
    exit();
  } else {
    $signup_error = "Error: Username already exists.";
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

    <?php if (!empty($signup_error)): ?>
      <p style="color: red; text-align: center;"><strong><?= htmlspecialchars($signup_error) ?></strong></p>
    <?php endif; ?>

    <form action="signup.php" method="POST">
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
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirm_password" required>
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number</label>
        <input type="text" id="mobile" name="mobile" required>
      </div>

      <div class="form-group">
        <label for="nid">NID Number</label>
        <input type="text" id="nid" name="nid" required>
      </div>

      <button type="submit" class="login-btn">Register</button>
    </form>

    <div class="bottom-text">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

</body>
</html>
