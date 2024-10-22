<?php
require '../includes/db.php';
require '../includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die('Invalid event ID');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);
    $max_participants = intval($_POST['max_participants']);
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Get existing image path
        $stmt = $conn->prepare("SELECT image_path FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        
        // Delete old image if exists
        if ($event['image_path'] && file_exists($event['image_path'])) {
            unlink($event['image_path']);
        }
        
        // Upload new image
        $image_path = '../uploads/' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        
        // Update with new image
        $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssssis", $name, $date, $time, $location, $description, $max_participants, $image_path, $id);
    } else {
        // Update without changing image
        $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ? WHERE id = ?");
        $stmt->bind_param("ssssiii", $name, $date, $time, $location, $description, $max_participants, $id);
    }
    
    
    if ($stmt->execute()) {
        header('Location: ../index.php');
        exit;
    } else {
        $error = "Error updating event";
    }
}

// Get event data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die('Event not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="path/to/tailwind.css">
</head>
<body>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Edit Event</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block">Event Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required 
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block">Date</label>
                <input type="datetime-local" name="date" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime($event['date'])); ?>" required 
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block">Time</label>
                <input type="time" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" required 
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block">Location</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required 
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block">Description</label>
                <textarea name="description" required class="w-full p-2 border rounded"><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>

            <div>
                <label class="block">Max Participants</label>
                <input type="number" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required 
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block">Image</label>
                <?php if ($event['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Current event image" class="w-48 mb-2">
                <?php endif; ?>
                <input type="file" name="image" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update Event
            </button>
            <a href="../index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
        </form>
    </div>
</body>
</html>
