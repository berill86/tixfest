<?php
require_once('../config/koneksi.php'); // pastikan path ini benar

class EventController {
    private $conn;

    public function __construct() {
        global $koneksi;
        $this->conn = $koneksi;
    }

    public function getAllEvents() {
        $query = "SELECT * FROM event ORDER BY tanggal_event DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEventById($id) {
        $query = "SELECT * FROM event WHERE id_event = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addEvent($data, $gambar_event)
{
    global $koneksi;

    $nama_event = mysqli_real_escape_string($koneksi, $data['nama_event']);
    $lokasi = mysqli_real_escape_string($koneksi, $data['lokasi']);
    $tanggal_event = mysqli_real_escape_string($koneksi, $data['tanggal_event']);
    $info_event = mysqli_real_escape_string($koneksi, $data['info_event']);
    $harga = floatval($data['harga']); // Ambil dan konversi ke float

    // Proses upload gambar
    $gambar_name = null;
    if ($gambar_event && $gambar_event['error'] === 0) {
        $gambar_name = time() . '_' . basename($gambar_event['name']);
        $target_path = '../uploads/' . $gambar_name;
        move_uploaded_file($gambar_event['tmp_name'], $target_path);
    }

    $query = "INSERT INTO event (nama_event, lokasi, tanggal_event, info_event, gambar_event, harga)
              VALUES ('$nama_event', '$lokasi', '$tanggal_event', '$info_event', '$gambar_name', '$harga')";

    return mysqli_query($koneksi, $query);
}



public function updateEvent($id, $nama, $lokasi, $tanggal, $gambar, $info, $harga)
{
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $nama);
    $lokasi = mysqli_real_escape_string($koneksi, $lokasi);
    $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
    $info = mysqli_real_escape_string($koneksi, $info);
    $harga = floatval($harga);

    $sql = "UPDATE event SET 
                nama_event = '$nama',
                lokasi = '$lokasi',
                tanggal_event = '$tanggal',
                info_event = '$info',
                harga = '$harga'";

    if (!empty($gambar)) {
        $sql .= ", gambar_event = '$gambar'";
    }

    $sql .= " WHERE id_event = '$id'";

    return mysqli_query($koneksi, $sql);
}

    

    public function deleteEvent($id) {
        $query = "DELETE FROM event WHERE id_event = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
}

