<?php
$host = 'localhost';
$db = 'concert_event_system';
$user = 'root';
$pass = '';

// Inisialisasi koneksi database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk menjalankan query dengan parameter
function dbQuery($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Asumsikan semua parameter adalah string
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    // Menggunakan get_result untuk mengambil hasil query
    $result = $stmt->get_result();
    return $result;
}

?>
