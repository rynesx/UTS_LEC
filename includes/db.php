<?php
// Menggunakan variabel lingkungan untuk kredensial database
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'concert_event_system';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

// Menginisialisasi koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi dan tangani kesalahan secara aman
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log error untuk audit
    die("Database connection error."); // Memberikan informasi umum kepada pengguna
}

/**
 * Melakukan query ke database dengan pengikat parameter untuk menghindari SQL Injection
 *
 * @param string $query Kueri SQL yang akan dieksekusi
 * @param array $params Parameter untuk kueri (opsional)
 * @return mixed Hasil kueri yang dieksekusi
 */
function dbQuery($query, $params = []) {
    global $conn;
    
    // Memastikan kueri yang valid
    if (empty($query)) {
        die("Query cannot be empty.");
    }

    // Menggunakan prepared statements
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        error_log("Error preparing the statement: " . $conn->error); // Log error
        die("Error preparing the statement.");
    }

    if (!empty($params)) {
        // Bentuk tipe parameter, sesuaikan dengan jenis data (asumsi semua parameter adalah string)
        $types = str_repeat('s', count($params)); // Untuk string, gunakan 's'
        
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

    // Mengambil hasil
    $result = $stmt->get_result();
    if ($result === false && $stmt->errno) {
        error_log("Error getting result: " . $stmt->error); // Log error
        die("Error getting result.");
    }

    return $result; // Kembalikan hasil dari query
}

// Pastikan untuk selalu menutup koneksi saat selesai
function closeConnection() {
    global $conn;
    $conn->close(); // Menutup koneksi database
}
?>
