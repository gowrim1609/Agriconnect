<?php
session_start();
include 'db.php';

// Check for valid form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $farmer_id = $_POST['farmer_id'] ?? null;
    $enterprise_id = $_SESSION['enterprise_id'] ?? null;
    $amount = $_POST['amount'] ?? 0;

    if ($farmer_id && $enterprise_id && $amount) {
        // Fetch crop name for record
        $farmer = $conn->query("SELECT crop FROM farmers WHERE id=$farmer_id")->fetch_assoc();
        $crop_name = $farmer['crop'] ?? 'Unknown Crop';

        // Insert into purchases table
        $stmt = $conn->prepare("INSERT INTO purchases (farmer_id, enterprise_id, crop_name, amount, date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iisd", $farmer_id, $enterprise_id, $crop_name, $amount);
        $stmt->execute();
        $stmt->close();

        $success = true;
    } else {
        $success = false;
    }
} else {
    $success = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            text-align: center;
            margin-top: 100px;
        }

        .box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: inline-block;
        }

        h2 {
            color: #2ecc71;
        }

        .error {
            color: #e74c3c;
        }

        .btn {
            display: inline-block;
            background: #1a73e8;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background: #1256b0;
        }
    </style>
</head>
<body>

<div class="box">
    <?php if ($success): ?>
        <h2>✅ Payment Successful!</h2>
        <p>Thank you for your purchase.</p>
        <p><strong>Crop:</strong> <?php echo htmlspecialchars($crop_name); ?></p>
        <p><strong>Amount Paid:</strong> ₹<?php echo htmlspecialchars($amount); ?></p>
        <p><strong>Date:</strong> <?php echo date("d M Y, h:i A"); ?></p>
        <a href="enterprize.php" class="btn">Back to Marketplace</a>
    <?php else: ?>
        <h2 class="error">❌ Payment Failed!</h2>
        <p>Something went wrong or missing details.</p>
        <a href="enterprize.php" class="btn">Return</a>
    <?php endif; ?>
</div>

</body>
</html>
