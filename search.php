<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database kamu
$password = ""; // Ganti dengan password database kamu
$dbname = "tixfest"; // Ganti dengan nama database kamu

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Query untuk pencarian
if (!empty($search)) {
    $query = "SELECT * FROM event WHERE nama_event LIKE '%$search%'";
} else {
    $query = "SELECT * FROM event";
}

$result = $conn->query($query);

if ($result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $gambar = !empty($row['gambar_event']) ? $row['gambar_event'] : 'default.jpg';
?>

  <div class="col">
    <div class="card h-100">
      <img src="uploads/<?php echo htmlspecialchars($gambar); ?>" class="card-img-top" alt="Event" style="height: 200px; object-fit: cover;">
      <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($row['nama_event']); ?></h5>
        <p><i class="bi bi-calendar-event"></i> <?php echo date('d F Y', strtotime($row['tanggal_event'])); ?></p>
        <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['lokasi']); ?></p>
        <h6 class="text-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></h6>
        <a href="detail_event.php?id=<?php echo $row['id_event']; ?>" class="btn btn-primary btn-block">Beli Tiket</a>
      </div>
    </div>
  </div>

<?php
    endwhile;
else:
?>
  <p class="text-muted">Belum ada event untuk ditampilkan.</p>
<?php
endif;

$conn->close();
?>
