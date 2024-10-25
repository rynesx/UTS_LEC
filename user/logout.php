<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../includes/db.php'; 
require '../includes/functions.php';

session_destroy();

redirect('../index.php');
?>