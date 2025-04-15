<?php
session_start(); // Panggil sekali saja

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../controllers/DashboardController.php');
$dashboard = new DashboardController();
$stats = $dashboard->getStats();
$event_terbaru = $stats['event_terbaru'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - TixFest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f3f4f6, #e9ecef);
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }

    .dashboard-header {
      background: linear-gradient(135deg, #0d3c61, #1f3b57);
      color: #fff;
      padding: 3rem 2rem;
      border-radius: 20px;
      margin-bottom: 3rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
      text-align: center;
    }

    /* Siluet tiket */
    .dashboard-header::before {
      content: "\f3ff"; /* bi-ticket-perforated */
      font-family: "bootstrap-icons";
      font-size: 7rem;
      position: absolute;
      top: 20px;
      left: 25px;
      color: rgba(255, 255, 255, 0.05);
      transform: rotate(-20deg);
    }

    /* Siluet lokasi */
    .dashboard-header::after {
      content: "\f3e8"; /* bi-geo-alt-fill */
      font-family: "bootstrap-icons";
      font-size: 6.5rem;
      position: absolute;
      bottom: 10px;
      right: 20px;
      color: rgba(255, 255, 255, 0.04);
      transform: rotate(15deg);
    }

    .dashboard-header::backdrop {
      backdrop-filter: blur(4px);
    }

    .dashboard-header h2 {
      font-weight: 800;
      font-size: 2.5rem;
      color: #ffd43b;
      margin-bottom: 0.5rem;
      position: relative;
    }

    .dashboard-header h2::after {
      content: "";
      display: block;
      width: 80px;
      height: 4px;
      background: #ffd43b;
      margin: 12px auto 0 auto;
      border-radius: 2px;
    }

    .dashboard-header p {
      font-size: 1.1rem;
      color: #dee2e6;
    }


    .card-custom {
      border-radius: 18px;
      padding: 2.2rem;
      color: white;
      position: relative;
      overflow: hidden;
      transition: 0.3s;
      min-height: 150px;
    }

    .card-custom .card-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .card-custom .card-value {
      font-size: 2.5rem;
      font-weight: 800;
    }

    .card-custom i {
      font-size: 3rem;
      position: absolute;
      top: 15px;
      right: 20px;
      opacity: 0.1;
    }

    .bg-event { background: linear-gradient(135deg, #0d3c61, #1a4f78); }
    .bg-form { background: linear-gradient(135deg, #198754, #28a745); }
    .bg-location { background: linear-gradient(135deg, #ffc107, #ffcd39); }

    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #1f3b57;
    }

    .event-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.2rem;
    }


    .event-card {
      border-radius: 16px;
      background-color: #ffffff;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: 0.3s;
    }

    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .event-image {
      width: 100%;
      height: 140px;
      object-fit: cover;
    }

    .event-body {
      padding: 1rem;
    }

    .event-title, .event-detail {
  word-wrap: break-word;
  overflow-wrap: break-word;
}


    .event-icon {
      font-size: 1.3rem;
      color: #0d3c61;
    }

    .pagination {
      justify-content: center;
    }

    .pagination .page-link {
      color: #0d6efd;
    }

    .pagination .active .page-link {
      background-color: #0d6efd;
      border-color: #0d6efd;
      color: white;
    }

    @media (max-width: 768px) {
      .event-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .event-grid {
        grid-template-columns: repeat(1, 1fr);
      }
    }

    .btn-edit {
  color: #0d3c61;
  border: 2px solid #0d3c61;
  background-color: #ffffff;
  transition: 0.3s ease;
}

.btn-edit,
.btn-delete {
  border-radius: 10px;
  padding: 0.4rem 1.2rem;
  font-weight: 500;
  border: none;
  color: #fff;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.btn-edit {
  background: linear-gradient(135deg, #ffc107, #ffcd39);
}

.btn-edit:hover {
  background: linear-gradient(135deg, #ffca2c, #ffe066);
  transform: translateY(-2px);
}

.btn-delete {
  background: linear-gradient(135deg, #dc3545, #e55363);
}

.btn-delete:hover {
  background: linear-gradient(135deg, #bd2130, #d73849);
  transform: translateY(-2px);
}

/* Tombol sejajar di tengah */
.event-buttons {
  display: flex;
  justify-content: center;
  gap: 0.8rem;
  margin: 0.5rem auto 1rem auto;
}

.btn-add-event {
  background: linear-gradient(135deg, #198754, #28a745);
  color: white;
  font-weight: 600;
  padding: 0.5rem 1.2rem;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: 0.3s ease;
  text-decoration: none;
}

.btn-add-event:hover {
  background: linear-gradient(135deg, #157347, #1e9b5c);
  transform: translateY(-2px);
  color: white;
}

.logout-btn {
      background: linear-gradient(135deg, #dc3545, #e55363);
      color: white;
      padding: 0.5rem 1.2rem;
      border-radius: 10px;
      font-weight: 600;
      position: absolute;
      top: 20px;
      right: 20px;
      transition: 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-decoration: none;
    }

    .logout-btn:hover {
      background: linear-gradient(135deg, #bd2130, #d73849);
      transform: translateY(-2px);
    }



  </style>
</head>
<body>

<div class="container mt-5">
  <!-- Header -->
  <div class="dashboard-header text-center">
    <h2>🎫 Dashboard Admin TixFest</h2>
    <p>Kontrol dan pantau statistik event & form TixFest kamu di sini</p>
    <a href="../auth/logout.php" class="logout-btn">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
    </a>
  </div>

  <!-- Statistik -->
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card card-custom bg-event shadow">
        <div class="card-title">Total Event</div>
        <div class="card-value"><?= $stats['jumlah_event'] ?></div>
        <i class="bi bi-calendar-event-fill"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-custom bg-form shadow">
        <div class="card-title">Total Form Masuk</div>
        <div class="card-value"><?= $stats['jumlah_form'] ?></div>
        <i class="bi bi-journal-text"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-custom bg-location shadow">
        <div class="card-title">Lokasi Populer</div>
        <div class="card-value"><?= $stats['lokasi_populer']['lokasi'] ?? 'Belum Ada' ?></div>
        <i class="bi bi-geo-alt-fill"></i>
      </div>
    </div>
  </div>

  <!-- Event Terbaru -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="section-title mb-0">📅 Event Terbaru</h4>
    <a href="../event/add_event.php" class="btn btn-add-event">
    <i class="bi bi-plus-circle me-1"></i> Tambah Event
    </a>
  </div>

  <div class="event-grid" style="max-height: 600px; overflow-y: auto;">
    <?php foreach ($event_terbaru as $event): ?>
      <div>
        <div class="event-card">
          <a href="../detail_event1.php?id=<?= $event['id_event'] ?>" class="text-decoration-none text-dark">
            <img src="../uploads/<?= $event['gambar_event'] ?>" class="event-image" alt="<?= $event['nama_event'] ?>">
            <div class="event-body">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="event-title"><?= $event['nama_event'] ?></div>
                  <div class="event-detail">
                    <i class="bi bi-geo-alt"></i> <?= $event['lokasi'] ?><br>
                    <i class="bi bi-calendar"></i> <?= $event['tanggal_event'] ?>
                  </div>
                </div>
                <div class="event-icon">
                  <i class="bi bi-ticket-detailed-fill"></i>
                </div>
              </div>
            </div>
          </a>
          <!-- Tombol Edit & Delete di dalam card -->
          <div class="event-buttons mt-3">
            <a href="../event/edit_event.php?id=<?= $event['id_event'] ?>" class="btn btn-sm btn-edit">
              <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="../event/delete_event.php?id=<?= $event['id_event'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus event ini?')">
              <i class="bi bi-trash-fill me-1"></i> Delete
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>

</html>
