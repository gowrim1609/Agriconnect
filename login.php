<?php
session_start();
include 'db.php';

$error = '';

// If the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $password === '') {
        $error = 'Please enter both name and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM enterprises WHERE name = ? LIMIT 1");
        if ($stmt === false) {
            $error = 'Database error: ' . $conn->error;
        } else {
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($row = $res->fetch_assoc()) {
                $stored = $row['password'] ?? '';
                $isValid = false;

                // Bcrypt
                if (preg_match('/^\$2[ayb]\$[0-9]{2}\$/', $stored)) {
                    if (password_verify($password, $stored)) $isValid = true;
                }

                // MD5
                if (!$isValid && preg_match('/^[a-f0-9]{32}$/i', $stored)) {
                    if (md5($password) === $stored) $isValid = true;
                }

                // Plain text fallback
                if (!$isValid && $password === $stored) $isValid = true;

                if ($isValid) {
                    $_SESSION['enterprise_id'] = $row['id'];
                    $_SESSION['enterprise_name'] = $row['name'];
                    header('Location: enterprize.php');
                    exit();
                } else {
                    $error = 'Invalid name or password!';
                }
            } else {
                $error = 'Invalid name or password!';
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Enterprise Login</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(to bottom right, #a8e063, #56ab2f);
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    nav {
        text-align: center;
        padding: 15px;
        background: rgba(0,0,0,0.3);
    }

    nav a {
        color: white;
        font-size: 18px;
        font-weight: bold;
        margin: 0 15px;
        text-decoration: none;
    }

    nav a:hover {
        text-decoration: underline;
    }

    .login-container {
        width: 350px;
        margin: auto;
        background: #ffffffea;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        text-align: center;
        color: #2f6f31;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #2c2c2c;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: 2px solid #8bc34a;
        border-radius: 8px;
        outline: none;
        transition: 0.2s;
    }

    input:focus {
        border-color: #4caf50;
        box-shadow: 0px 0px 5px rgba(76,175,80,0.6);
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        background: #2f7d32;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #27682a;
        transform: translateY(-2px);
        box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
    }

    .error-box {
        background: #ffdddd;
        padding: 10px;
        border-left: 5px solid #b91c1c;
        margin-top: 10px;
        color: #a10f0f;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

</head>

<body>

<nav>
  <a href="index.php">Home</a>
  <a href="farmer.php">Farmer Portal</a>
  <a href="enterprize.php">Enterprise Portal</a>
  <a href="dashboard.php">Dashboard</a>
</nav>

<div class="login-container">
    <h2>Enterprise Login</h2>

    <?php if ($error): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="off">
        <label>Enterprise Name</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
