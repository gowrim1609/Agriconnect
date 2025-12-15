<?php
include 'db.php';

// Accept via GET (keeps the same flow you used)
if (!isset($_GET['farmer_id']) || !isset($_GET['crop']) || !isset($_GET['price']) || !isset($_GET['qty'])) {
    echo "Invalid request.";
    exit;
}

// sanitize & cast
$farmer_id = (int) $_GET['farmer_id'];
$crop = trim($_GET['crop']);
$price = (float) $_GET['price'];
$qty = (int) $_GET['qty'];

if ($farmer_id <= 0 || $crop === '' || $price <= 0 || $qty <= 0) {
    echo "Invalid request.";
    exit;
}

// fetch farmer row safely
$stmt = $conn->prepare("SELECT * FROM farmers WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $farmer_id);
$stmt->execute();
$farmer = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$farmer) {
    echo "Invalid request.";
    exit;
}

// check available qty in DB to avoid over-buying
$available = isset($farmer['quantity']) ? (int)$farmer['quantity'] : 0;
if ($qty > $available) {
    echo "<p>Requested quantity ({$qty} kg) exceeds available stock ({$available} kg).</p>";
    echo "<p><a href='enterprize.php'>&larr; Back to Marketplace</a></p>";
    exit;
}

// calculate total
$total = $price * $qty;
$total_display = number_format($total, 2);
$unit_display = number_format($price, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Gateway</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            text-align: center;
            padding: 40px;
        }

        .payment-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: inline-block;
            padding: 30px;
            text-align: left;
            max-width:420px;
        }

        .qr {
            width: 200px;
            margin: 20px 0;
            display:block;
            margin-left:auto;
            margin-right:auto;
        }

        button {
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            cursor: pointer;
            width:100%;
            font-size:16px;
        }

        button:hover {
            background: #1256b0;
        }

        .summary p { margin:8px 0; font-size:15px; }
        .label { color:#555; font-weight:600; }
    </style>
</head>
<body>

<div class="payment-box">
    <h2>Payment Gateway</h2>

    <div class="summary">
        <p><span class="label">Farmer:</span> <?php echo htmlspecialchars($farmer['name'] ?? $farmer['farmer_name'] ?? 'Seller'); ?></p>
        <p><span class="label">Crop:</span> <?php echo htmlspecialchars($crop); ?></p>
        <p><span class="label">Unit Price:</span> ₹<?php echo $unit_display; ?> / kg</p>
        <p><span class="label">Quantity:</span> <?php echo $qty; ?> kg</p>
        <p><strong>Total Amount: ₹<?php echo $total_display; ?></strong></p>
    </div>

    <!-- QR (keeps your existing QR image) -->
    <img src="images/qr.png" alt="QR Code" class="qr">

    <p style="text-align:center;color:#666">Scan the QR code to simulate payment</p>

    <!-- Post the final amount to your existing success handler -->
    <form action="payment_success.php" method="POST">
        <input type="hidden" name="farmer_id" value="<?php echo $farmer_id; ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($total); ?>">
        <input type="hidden" name="qty" value="<?php echo $qty; ?>">
        <input type="hidden" name="crop" value="<?php echo htmlspecialchars($crop); ?>">
        <button type="submit">I Have Paid — Pay ₹<?php echo $total_display; ?></button>
    </form>
</div>

</body>
</html>
