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

    // Get existing image path
    $stmt = $conn->prepare("SELECT image_path FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    
    $image_path = $event['image_path'];  // Keep the current image path

    // Handle image upload if a new one is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/events/';  // Change to the events folder
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate a unique filename
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $new_image_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $new_image_path)) {
            // Delete old image if exists
            if ($event['image_path'] && file_exists($event['image_path'])) {
                unlink($event['image_path']);
            }
            $image_path = 'uploads/events/' . $new_filename;  // Update the path for the database
        } else {
            $error = "Failed to upload the image.";
        }
    }

    // Update event
    $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, image_path = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $date, $time, $location, $description, $max_participants, $image_path, $id);

    if ($stmt->execute()) {
        header('Location: view_registration.php');
        exit;
    } else {
        $error = "Error updating event: " . $stmt->error;
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
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 500;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .left-column, .right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 0;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 30px;
            font-size: 16px;
            color: #333;
            background: transparent;
            outline: none;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input::placeholder {
            color: #aaa;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        button[type="submit"], .cancel-button {
            padding: 15px 40px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            min-width: 150px;
        }

        button[type="submit"] {
            background-color: #7E57C2;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #6A48B0;
        }

        .cancel-button {
            background-color: #e0e0e0;
            color: #333;
        }

        .cancel-button:hover {
            background-color: #d0d0d0;
        }

        .preview-image {
            width: 100%;
            border-radius: 10px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Event</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="left-column">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Event Name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <input type="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
                    </div>

                    <div class="form-group">
                        <input type="time" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" required>
                    </div>

                    <div class="form-group">
                        <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <input type="number" name="max_participants" placeholder="Max Participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required>
                    </div>

                    <div class="form-group">
                        <input type="file" name="image" accept="image/*">
                        <?php if ($event['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Current event image" class="preview-image">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit">Update Event</button>
                <a href="view_registration.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>