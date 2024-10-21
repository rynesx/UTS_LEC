<?php
require_once 'config.php';

function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function dbQuery($sql, $params = []) {
    $conn = dbConnect();
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    return $result;
}

function dbInsert($sql, $params = []) {
    $conn = dbConnect();
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $insertId = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    
    return $insertId;
}