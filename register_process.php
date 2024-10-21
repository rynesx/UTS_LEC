<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $role = !empty($_POST['is_admin']) ? 'admin' : 'user'; // Tentukan peran berdasarkan checkbox

    // Validasi dan lainnya...
    
    // Menyimpan ke database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role); // Sertakan role
}