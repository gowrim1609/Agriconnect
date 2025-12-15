<?php
include 'db.php';
session_start();

$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $crop = $_POST['crop'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];

    $sql = "INSERT INTO farmers (name, crop, quantity, price, bank_name, account_number, ifsc_code)
            VALUES ('$name', '$crop', $quantity, $price, '$bank_name', '$account_number', '$ifsc_code')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "✅ Registration successful! Your crop and bank details have been added.";
    } else {
        $success_message = "❌ Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM farmers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Farmer Registration</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;

            /* 🌿 Green Gradient Background */
            background: linear-gradient(to bottom right, #a8e063, #56ab2f);
            background-attachment: fixed;
        }

        nav {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            font-size: 17px;
        }

        h1, h2 {
            text-align: center;
            color: white;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }

        .page-container {
            width: 80%;
            margin: 20px auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }

        .form-container label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #555;
            margin-top: 5px;
        }

        .form-container input[type="submit"] {
            background-color: #2f7d32;
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 20px;
            font-size: 16px;
            padding: 12px;
            border-radius: 6px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #27682a;
        }

        .crop-list {
            list-style: none;
            padding: 0;
        }

        .crop-list li {
            background: #e3f7d4;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 16px;
            border-left: 5px solid #2f7d32;
        }

        .success-message {
            text-align: center;
            color: #003d00;
            background: #c8f7c5;
            padding: 10px;
            border-radius: 5px;
            width: 70%;
            margin: 10px auto;
            font-weight: bold;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<nav>
    <a href="index.php">Home</a>
    <a href="farmer.php">Farmer Portal</a>
    <a href="enterprize.php">Enterprise Portal</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="page-container">

    <h1>Farmer Registration</h1>

    <?php if ($success_message) echo "<p class='success-message'>$success_message</p>"; ?>

    <div class="card">
        <form method="post" class="form-container">

            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Crop:</label>
            <input type="text" name="crop" required>

            <label>Quantity (kg):</label>
            <input type="number" name="quantity" required min="1">

            <label>Price per kg (₹):</label>
            <input type="number" name="price" step="0.01" required>

            <h3>Bank Details</h3>

            <label>Bank Name:</label>
            <input type="text" name="bank_name" required>

            <label>Account Number:</label>
            <input type="text" name="account_number" required>

            <label>IFSC Code:</label>
            <input type="text" name="ifsc_code" required>

            <input type="submit" value="Register">
        </form>
    </div>

    <h2>Registered Farmers</h2>

    <div class="card">
        <ul class="crop-list">
            <?php while($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?= $row['crop'] ?></strong> — 
                    <?= $row['name'] ?> — 
                    ₹<?= $row['price'] ?>/kg
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

</div>

</body>
</html>
