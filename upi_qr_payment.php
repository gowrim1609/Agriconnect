<?php
session_start();
include('db.php');

if (!isset($_GET['farmer_id']) || !isset($_GET['quantity'])) {
    die("Invalid request.");
}

$farmer_id = intval($_GET['farmer_id']);
$quantity = intval($_GET['quantity']);
$result = $conn->query("SELECT * FROM farmers WHERE id=$farmer_id");
$farmer = $result->fetch_assoc();

$total_price = $farmer['price'] * $quantity;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fake UPI Payment - AgriConnect</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f6f5;
    text-align: center;
    padding: 50px;
}
.payment-box {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    width: 400px;
    margin: auto;
}
.qr-code {
    width: 200px;
    height: 200px;
    margin: 20px auto;
    border: 2px solid #ccc;
    border-radius: 10px;
}
.button {
    display: inline-block;
    background-color: #2e7d32;
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    margin-top: 20px;
}
.button:hover {
    background-color: #1b5e20;
}
</style>
</head>
<body>

<div class="payment-box">
    <h2>Make Payment via UPI</h2>
    <p><strong>Farmer:</strong> <?php echo $farmer['name']; ?></p>
    <p><strong>Crop:</strong> <?php echo $farmer['crop']; ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo $total_price; ?></p>
    <p><strong>UPI ID:</strong> <?php echo $farmer['upi_id']; ?></p>

    <img src="images/upi_qr.png" alt="UPI QR Code" class="qr-code">

    <br>
    <a href="payment_success.php?farmer_id=<?php echo $farmer_id; ?>&quantity=<?php echo $quantity; ?>" class="button">
        ✅ I Have Paid
    </a>
</div>

</body>
</html>
