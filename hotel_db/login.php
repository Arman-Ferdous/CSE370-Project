<?php
// login.php
include 'dbconnect.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, password, role, fname FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['LAST_ACTIVITY'] = time();
      $_SESSION['user_fname'] = $user['fname'];

      if ($user['role'] == 'manager') {
        header("Location: dashboard_manager.php");
        exit;
      } else {
        header("Location: dashboard_customer.php");
        exit;
      }
    } else {
      $message = "Invalid password.";
    }
  } else {
    $message = "User not found.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Hotel Management System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-container">
    <h2>Login</h2>
    
    <?php if (!empty($message)): ?>
      <p class="message" style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="bottom-text">
      <a href="index.php">← Back to Home</a>
    </div>
  </div>

</body>
</html>
