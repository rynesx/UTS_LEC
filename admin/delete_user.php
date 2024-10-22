<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Validasi admin
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin['role'] !== 'admin') {
    redirect('../user/dashboard.php');
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === 0 || $user_id === $admin_id) {
    $_SESSION['error'] = "Invalid user ID or attempting to delete admin account";
    redirect('manage_users.php');
}

// Begin transaction
$conn->begin_transaction();

try {
    // Delete user's event registrations first
    $stmt = $conn->prepare("DELETE FROM event_registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Then delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();
    
    $_SESSION['message'] = "User successfully deleted";
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
}

redirect('manage_users.php');
?>