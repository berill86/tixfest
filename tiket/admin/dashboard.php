<?php
include('../config/koneksi.php');

// Ambil semua event dari database
$query = mysqli_query($koneksi, "SELECT * FROM event ORDER BY tanggal_event DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - TixFest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .event-card {
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
      transition: 0.3s ease;
    }
    .event-card:hover {
      transform: translateY(-3px);
    }
    .event-image {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center">Dashboard Admin - TixFest</h2>
  <div class="text-end mb-4">
    <a href="add_event.php" class="btn btn-success">+ Tambah Event</a>
  </div>
  <div class="row g-4">
    <?php while ($data = mysqli_fetch_assoc($query)): ?>
      <div class="col-md-4">
        <div class="card event-card">
          <img src="../uploads/<?= $data['gambar_event'] ?>" class="card-img-top event-image" alt="Gambar Event">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($data['nama_event']) ?></h5>
            <p class="card-text"><strong>Tanggal:</strong> <?= $data['tanggal_event'] ?></p>
            <p class="card-text"><strong>Lokasi:</strong> <?= $data['lokasi'] ?></p>
            <p class="card-text"><?= nl2br(htmlspecialchars($data['info_event'])) ?></p>
            <div class="d-flex justify-content-between mt-3">
              <a href="edit_event.php?id=<?= $data['id_event'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="hapus_event.php?id=<?= $data['id_event'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus event ini?')">Hapus</a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
