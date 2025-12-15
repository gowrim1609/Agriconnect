<?php
include 'db.php';

$enterprise_id = $_SESSION['enterprise_id'];
$crop_name = $_POST['crop_name'];
$amount = $_POST['amount'];
$method = $_POST['method']; // e.g., "UPI" or "Card"
$txn_id = "TXN" . rand(100000,999999); // fake txn id

$sql = "INSERT INTO payments (enterprise_id, crop_name, amount, payment_method, transaction_id, status)
        VALUES ('$enterprise_id', '$crop_name', '$amount', '$method', '$txn_id', 'Success')";

if ($conn->query($sql)) {
    echo "Payment successful!<br>Transaction ID: $txn_id";
} else {
    echo "Error: " . $conn->error;
}
?>
