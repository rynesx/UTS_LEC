<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Pastikan session_start hanya dipanggil sekali
}
require_once 'db.php'; // Menghubungkan ke database
require_once 'functions.php'; // Mengambil fungsi-fungsi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/styles.css"> <!-- Pastikan jalur ini benar -->
</head>
<body class="bg-gray-100">
<header class="bg-purple-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold"><?php echo SITE_NAME; ?></h1>
        <nav>
    <ul class="flex space-x-4">
        <li><a href="/UTS_LEC/index.php" class="hover:underline">Home</a></li>
        <?php if (isLoggedIn()): ?>
            <li><a href="/UTS_LEC/user/dashboard.php" class="hover:underline">Dashboard</a></li>
            <li><a href="/UTS_LEC/user/profile.php" class="hover:underline">Profile</a></li>
            <li><a href="/UTS_LEC/user/logout.php" class="hover:underline">Logout</a></li>
        <?php else: ?>
            <li><a href="/UTS_LEC/login.php" class="hover:underline">Login</a></li>
            <li><a href="/UTS_LEC/register.php" class="hover:underline">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

    </div>
</header>
<main class="container mx-auto mt-8">