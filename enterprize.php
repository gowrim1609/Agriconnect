<?php
// DEBUG: show errors while developing
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// If enterprise isn't logged in, show a clear message (avoid blind redirect to missing file)
if (!isset($_SESSION['enterprise_id'])) {
    // If your actual login file is named differently, update the link below to that filename
    $loginFileCandidates = ['enterprise_login.php','login_enterprize.php','login.php','login_enterprise.php'];
    $availableLogin = null;
    foreach ($loginFileCandidates as $f) {
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $f)) {
            $availableLogin = $f;
            break;
        }
    }

    echo "<!doctype html><html><head><meta charset='utf-8'><title>Access required</title>";
    echo "<style>body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fa;color:#222;padding:40px} .box{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);max-width:700px;margin:0 auto;text-align:center} a.btn{display:inline-block;margin-top:12px;padding:10px 16px;background:#1a73e8;color:#fff;border-radius:6px;text-decoration:none}</style>";
    echo "</head><body><div class='box'><h2>Access denied — login required</h2>";
    if ($availableLogin) {
        echo "<p>You are not logged in as an enterprise. Please <a class='btn' href='{$availableLogin}'>login here</a>.</p>";
    } else {
        echo "<p>No login page was found in the project folder. Create a login file (e.g. <code>enterprise_login.php</code> or <code>login_enterprize.php</code>) or update the redirect in <code>enterprize.php</code>.</p>";
        echo "<p>Try opening <code>http://localhost/agriconnect/login_enterprize.php</code> or <code>http://localhost/agriconnect/login.php</code>.</p>";
    }
    echo "<p><small>If you want to bypass login for testing, set <code>\$_SESSION['enterprise_id']=1</code> manually in a test script (temporary only).</small></p>";
    echo "</div></body></html>";
    exit;
}

// If we are here, enterprise session exists
$enterprise_id = intval($_SESSION['enterprise_id']);

// Fetch farmers safely
$crops_result = $conn->query("SELECT * FROM farmers");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enterprise Marketplace</title>
    <style>
        body {font-family: Arial, sans-serif; background: #f5f7fa; margin: 0; padding: 0;}
        h2 {text-align:center;margin-top:20px;}
        .container {display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;padding:20px;}
        .card {background:white;border-radius:15px;box-shadow:0 2px 8px rgba(0,0,0,0.12);padding:20px;text-align:center;}
        .card img {width:100%;border-radius:10px;height:200px;object-fit:cover;}
        .btn {display:inline-block;padding:10px 18px;margin-top:10px;background:#1a73e8;color:white;text-decoration:none;border-radius:8px;border:none;cursor:pointer;}
        .btn:hover{background:#1256b0;}
        .logout {float:right;margin:10px;padding:10px 18px;background:#e74c3c;color:white;border-radius:8px;text-decoration:none;}
        .qty-input { width:90px; padding:6px; border-radius:6px; border:1px solid #ddd; margin-top:8px; }
        .label-inline { display:inline-block; margin-right:8px; font-size:14px; color:#333; }
        .meta { margin-top:8px; color:#555; }
    </style>
</head>
<body>
<a href="logout.php" class="logout">Logout</a>
<h2>🌾 Welcome to AgriConnect Marketplace</h2>

<div class="container">
    <?php
    if ($crops_result && $crops_result->num_rows > 0):
        while ($row = $crops_result->fetch_assoc()):
            // handle different possible column names
            $cropName = $row['crop'] ?? ($row['crop_name'] ?? 'Unknown Crop');
            $image = $row['image'] ?? 'images/default_crop.jpg';
            // ensure price is numeric (raw number for posting)
            $price_raw = isset($row['price']) ? (float)$row['price'] : 0.00;
            $price_display = number_format($price_raw, 2);
            $quantity = isset($row['quantity']) ? (int)$row['quantity'] : 0;
            $farmerId = isset($row['id']) ? (int)$row['id'] : 0;
    ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Crop Image" onerror="this.src='images/default_crop.jpg'">
            <h3><?php echo htmlspecialchars($cropName); ?></h3>
            <p class="meta"><strong>Price:</strong> ₹<?php echo $price_display; ?> / kg</p>
            <p class="meta"><strong>Available:</strong> <?php echo htmlspecialchars($quantity); ?> kg</p>

            <!-- BUY FORM: sends qty along with product details to payment.php using GET (keeps your flow) -->
            <form action="payment.php" method="GET" style="margin-top:12px;">
                <input type="hidden" name="farmer_id" value="<?php echo $farmerId; ?>">
                <input type="hidden" name="crop" value="<?php echo htmlspecialchars($cropName); ?>">
                <input type="hidden" name="price" value="<?php echo $price_raw; ?>">

                <label class="label-inline" for="qty-<?php echo $farmerId; ?>">Quantity (kg)</label>
                <input id="qty-<?php echo $farmerId; ?>" class="qty-input" type="number" name="qty" value="1" min="1" max="<?php echo $quantity; ?>" required>

                <br>
                <button type="submit" class="btn" style="margin-top:10px;">Buy Now</button>
            </form>
        </div>
    <?php
        endwhile;
    else:
        echo "<div style='padding:40px;text-align:center;color:#666'>No crops available right now.</div>";
    endif;
    ?>
</div>

</body>
</html>
