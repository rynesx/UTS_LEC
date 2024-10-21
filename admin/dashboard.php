<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php'); // Redirect jika tidak ada sesi
}

// Mengambil informasi peran pengguna
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('../login.php'); // Redirect jika tidak ada hasil
}

// Ambil data penggunaan role
$user = $result->fetch_assoc();

// Redirect jika bukan admin
if ($user['role'] !== 'admin') {
    redirect('../user/dashboard.php'); // Redirect ke dashboard pengguna
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"> <!-- Ganti dengan jalur CSS Anda -->
</head>
<body class="bg-gray-100">

<!-- Header -->
<?php require '../includes/header.php'; ?>

<div class="p-6">
    <h1 class="text-3xl font-bold mb-4">Welcome, Admin!</h1>
    <p>Use the menu to manage users and events.</p>
    <!-- Konten untuk admin di sini -->
</div>

<!-- Footer -->
<?php require '../includes/footer.php'; ?>

</body>
</html>