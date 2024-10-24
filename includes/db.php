<?php
// Menggunakan variabel lingkungan untuk kredensial database
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'concert_event_system';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log error untuk audit
    die("Database connection error."); // Memberikan informasi umum kepada pengguna
}

function dbQuery($query, $params = []) {
    global $conn;
    
    // Memastikan kueri yang valid
    if (empty($query)) {
        die("Query cannot be empty.");
    }

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        error_log("Error preparing the statement: " . $conn->error); // Log error
        die("Error preparing the statement.");
    }

    if (!empty($params)) {
        // Bentuk tipe parameter, sesuaikan dengan jenis data
        $types = str_repeat('s', count($params));
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error binding parameters: " . $stmt->error); // Log error
            die("Error binding parameters.");
        }
    }

    // Eksekusi pernyataan
    if (!$stmt->execute()) {
        error_log("Error executing the query: " . $stmt->error); // Log error
        die("Error executing the query.");
    }

    $result = $stmt->get_result();
    if ($result === false && $stmt->errno) {
        error_log("Error getting result: " . $stmt->error); // Log error
        die("Error getting result.");
    }

    return $result;
}

?>
