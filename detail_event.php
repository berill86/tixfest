<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tixfest";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    echo "ID event tidak ditemukan!";
    exit;
}

$id_event = $_GET['id'];
$query = "SELECT * FROM event WHERE id_event = $id_event";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo "Event tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($event['nama_event']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #fff;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      display: flex;
      gap: 30px;
      background-color: #f3f3f3;
      border-radius: 10px;
      position: relative;
    }

    .left-content {
      flex: 2;
    }

    .right-content {
      flex: 1;
      background: #0d1b2a;
      padding: 30px;
      border-radius: 10px;
      color: #ffffff;
      height: fit-content;
    }

    .banner {
      width: 100%;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .details {
      display: flex;
      justify-content: flex-start;
      gap: 25px;
      flex-wrap: wrap;
    }

    .detail-box {
      width: 32%;
      background: #0d1b2a;
      border-radius: 10px;
      padding: 15px;
      color: white;
    }

    .detail-box h4 {
      margin: 0 0 10px;
      font-size: 15px;
    }

    .detail-box p {
      margin: 0;
      font-weight: bold;
    }

    .description {
      margin-top: 20px;
      background: #f4d35e;
      border-radius: 10px;
      padding: 20px;
      color: #0d1b2a;
    }

    .form-box label {
      font-weight: 600;
      display: block;
      margin-top: 10px;
    }

    .form-box input, .form-box textarea {
      width: 96%;
      padding: 8px;
      margin-top: 4px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .form-box button {
      background: #f4d35e;
      color: #0d1b2a;
      padding: 10px 20px;
      border: none;
      font-weight: bold;
      border-radius: 8px;
      margin-top: 15px;
      cursor: pointer;
    }

    h1 {
      margin-top: 0;
      color: #0d1b2a;
    }

    .back-button {
      position: absolute;
      bottom: 20px;
      right: 20px;
      background-color: #0d1b2a;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }

    .back-button:hover {
      background-color: #f4d35e;
      color: #0d1b2a;
    }

    .notif.success {
      padding: 15px;
      background-color: #d4edda;
      color: #155724;
      border-radius: 8px;
      margin: 20px auto;
      max-width: 800px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php if (isset($_SESSION['message'])): ?>
  <div class="notif success">
    <?php 
      echo $_SESSION['message']; 
      unset($_SESSION['message']); 
    ?>
  </div>
<?php endif; ?>

<div class="container">
  <!-- Bagian Kiri -->
  <div class="left-content">
    <?php
      $gambar = $event['gambar_event'];
      $gambar_disk_path = __DIR__ . '/uploads/' . $gambar;
      $gambar_url = '/tiket/uploads/' . $gambar;
    ?>

    <?php if (!empty($gambar) && file_exists($gambar_disk_path)): ?>
      <img src="<?php echo $gambar_url; ?>" alt="Banner Event" class="banner">
    <?php else: ?>
      <div style="padding:20px; background:#eee; border-radius:12px; text-align:center;">
        <strong>Gambar tidak tersedia</strong>
      </div>
    <?php endif; ?>

    <h1><?php echo htmlspecialchars($event['nama_event']); ?></h1>

    <div class="details">
      <div class="detail-box">
        <h4>Tanggal</h4>
        <p><?php echo date("d F Y", strtotime($event['tanggal_event'])); ?></p>
      </div>
      <div class="detail-box">
        <h4>Waktu</h4>
        <p>14:00 – 22:00</p>
      </div>
      <div class="detail-box">
        <h4>Lokasi</h4>
        <p><?php echo htmlspecialchars($event['lokasi']); ?></p>
      </div>
    </div>

    <div class="description">
      <h2>Deskripsi Event</h2>
      <p><?php echo nl2br(htmlspecialchars($event['info_event'])); ?></p>
    </div>
  </div>

  <!-- Bagian Kanan -->
  <div class="right-content">
    <h2>Form Pendaftaran</h2>
    <form method="POST" action="http://localhost/tiket/event/proses_form.php" class="form-box">
      <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">

      <label>Nama Lengkap</label>
      <input type="text" name="nama" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Alamat</label>
      <input type="text" name="alamat" required>

      <label>Komentar / Pesan</label>
      <textarea name="komentar" rows="4"></textarea>

      <button type="submit">Daftar Sekarang</button>
    </form>
  </div>

  <a href="index.php" class="back-button">Kembali</a>
</div>

<?php $conn->close(); ?>
</body>
</html>
