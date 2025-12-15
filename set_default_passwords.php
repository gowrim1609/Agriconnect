<?php
// RUN THIS FILE ONLY ONCE
// It will assign default passwords to all enterprises & farmers

include 'db.php';

// Default passwords
$enterprise_pw = password_hash("enterprise123", PASSWORD_BCRYPT);
$farmer_pw = password_hash("farmer123", PASSWORD_BCRYPT);

// Update Enterprise Passwords
$conn->query("UPDATE enterprises SET password = '$enterprise_pw'");

// Update Farmer Passwords
$conn->query("UPDATE farmers SET password = '$farmer_pw'");

echo "<h2>✅ Default passwords updated successfully!</h2>";
echo "<p><b>Enterprise Default Password:</b> enterprise123</p>";
echo "<p><b>Farmer Default Password:</b> farmer123</p>";
echo "<p>Delete this file after running once.</p>";
?>
