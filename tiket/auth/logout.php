<?php
session_start();
session_unset();  // Hapus semua session yang ada
session_destroy();  // Hancurkan session
header("Location: ../admin/dashboard.php");  // Arahkan kembali ke halaman dashboard
exit();
?>
