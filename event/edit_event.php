<?php
require_once '../controllers/EventController.php';
require_once '../config/koneksi.php';

$eventController = new EventController();
$id = $_GET['id'];
$event = $eventController->getEventById($id);

// Ambil enum lokasi
$enum_query = mysqli_query($koneksi, "SHOW COLUMNS FROM event LIKE 'lokasi'");
$enum_row = mysqli_fetch_assoc($enum_query);
$enum_values = [];

if (preg_match("/^enum\((.*)\)$/", $enum_row['Type'], $matches)) {
    $enum_str = $matches[1];
    $enum_values = array_map(fn($val) => trim($val, "'"), explode(",", $enum_str));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_event'];
    $lokasi = $_POST['lokasi'];
    $tanggal = $_POST['tanggal_event'];
    $info = $_POST['info_event'];
    $gambar = null;
    $harga = $_POST['harga'];

    if (!empty($_FILES['gambar_event']['name'])) {
        $gambar = $_FILES['gambar_event']['name'];
        $tmp = $_FILES['gambar_event']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/" . $gambar);
    }

    $eventController->updateEvent($id, $nama, $lokasi, $tanggal, $gambar, $info, $harga);
    header("Location: ../admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Event | TixFest</title>
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
            max-width: 650px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #0d1b2a;
        }

        .form-title {
            text-align: center;
            font-size: 28px;
            color: #0d1b2a;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #0d1b2a;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #d1d5db;
            border-radius: 10px;
            background-color: #f9fafb;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #0d1b2a;
            background-color: #ffffff;
            outline: none;
        }

        input[type="file"] {
            margin-top: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            width: 100%;
            background-color: #0d1b2a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-submit:hover {
            background-color: #142d44;
            transform: scale(1.02);
        }

        @media (max-width: 600px) {
            .form-wrapper {
                padding: 30px 20px;
            }
        }

        .form-wrapper {
    width: 100%;
    max-width: 460px; /* Tetap proporsional tapi lebih kecil */
    background-color: #ffffff;
    padding: 24px;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    border-top: 5px solid #0d1b2a;
    max-height: 100vh;
    overflow-y: auto;
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
    margin-bottom: 6px;
    font-size: 14px;
}

input[type="text"],
input[type="date"],
input[type="number"],
select,
textarea {
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
}

input[type="file"] {
    font-size: 13px;
}

textarea {
    min-height: 100px;
}

.btn-submit {
    padding: 12px;
    font-size: 15px;
    border-radius: 8px;
}

    </style>
</head>
<body>

<div class="form-wrapper">
    <h1 class="form-title">Edit Event</h1>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Event</label>
            <input type="text" name="nama_event" value="<?= htmlspecialchars($event['nama_event']) ?>" required>
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <select name="lokasi" required>
                <option value="">-- Pilih Lokasi --</option>
                <?php foreach ($enum_values as $value): ?>
                    <option value="<?= htmlspecialchars($value) ?>" <?= $value == $event['lokasi'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Event</label>
            <input type="date" name="tanggal_event" value="<?= $event['tanggal_event'] ?>" required>
        </div>

        <div class="form-group">
            <label>Info Event</label>
            <textarea name="info_event" placeholder="Deskripsi lengkap acara..."><?= htmlspecialchars($event['info_event']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Gambar Event</label>
            <input type="file" name="gambar_event">
        </div>

        <div class="form-group">
            <label>Harga Tiket (Rp)</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($event['harga']) ?>" min="0" step="any" required>
        </div>

        <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
