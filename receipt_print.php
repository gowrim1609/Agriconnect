<?php
include 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = $conn->query("SELECT * FROM purchases WHERE id=$id")->fetch_assoc();
if (!$row) die("Receipt not found.");
?>
<!DOCTYPE html><html><head><title>Receipt</title></head><body onload="window.print()">
<h2>AgriConnect — Receipt</h2>
<p>Purchase ID: <?= $row['id'] ?></p>
<p>Enterprise: <?= htmlspecialchars($row['enterprise_name']) ?></p>
<p>Farmer: <?= htmlspecialchars($row['farmer_name']) ?></p>
<p>Crop: <?= htmlspecialchars($row['crop_name']) ?></p>
<p>Quantity: <?= $row['quantity'] ?> kg</p>
<p>Amount: ₹<?= number_format($row['total_price'],2) ?></p>
<p>Date: <?= $row['created_at'] ?></p>
</body></html>
