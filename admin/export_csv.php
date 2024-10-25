<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

$query = "SELECT u.name, u.email, r.registration_date 
          FROM registrations r
          JOIN users u ON r.user_id = u.id";
$participants = $conn->query($query);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="participants.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Name', 'Email', 'Registration Date')); // Menulis header

while ($row = $participants->fetch_assoc()) {
    
    $row['registration_date'] = date('d/m/Y', strtotime($row['registration_date']));
    
    $row = array_map('trim', $row);

    fputcsv($output, $row);
}

fclose($output);
exit();