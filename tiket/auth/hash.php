<?php
// Ganti 'admin123' dengan password yang kamu mau
$password = 'admin123';

$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "Password asli: $password<br>";
echo "Password hash: <br><textarea cols='80' rows='3'>$hashed</textarea>";
?>
