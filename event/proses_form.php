<?php
session_start(); // Mulai session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tixfest";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$komentar = $_POST['komentar'];
$id_event = $_POST['id_event']; // ID event dari form

// Simpan ke database (tambahkan id_event)
$sql = "INSERT INTO form (id_event, nama, email, alamat, komentar) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $id_event, $nama, $email, $alamat, $komentar);

// Eksekusi dan cek
if ($stmt->execute()) {
    $_SESSION['message'] = "Pendaftaran berhasil!";
    header("Location: /tiket/detail_event.php?id=" . $id_event);
    exit();
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
