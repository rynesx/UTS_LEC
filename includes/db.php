<?php

$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'concert_event_system';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); 
    die("Database connection error."); 
}

/**
 * 
 *
 * @param string 
 * @param array 
 * @return mixed 
 */
function dbQuery($query, $params = []) {
    global $conn;
    
    if (empty($query)) {
        die("Query cannot be empty.");
    }

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        error_log("Error preparing the statement: " . $conn->error); 
        die("Error preparing the statement.");
    }
    if (!empty($params)) {
       
        $types = str_repeat('s', count($params)); 
        
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error binding parameters: " . $stmt->error); 
            die("Error binding parameters.");
        }
    }

    if (!$stmt->execute()) {
        error_log("Error executing the query: " . $stmt->error); 
        die("Error executing the query.");
    }

    $result = $stmt->get_result();
    if ($result === false && $stmt->errno) {
        error_log("Error getting result: " . $stmt->error); 
        die("Error getting result.");
    }

    return $result; 
}

function closeConnection() {
    global $conn;
    $conn->close(); 
}
?>
