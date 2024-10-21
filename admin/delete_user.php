<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    redirect('../login.php');
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    redirect('user_management.php');
}
?>