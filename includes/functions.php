<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Memulai sesi hanya jika belum ada sesi yang aktif
}
define('SITE_NAME', 'Webprog UTS'); // Definisikan nama situs

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']); // Memeriksa jika pengguna sudah login
}
?>