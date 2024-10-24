<?php
$host = 'localhost';
$db = 'concert_event_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function dbQuery($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        if (!$stmt->bind_param($types, ...$params)) {
            die("Error binding parameters: " . $stmt->error);
        }
    }

    if (!$stmt->execute()) {
        die("Error executing the query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result === false && $stmt->errno) {
        die("Error getting result: " . $stmt->error);
    }

    return $result;
}

?>
