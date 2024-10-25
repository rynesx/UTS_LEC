<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

define('SITE_NAME', 'EventPlay'); 

function sanitize($data) {
   
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return sanitize($data); 
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
    return isset($_SESSION['user_id']); 


function dbInsert($query, $params = []) {
    global $conn;


    if (empty($query)) {
        die("Query is required.");
    }

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        error_log("Error preparing query: " . $conn->error); 
        die("Error preparing query.");
    }

   
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); 
        if (!$stmt->bind_param($types, ...$params)) {
            error_log("Error binding parameters: " . $stmt->error); 
            die("Error binding parameters.");
        }
    }
}
  
    if (!$stmt->execute()) {
        error_log("Error executing query: " . $stmt->error); 
        die("Error executing query.");
    }

    return $stmt->affected_rows > 0; 
}


function safeQuery($sql, $params) {
    global $conn;

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        die("Error preparing statement");
    }

   
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); 
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

    return $stmt->get_result(); 
}
?>