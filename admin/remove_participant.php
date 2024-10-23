<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Validasi admin
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    redirect('../user/dashboard.php');
}

$event_id = $_GET['event_id'];
$participant_id = $_GET['user_id'];

// Hapus peserta dari event
$stmt = $conn->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
$stmt->bind_param("ii", $event_id, $participant_id);
$stmt->execute();

// Set pesan berhasil dan redirect
$_SESSION['message'] = "Participant removed successfully.";
redirect("view_participants.php?event_id=$event_id");
?>
