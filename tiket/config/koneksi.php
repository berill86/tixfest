<?php
$host     = "localhost";
$username = "root";
$password = ""; // ganti jika password database kamu ada
$database = "tixfest";

// Koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
