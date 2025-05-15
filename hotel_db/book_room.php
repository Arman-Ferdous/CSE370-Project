<?php
include 'dbconnect.php';
session_start();
if ($_SESSION['role'] != 'customer') die('Access denied.');

$successMessage = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $successMessage = '✅ Room booked successfully.';
}

$message = '';

if (isset($_GET['check_in']) && isset($_GET['check_out']) && isset($_GET['capacity'])) {
  $check_in = $_GET['check_in'];
  $check_out = $_GET['check_out'];
  $capacity = $_GET['capacity'];
  $today = date('Y-m-d');

  if ($check_in < $today) {
    $message = "Check in must be today or later.";
  } else if ($check_out < $check_in) {
    $message = "Check-out must be after check-in.";
  } else {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE capacity >= ? AND id NOT IN (SELECT room_id FROM bookings WHERE (status = 'reserved' OR status = 'paid') AND NOT (check_out_date < ? OR check_in_date > ?)) ORDER BY price");
    $stmt->bind_param("iss", $capacity, $check_in, $check_out);
    $stmt->execute();
    $available_rooms = $stmt->get_result();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['room_id'])) {
  $room_id = $_POST['room_id'];
  $check_in = $_POST['check_in'];
  $check_out = $_POST['check_out'];
  $user_id = $_SESSION['user_id'];
  $price = $_POST['price'];

  $check = $conn->prepare("SELECT * FROM bookings WHERE room_id = ? AND (status = 'reserved' OR status = 'paid') AND NOT (check_out_date < ? OR check_in_date > ?)");
  $check->bind_param("iss", $room_id, $check_in, $check_out);
  $check->execute();
  $conflict = $check->get_result();

  if ($conflict->num_rows > 0) {
    $message = "Room is already booked during the selected dates.";
  } else {
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);
    if ($stmt->execute()) {
      $booking_id = $conn->insert_id;
      $conn->query("INSERT INTO payments (booking_id, amount) VALUES ($booking_id, $price)");
      header("Location: book_room.php?success=1");
      exit();
    } else $message = "Booking failed.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book a Room</title>
  <link rel="stylesheet" href="CSS/style1.css">
</head>
<body>
  <div class="top-banner">
    <div class="left">
      <a href="dashboard_customer.php"><h2>Dashboard</h2></a>
    </div>
    <div class="right">
      <a href="logout.php" style="color: blue;">Logout</a>
    </div>
  </div>
  
  <div class="form-container">
    <h2>Search Available Rooms</h2>

    <?php if ($successMessage): ?>
        <div style="padding: 10px; background-color: #e6ffe6; border: 1px solid green; color: darkgreen; margin-bottom: 15px;">
            <?= $successMessage ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
      <?php if ($message == "Room booked."): ?>
        <p class="message" style="color: green;"><?= htmlspecialchars($message) ?></p>
      <?php else: ?>
        <p class="message" style="color: red;"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>
    <?php endif; ?>

    <form method="get">
      <label for="check_in">Check-in Date:</label>
      <input type="date" name="check_in" min="<?= date('Y-m-d') ?>" id="check_in" required>

      <label for="check_out">Check-out Date:</label>
      <input type="date" name="check_out" min="<?= date('Y-m-d') ?>" id="check_out" required>

      <label for="capacity">Required Capacity:</label>
      <input type="number" name="capacity" id="capacity" min="1" required>

      <button type="submit">Search Rooms</button>
    </form>
  </div>

  <?php if (isset($available_rooms)): ?>
    <div class="form-container">
      <h2>Select a Room to Book</h2>

      <?php if ($available_rooms->num_rows == 0): ?>
        <p>No rooms found with your specifications. </p>
      <?php else: ?>
        <?php while($r = $available_rooms->fetch_assoc()): ?>
          <form method="post" style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px;">
            <input type="hidden" name="room_id" value="<?= $r['id'] ?>">
            <input type="hidden" name="check_in" value="<?= htmlspecialchars($check_in) ?>">
            <input type="hidden" name="check_out" value="<?= htmlspecialchars($check_out) ?>">

            <?php 
              $day_cnt = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24) + 1;
              $price = $r['price'] * $day_cnt;
            ?>

            <input type="hidden" name="price" value="<?= $price ?>">

            <p><strong>Room <?= $r['room_number'] ?></strong></p>
            <p>Capacity: <?= $r['capacity'] ?></p>
            
            <p>Price: $<?= number_format($price, 2) ?></p>
            <button type="submit">Book This Room</button>
          </form>
        <?php endwhile; ?>
      <?php endif; ?>

    </div>
  <?php endif; ?>

  <a class="back-link" href="dashboard_customer.php">← Back to Customer Dashboard</a>
</body>
</html>
