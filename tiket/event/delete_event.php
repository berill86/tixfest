<?php
include_once('../controllers/EventController.php');

$controller = new EventController();
$id = $_GET['id'];

if ($controller->deleteEvent($id)) {
    header("Location: ../admin/dashboard.php");
    exit;
} else {
    echo "Gagal menghapus event.";
}
