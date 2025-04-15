<?php
$host     = "localhost";
$username = "root";
$password = ""; // Ganti kalau kamu pakai password MySQL
$database = "tixfest";

// Koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
/*
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {
    echo "<div style='
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        margin: 10px 0;
        font-family: Arial, sans-serif;
        max-width: 400px;'>✅ Koneksi ke database berhasil.</div>";
}
*/
?>
