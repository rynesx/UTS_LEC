<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die('Invalid event ID');
}

$stmt = $conn->prepare("SELECT image_path FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($event && $event['image_path'] && file_exists($event['image_path'])) {
    unlink($event['image_path']);
}

$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: ../admin/view_registration.php');
    exit;
} else {
    die('Error deleting event');
}
?>