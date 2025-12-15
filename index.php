<?php
include 'db.php';

// Fetch crops (pull only existing columns)
$result = $conn->query("SHOW COLUMNS FROM crops");
$columns = [];
while ($col = $result->fetch_assoc()) {
    $columns[] = $col['Field'];
}

$selectCols = ["id", "name"]; // mandatory

if (in_array("quantity", $columns)) $selectCols[] = "quantity";
if (in_array("price", $columns)) $selectCols[] = "price";
if (in_array("image", $columns)) $selectCols[] = "image";

$query = "SELECT " . implode(",", $selectCols) . " FROM crops";
$crops = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AgriConnect - Bridging Farmers & Enterprises</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="hero">
    <div class="overlay"></div>
    <div class="hero-content">
        <h1>🌿 Welcome to <span>AgriConnect</span></h1>
        <p>Connecting <b>Farmers</b> and <b>Enterprises</b> directly.</p>
        <div class="buttons">
            <a href="farmer.php" class="btn">👨‍🌾 Farmer Portal</a>
            <a href="enterprize.php" class="btn">🏢 Enterprise Portal</a>
            <a href="login.php" class="btn login-btn">🔐 Login</a>
            <a href="dashboard.php" class="btn">📊 Dashboard</a>
        </div>
    </div>
</header>

<section class="marketplace">
    <h2>🌾 Available Crops</h2>

    <div class="crop-list">
        <?php if ($crops && $crops->num_rows > 0): ?>
            <?php while ($row = $crops->fetch_assoc()): ?>
                <div class="crop-card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

                    <?php if (isset($row["quantity"])): ?>
                        <p>Quantity: <?= $row["quantity"] ?> kg</p>
                    <?php endif; ?>

                    <?php if (isset($row["price"])): ?>
                        <p>Price: ₹<?= $row["price"] ?> /kg</p>
                    <?php endif; ?>

                    <a href="enterprize.php?crop_id=<?= $row['id'] ?>" class="buy-btn">Buy Now</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No crops found.</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <p>© <?php echo date("Y"); ?> AgriConnect</p>
</footer>

</body>
</html>
