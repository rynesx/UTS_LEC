<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';
require_once '../includes/db.php';

redirectIfNotLoggedIn();

$error = '';
$success = '';
$image_path = null; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
      
        $name = sanitizeInput($_POST['name'] ?? '');
        $date = sanitizeInput($_POST['date'] ?? '');
        $time = sanitizeInput($_POST['time'] ?? '');
        $location = sanitizeInput($_POST['location'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $max_participants = (int)sanitizeInput($_POST['max_participants'] ?? 0);
        $image_path = null;

       
        if (empty($name) || empty($date) || empty($time) || empty($location) || empty($description) || $max_participants <= 0) {
            throw new Exception('All fields are required and max participants must be greater than 0.');
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/events/'; 

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF files are allowed.');
            }

            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $upload_dir . $new_filename;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                throw new Exception('Failed to upload image.');
            }

            $image_path = 'uploads/events/' . $new_filename; 
        }

        $query = "INSERT INTO events (name, date, time, location, description, max_participants, image_path, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $name,
            $date,
            $time,
            $location,
            $description,
            $max_participants,
            $image_path
        ];

        if (dbInsert($query, $params)) {
            $_SESSION['success_message'] = 'Event created successfully!';
            redirect('view_registration.php');
        } else {
            throw new Exception('Failed to create event. Please try again.');
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event - <?php echo SITE_NAME; ?></title>
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
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 500;
        }

        .error-message {
            background-color: #FEE2E2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message {
            background-color: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #047857;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 16px;
            color: #1F2937;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #D1D5DB;
            border-radius: 8px;
            cursor: pointer;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button[type="submit"],
        .cancel-button {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            min-width: 120px;
        }

        button[type="submit"] {
            background-color: #6366F1;
            color: white;
            border: none;
        }

        button[type="submit"]:hover {
            background-color: #4F46E5;
        }

        .cancel-button {
            background-color: #F3F4F6;
            color: #4B5563;
            border: 1px solid #D1D5DB;
        }

        .cancel-button:hover {
            background-color: #E5E7EB;
        }

        .preview-image {
            max-width: 100%; 
            border-radius: 10px; 
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Event</h2>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-grid">
                <div class="left-column">
                    <div class="form-group">
                        <label for="name">Event Name</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" required 
                               value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" required 
                               value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" required 
                               value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="max_participants">Maximum Participants</label>
                        <input type="number" id="max_participants" name="max_participants" min="1" required 
                               value="<?php echo isset($_POST['max_participants']) ? htmlspecialchars($_POST['max_participants']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Event Image</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                    </div>

                    <!-- Menambahkan penampilan gambar jika ada -->
                    <?php if ($image_path): ?>
                        <div class="form-group">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Event Image" class="preview-image">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="button-group">
                <button type="submit">Create Event</button>
                <a href="view_registration.php" class="cancel-button">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
