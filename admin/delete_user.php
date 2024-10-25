<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

$admin_id = $_SESSION['user_id'] ?? null;
if (!$admin_id) {
    $_SESSION['error'] = "User not logged in.";
    redirect('../user/dashboard.php');
}

$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin || $admin['role'] !== 'admin') {
    redirect('../user/dashboard.php');
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === 0 || $user_id === $admin_id) {
    $_SESSION['error'] = "Invalid user ID or attempting to delete admin account.";
    redirect('user_management.php');
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("User does not exist.");
    }

    $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete user. User may not exist or already deleted.");
    }

    $conn->commit();
    $_SESSION['message'] = "User successfully deleted.";
} catch (Exception $e) {
    
    $conn->rollback();
    $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
}

redirect('user_management.php');
?>
