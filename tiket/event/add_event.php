<?php
include_once('../controllers/EventController.php');
include_once('../config/koneksi.php');

$controller = new EventController();

// Ambil daftar lokasi dari kolom ENUM 'lokasi'
$enum_query = mysqli_query($koneksi, "SHOW COLUMNS FROM event LIKE 'lokasi'");
$enum_row = mysqli_fetch_assoc($enum_query);

$enum_values = [];
if (preg_match("/^enum\((.*)\)$/", $enum_row['Type'], $matches)) {
    $enum_str = $matches[1];
    $enum_values = array_map(function($val) {
        return trim($val, "'");
    }, explode(",", $enum_str));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gambar_event = $_FILES['gambar_event'] ?? null;

    if ($controller->addEvent($_POST, $gambar_event)) {
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        echo "<p class='text-danger'>❌ Gagal menambahkan event.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Event | TixFest</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-wrapper {
            width: 100%;
            max-width: 460px;
            background-color: #ffffff;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border-top: 5px solid #0d1b2a;
        }

        .form-title {
            text-align: center;
            font-size: 22px;
            color: #0d1b2a;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: #0d1b2a;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #0d1b2a;
            background-color: #ffffff;
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="file"] {
            font-size: 13px;
        }

        .btn-submit,
        .btn-back {
            display: inline-block;
            width: 100%;
            padding: 12px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 8px;
        }

        .btn-submit {
            background-color: #0d1b2a;
            color: #ffffff;
        }

        .btn-submit:hover {
            background-color: #142d44;
            transform: scale(1.02);
        }

        .btn-back {
            background-color: #9ca3af;
            color: #fff;
            margin-top: 12px;
        }

        .btn-back:hover {
            background-color: #6b7280;
        }

        @media (max-width: 600px) {
            .form-wrapper {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <h1 class="form-title">Tambah Event</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_event">Nama Event</label>
            <input type="text" id="nama_event" name="nama_event" required>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <select id="lokasi" name="lokasi" required>
                <option value="">-- Pilih Lokasi --</option>
                <?php foreach ($enum_values as $value): ?>
                    <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_event">Tanggal Event</label>
            <input type="date" id="tanggal_event" name="tanggal_event" required>
        </div>

        <div class="form-group">
            <label for="info_event">Info Event</label>
            <textarea id="info_event" name="info_event" placeholder="Deskripsi lengkap acara..."></textarea>
        </div>

        <div class="form-group">
            <label for="gambar_event">Gambar Event</label>
            <input type="file" id="gambar_event" name="gambar_event" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="harga">Harga Tiket (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" step="any" required>
        </div>

        <button type="submit" class="btn-submit">➕ Tambah Event</button>
    </form>
</div>

</body>
</html>
