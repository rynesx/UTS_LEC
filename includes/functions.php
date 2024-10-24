<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Memulai sesi hanya jika belum ada sesi yang aktif
}

define('SITE_NAME', 'EventPlay'); // Definisikan nama situs

function sanitize($data) {
    // Menggunakan htmlspecialchars untuk mencegah XSS
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return sanitize($data); // Menggunakan fungsi sanitize untuk melakukan sanitasi lebih lanjut
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

    // Pastikan query tidak kosong sebelum dilanjutkan
    if (empty($query)) {
        die("Query is required.");
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        error_log("Error preparing query: " . $conn->error); // Log error untuk audit
        die("Error preparing query.");
    }

    // Binding parameter dengan validasi tipe data
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Asumsikan semua parameter adalah string
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error binding parameters: " . $stmt->error); // Log error untuk audit
            die("Error binding parameters.");
        }
    }

    // Eksekusi dan pemeriksaan hasil
    if (!$stmt->execute()) {
        error_log("Error executing query: " . $stmt->error); // Log error
        die("Error executing query.");
    }

    return $stmt->affected_rows > 0;  // Mengembalikan true jika ada baris yang terpengaruh
}

// Pengaturan untuk mencegah sql injection
function safeQuery($sql, $params) {
    global $conn;

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        die("Error preparing statement");
    }

    // Binding parameter
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Mengasumsikan parameter string
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error binding parameters: " . $stmt->error);
            die("Error binding parameters");
        }
    }

    // Eksekusi query
    if (!$stmt->execute()) {
        error_log("Error executing query: " . $stmt->error);
        die("Error executing query");
    }

    return $stmt->get_result(); // Mengembalikan hasil untuk selanjutnya digunakan
}
?>
