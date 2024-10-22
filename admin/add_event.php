<?php
require '../includes/db.php';
require '../includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $date = $_POST['date'];
    // Extract time from the datetime-local input
    $time = date('H:i:s', strtotime($_POST['date'])); // Get only the time part
    $location = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);
    $max_participants = intval($_POST['max_participants']);
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Create uploads directory if it doesn't exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Upload new image
        $image_path = 'uploads/' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        
        // Insert with image
        $stmt = $conn->prepare("INSERT INTO events (name, date, time, location, description, max_participants, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $name, $date, $time, $location, $description, $max_participants, $image_path);
    } else {
        // Insert without image
        $stmt = $conn->prepare("INSERT INTO events (name, date, time, location, description, max_participants) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $name, $date, $time, $location, $description, $max_participants);
    }
    
    if ($stmt->execute()) {
        header('Location: ../index.php');
        exit;
    } else {
        $error = "Error adding event: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Event</title>
    <link rel="stylesheet" href="path/to/tailwind.css">
</head>
<body>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">Add New Event</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block">Event Name</label>
                <input type="text" name="name" required 
                       class="w-full p-2 border rounded"
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>

            <div>
                <label class="block">Date and Time</label>
                <input type="datetime-local" name="date" required 
                       class="w-full p-2 border rounded"
                       value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
            </div>

            <div>
                <label class="block">Location</label>
                <input type="text" name="location" required 
                       class="w-full p-2 border rounded"
                       value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
            </div>

            <div>
                <label class="block">Description</label>
                <textarea name="description" required 
                          class="w-full p-2 border rounded"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div>
                <label class="block">Max Participants</label>
                <input type="number" name="max_participants" required 
                       class="w-full p-2 border rounded"
                       value="<?php echo isset($_POST['max_participants']) ? htmlspecialchars($_POST['max_participants']) : ''; ?>">
            </div>

            <div>
                <label class="block">Image</label>
                <input type="file" name="image" class="w-full p-2 border rounded">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Add Event
                </button>
                <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>