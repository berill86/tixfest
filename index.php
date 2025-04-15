<?php
// Mulai session jika diperlukan
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database kamu
$password = ""; // Ganti dengan password database kamu
$dbname = "tixfest"; // Ganti dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if (!empty($search)) {
    $query_populer = "SELECT * FROM event WHERE nama_event LIKE '%$search%'";
} else {
    $query_populer = "SELECT * FROM event";
}

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data siswa
$query = "SELECT * FROM event";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TixFest Navbar + Carousel</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
  .card-event-wrapper {
    display: flex;
    flex-direction: column;
    max-height: 530px;
    overflow-y: auto;
    gap: 15px;

    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE 10+ */
  }

  .card-event-wrapper::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
  }


.card-event-wrapper {
  display: flex;
  flex-direction: column;
  max-height: 560px; /* ditambah dari 545px ke 700px */
  overflow-y: auto;
  gap: 15px;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

/* Tambahkan margin atas ke setiap card di baris ke-2 dan seterusnya */
.card-event-wrapper .row .col:nth-child(n+4) {
  margin-top: 33px;
}

.event-slider {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 10px;
    scrollbar-width: none;
    gap: 20px;
    padding-top: 5px;
  }

</style>

</head>
<body>
  

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span class="brand-icon"><i class="bi bi-ticket-perforated"></i></span>
        <span class="brand-text">
          <span class="tix">Tix</span><span class="fest">Fest</span>
        </span>
      </a>
      <button class="navbar-toggler text-light" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <form class="d-flex ms-auto" role="search" onsubmit="return false;">
        <input id="searchInput" class="form-control search-bar" type="search" placeholder="Cari event..." aria-label="Search">
      </form>

      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto align-items-center">
          <a href="http://localhost/tiket/admin/dashboard.php" class="btn btn-warning btn-custom">Masuk</a>
        </div>
      </div>      
    </div>
  </nav>

  <!-- Carousel -->
  <div class="container my-5">
    <div id="carousel3D" class="carousel slide carousel-fade" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carousel3D" data-slide-to="0" class="active"></li>
        <li data-target="#carousel3D" data-slide-to="1"></li>
        <li data-target="#carousel3D" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner rounded shadow" style="perspective: 1200px;">
        <div class="carousel-item active">
          <img src="asset//hero/11111.png" class="d-block w-100" alt="Event 1">
        </div>
        <div class="carousel-item">
          <img src="asset//hero/22222.png" class="d-block w-100" alt="Event 2">
        </div>
        <div class="carousel-item">
          <img src="asset//hero/33333.png" class="d-block w-100" alt="Event 3">
        </div>
      </div>
  </div>

  <!-- Section Event Populer -->
  <div class="container custom-width my-5">
    <div class="card-event-wrapper">
      <h3 class="fw-bold mb-4">Event Populer</h3>
      <div id="eventContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        <!-- Card 1 -->
        <?php
          $query_populer = "SELECT * FROM event";
          $result_populer = $conn->query($query_populer);

          if ($result_populer->num_rows > 0):
            while ($row = $result_populer->fetch_assoc()):
              $gambar = !empty($row['gambar_event']) ? $row['gambar_event'] : 'default.jpg';
        ?>
          <div class="col ">
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
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Section Laris Manis dengan Background Gambar -->
  <section class="laris-manis-section d-flex align-items-center text-white">
    <div class="container py-5">
      <div class="row">
        <!-- Konten Teks Kiri -->
        <div class="col-md-4 mb-4 mb-md-0">
          <h2 class="fw-bold">Laris Manis!</h2>
          <p>Kumpulan event-event laris manis di TixFest yang mungkin kamu sukai.</p>
        </div>

        <!-- Konten Kartu dengan Slider -->
        <!-- Konten Kartu dengan Slider -->
<div class="col-md-8">
  <div class="card card-slider-wrapper p-3">
    <div class="event-slider d-flex overflow-auto">
      <?php
        // Contoh: Ambil 5 event dengan harga tertinggi sebagai "Laris Manis"
        $query_laris = "SELECT * FROM event ORDER BY harga DESC LIMIT 4";
        $result_laris = $conn->query($query_laris);

        if ($result_laris->num_rows > 0):
          while ($row = $result_laris->fetch_assoc()):
            $gambar = !empty($row['gambar_event']) ? $row['gambar_event'] : 'default.jpg';
      ?>
        <div class="event-card card me-3 flex-shrink-0" style="min-width: 220px;">
          <img src="uploads/<?php echo htmlspecialchars($gambar); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nama_event']); ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($row['nama_event']); ?></h5>
            <p><i class="bi bi-calendar-event"></i> <?php echo date('d F Y', strtotime($row['tanggal_event'])); ?></p>
            <p><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['lokasi']); ?></p>
          </div>
        </div>
      <?php
          endwhile;
        else:
      ?>
        <p class="text-muted">Belum ada event laris untuk ditampilkan.</p>
      <?php endif; ?>
    </div>
  </div>
