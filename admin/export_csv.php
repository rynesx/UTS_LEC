<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Validasi admin
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

// Query untuk mendapatkan data peserta
$query = "SELECT u.name, u.email, r.registration_date 
          FROM registrations r
          JOIN users u ON r.user_id = u.id";
$participants = $conn->query($query);

// Mengatur header untuk file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="participants.csv"');

// Membuka output untuk menulis CSV
$output = fopen('php://output', 'w');
fputcsv($output, array('Name', 'Email', 'Registration Date')); // Menulis header

// Menulis data peserta ke file CSV
while ($row = $participants->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();