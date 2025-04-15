<?php
include('../config/koneksi.php');

class DashboardController {
    public function getStats() {
        global $koneksi;

        $data = [];

        // Jumlah event
        $query1 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event");
        $data['jumlah_event'] = mysqli_fetch_assoc($query1)['total'];

        // Jumlah form masuk
        $query2 = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM form");
        $data['jumlah_form'] = mysqli_fetch_assoc($query2)['total'];

        // Lokasi terpopuler
        $query3 = mysqli_query($koneksi, "SELECT lokasi, COUNT(*) AS total FROM event GROUP BY lokasi ORDER BY total DESC LIMIT 1");
        $data['lokasi_populer'] = mysqli_fetch_assoc($query3);

        // Event terbaru
        $query4 = mysqli_query($koneksi, "SELECT * FROM event ORDER BY id_event DESC");
        $data['event_terbaru'] = [];
        while ($row = mysqli_fetch_assoc($query4)) {
            $data['event_terbaru'][] = $row;
        }

        return $data;
    }
}
?>

