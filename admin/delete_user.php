<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Cek apakah user sudah login dan mendapatkan id admin
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

// Mendapatkan user_id dari URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === 0 || $user_id === $admin_id) {
    $_SESSION['error'] = "Invalid user ID or attempting to delete admin account.";
    redirect('user_management.php');
}

// Mulai transaksi
$conn->begin_transaction();

try {
    // Periksa apakah pengguna ada sebelum menghapus
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("User does not exist.");
    }

    // Hapus pendaftaran acara pengguna dari tabel registrations
    $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Hapus registrasi acara pengguna dari event_registrations jika ada
    $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Hapus pengguna dari tabel users
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Cek apakah pengguna berhasil dihapus
    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete user. User may not exist or already deleted.");
    }

    // Commit transaksi
    $conn->commit();
    $_SESSION['message'] = "User successfully deleted.";
} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    $conn->rollback();
    $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
}

// Redirect setelah proses
redirect('user_management.php');
?>
