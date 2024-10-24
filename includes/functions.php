<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Memulai sesi hanya jika belum ada sesi yang aktif
}
define('SITE_NAME', 'EventPlay'); // Definisikan nama situs

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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

function dbInsert($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Asumsikan semua parameter adalah string
        $stmt->bind_param($types, ...$params);
    }

    $result = $stmt->execute();

    if ($result === false) {
        die("Error executing query: " . $stmt->error);
    }

    return $stmt->affected_rows > 0;  // Mengembalikan true jika ada baris yang terpengaruh
}

?>