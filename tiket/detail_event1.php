<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tixfest";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

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

$query_pendaftar = "SELECT * FROM form WHERE id_event = $id_event";
$result_pendaftar = mysqli_query($conn, $query_pendaftar);
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
      background-color: #f3f3f3;
      border-radius: 10px;
    }

    .banner {
      width: 100%;
      height: auto;
      max-height: 500px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .details {
      display: flex;
      justify-content: flex-start;
      gap: 25px;
      flex-wrap: wrap;
      margin-bottom: 20px;
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
      background: #f4d35e;
      border-radius: 10px;
      padding: 20px;
      color: #0d1b2a;
      margin-bottom: 30px;
    }

    .pendaftar-container {
      background: #0d1b2a;
      color: white;
      border-radius: 10px;
      padding: 20px;
    }

    .pendaftar-container h2 {
      margin-top: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #0d1b2a;
      color: white;
    }

    .back-wrapper {
      margin-top: 30px;
      text-align: right;
    }

    .back-button {
      background-color: #0d1b2a;
      color: #fff;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s ease;
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

  <div class="pendaftar-container">
    <h2>Daftar Pendaftar</h2>

    <?php if (mysqli_num_rows($result_pendaftar) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Komentar</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($pendaftar = mysqli_fetch_assoc($result_pendaftar)): ?>
            <tr>
              <td><?php echo htmlspecialchars($pendaftar['nama']); ?></td>
              <td><?php echo htmlspecialchars($pendaftar['email']); ?></td>
              <td><?php echo htmlspecialchars($pendaftar['alamat']); ?></td>
              <td><?php echo htmlspecialchars($pendaftar['komentar']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Tidak ada pendaftar untuk event ini.</p>
    <?php endif; ?>
  </div>

  <div class="back-wrapper">
    <a href="http://localhost/tiket/admin/dashboard.php" class="back-button">Kembali</a>
  </div>
</div>

<?php $conn->close(); ?>
</body>
</html>
