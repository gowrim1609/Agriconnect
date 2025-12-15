<?php
include 'db.php';
session_start();

// for now you can hardcode a test farmer ID if you haven’t implemented login yet
$farmer_id = 1;

$result = $conn->query("SELECT * FROM notifications WHERE farmer_id='$farmer_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="notifications.php">Notifications</a>
</nav>

<div class="container">
    <h2>Payment Notifications</h2>
    <?php if($result->num_rows > 0): ?>
        <?php while($n = $result->fetch_assoc()): ?>
            <div class="notification">
                <p>💬 <?= $n['message'] ?></p>
                <p class="time">Sent on <?= $n['created_at'] ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No notifications yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
