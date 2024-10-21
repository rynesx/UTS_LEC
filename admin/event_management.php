<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $date = $_POST['date']; // Format: YYYY-MM-DD HH:MM:SS
    $location = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);
    $max_participants = intval($_POST['max_participants']);
    
    // Upload Gambar
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO events (name, date, location, description, max_participants, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $date, $location, $description, $max_participants, $image_path);
    $stmt->execute();
}

// Mengambil acara
$events = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management</title>
    <link rel="stylesheet" href="path/to/tailwind.css"> <!-- Ganti dengan jalur CSS Anda -->
</head>
<body>
    <h2 class="mt-6">Manage Events</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" required placeholder="Event Name">
        <input type="datetime-local" name="date" required>
        <input type="text" name="location" required placeholder="Location">
        <textarea name="description" required placeholder="Description"></textarea>
        <input type="number" name="max_participants" required placeholder="Max Participants">
        <input type="file" name="image">
        <button type="submit">Add Event</button>
    </form>

    <h3 class="mt-6">Existing Events</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
        <?php while ($event = $events->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($event['name']); ?></td>
            <td><?php echo htmlspecialchars($event['date']); ?></td>
            <td><?php echo htmlspecialchars($event['location']); ?></td>
            <td>
                <a href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a> |
                <a href="delete_event.php?id=<?php echo $event['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>