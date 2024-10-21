<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../includes/db.php'; 
require '../includes/functions.php';

// Menghapus semua variabel sesi
session_destroy();

// Mengarahkan pengguna kembali ke halaman utama
redirect('../index.php');
?>