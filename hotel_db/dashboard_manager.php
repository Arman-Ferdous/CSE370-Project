<?php
include 'dbconnect.php';
session_start();
if ($_SESSION['role'] != 'manager') die('Access denied.');

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit;
}

$_SESSION['LAST_ACTIVITY'] = time();

// Room booking chart
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$rooms = $conn->query("SELECT id, room_number FROM rooms ORDER BY room_number");
$bookings = $conn->query("SELECT room_id, check_in_date, check_out_date, status FROM bookings");
$calendar = [];
while ($booking = $bookings->fetch_assoc()) {
  $start = strtotime($booking['check_in_date']);
  $end = strtotime($booking['check_out_date']);
  for ($d = $start; $d <= $end; $d += 86400) {
    $calendar[$booking['room_id']][date('Y-m-d', $d)] = $booking['status'];
  }
}

$totalResult = $conn->query("SELECT SUM(amount) as total_received FROM payments WHERE status = 'paid'");
$totalRow = $totalResult->fetch_assoc();
$totalReceived = $totalRow['total_received'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="CSS/dashboard.css">
</head>
<body>
  <div class="top-banner">
    <div class="left">
      <a href="dashboard_manager.php"><h2>Manager Dashboard</h2></a>
    </div>
    <div class="right">
      <a href="logout.php" style="color: blue;">Logout</a>
    </div>
  </div>

  <h3>Welcome, Manager <?= htmlspecialchars($_SESSION['user_fname']) ?></h3>
  <p><strong>Total Amount Received:</strong>💲<?= number_format($totalReceived, 2) ?></p>

  <ul>
    <li><a href="add_room.php">➕ Add Room</a></li>
    <li><a href="confirm_payment.php">💰 Confirm Payments</a></li>
  </ul>

  <h3>Room Booking Chart</h3>
  <form method="get">
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" min="<?= date('Y-m-d') ?>" id="start_date" required>
    <button type="submit">Apply</button>
  </form>

  <table>
    <tr>
      <th style="text-align:center;">Room</th>
      <?php for ($i = 0; $i < 7; $i++): ?>
        <th><?= date('D, M d', strtotime("+$i days", strtotime($start_date))) ?></th>
      <?php endfor; ?>
    </tr>
    <?php while ($room = $rooms->fetch_assoc()): ?>
    <tr>
      <td>Room <?= $room['room_number'] ?></td>
      <?php for ($i = 0; $i < 7; $i++):
        $date = date('Y-m-d', strtotime("+$i days", strtotime($start_date)));
        $status = $calendar[$room['id']][$date] ?? '...';
        $color = ($status == 'confirmed') ? '#c6f6d5' : (($status == 'pending') ? '#fefcbf' : '#f8f9fa');
      ?>
        <td style="text-align: center; background-color: <?= $color ?>;"><?= $status ?></td>
      <?php endfor; ?>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