</div>


      </div>
    </div>
  </section>

  <!-- Section Temukan Event Menarik di Kotamu -->
  <div class="container my-5">
    <h3 class="fw-bold mb-4">Temukan Event Menarik di Kotamu!</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">

      <!-- Card Jabodetabek -->
      <div class="col">
        <div class="card text-center h-100 shadow-sm border-0">
          <div class="card-body">
            <img src="asset/city/Jakarta.png" alt="Jabodetabek" class="img-fluid mb-3 city-img" style="height: 100px;">
            <h6 class="card-title fw-bold">Jakarta</h6>
          </div>
        </div>
      </div>

      <!-- Card Bali -->
      <div class="col">
        <div class="card text-center h-100 shadow-sm border-0">
          <div class="card-body">
            <img src="asset/city/bali.png" alt="Bali" class="img-fluid mb-3 city-img" style="height: 100px;">
            <h6 class="card-title fw-bold">Bali</h6>
          </div>
        </div>
      </div>

      <!-- Card Yogyakarta -->
      <div class="col">
        <div class="card text-center h-100 shadow-sm border-0">
          <div class="card-body">
            <img src="asset/city/yogyakarta.png" alt="Yogyakarta" class="img-fluid mb-3 city-img" style="height: 100px;">
            <h6 class="card-title fw-bold">Yogyakarta</h6>
          </div>
        </div>
      </div>

      <!-- Card Bandung -->
      <div class="col">
        <div class="card text-center h-100 shadow-sm border-0">
          <div class="card-body">
            <img src="asset/city/bandung.png" alt="Bandung" class="img-fluid mb-3 city-img" style="height: 100px;">
            <h6 class="card-title fw-bold">Bandung</h6>
          </div>
        </div>
      </div>

      <!-- Card Surabaya -->
      <div class="col">
        <div class="card text-center h-100 shadow-sm border-0">
          <div class="card-body">
            <img src="asset/city/surabaya.png" alt="Surabaya" class="img-fluid mb-3 city-img" style="height: 100px;">
            <h6 class="card-title fw-bold">Surabaya</h6>
          </div>
        </div>
      </div>

    </div>
  </div>

  <footer class="footer-dark">
    <div class="container">
      <div class="row">
        <!-- Kolom 1: Brand & Deskripsi -->
        <div class="col-md-4 mb-4">
          <h5 class="mb-3">
            <i class="bi bi-ticket-perforated footer-brand-icon"></i>
            <span class="footer-brand-text">
              <span class="tix">Tix</span><span class="fest">Fest</span>
            </span>
          </h5>
          <p>Platform terpercaya untuk cari dan beli tiket event seru di seluruh Indonesia. Musik, seni, budaya, semuanya ada!</p>
        </div>
  
        <!-- Kolom 3: Sosial Media -->
        <div class="col-md-4 mb-4">
          <h6 class="mb-3">Ikuti Kami</h6>
          <div>
            <a href="#" class="text-white-50 me-3"><i class="bi bi-facebook fs-4"></i></a>
            <a href="#" class="text-white-50 me-3"><i class="bi bi-instagram fs-4"></i></a>
            <a href="#" class="text-white-50 me-3"><i class="bi bi-twitter fs-4"></i></a>
            <a href="#" class="text-white-50"><i class="bi bi-youtube fs-4"></i></a>
          </div>
        </div>
      </div>
  
      <hr class="border-secondary" />
  
      <div class="text-center text-white-50 small">
        &copy; 2025 TixFest. All rights reserved.
      </div>
    </div>
  </footer>
  
  
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
      const keyword = this.value;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "search.php?search=" + encodeURIComponent(keyword), true);
      xhr.onload = function () {
        if (this.status === 200) {
          document.getElementById("eventContainer").innerHTML = this.responseText;
        }
      };
      xhr.send();
    });
  </script>
</body>
</html>