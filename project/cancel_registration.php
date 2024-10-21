<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $event_id > 0) {
    $result = dbQuery("DELETE FROM registrations WHERE user_id = ? AND event_id = ?", [$user_id, $event_id]);
    
    if ($result) {
        dbQuery("UPDATE events SET current_participants = current_participants - 1 WHERE id = ?", [$event_id]);
        $_SESSION['message'] = "Your registration has been successfully canceled.";
    } else {
        $_SESSION['error'] = "Failed to cancel registration. Please try again.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header('Location: dashboard.php');
exit();
?>