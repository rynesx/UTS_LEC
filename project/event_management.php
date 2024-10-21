<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

redirectIfNotAdmin();

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create' || $action === 'edit') {
            $name = sanitizeInput($_POST['name']);
            $date = sanitizeInput($_POST['date']);
            $time = sanitizeInput($_POST['time']);
            $location = sanitizeInput($_POST['location']);
            $description = sanitizeInput($_POST['description']);
            $max_participants = intval($_POST['max_participants']);

            if (empty($name) || empty($date) || empty($time) || empty($location) || empty($description) || $max_participants <= 0) {
                $errors[] = "All fields are required and max participants must be greater than 0.";
            } else {
                $image_path = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $image_path = handleImageUpload($_FILES['image']);
                    if (!$image_path) {
                        $errors[] = "Failed to upload image.";
                    }
                }

                if (empty($errors)) {
                    if ($action === 'create') {
                        $event_id = dbInsert("INSERT INTO events (name, date, time, location, description, max_participants, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)", 
                            [$name, $date, $time, $location, $description, $max_participants, $image_path]);
                        if ($event_id) {
                            $success_message = "Event created successfully.";
                        } else {
                            $errors[] = "Failed to create event.";
                        }
                    } elseif ($action === 'edit') {
                        $event_id = intval($_POST['event_id']);
                        $result = dbQuery("UPDATE events SET name = ?, date = ?, time = ?, location = ?, description = ?, max_participants = ?, image_path = COALESCE(?, image_path) WHERE id = ?", 
                            [$name, $date, $time, $location, $description, $max_participants, $image_path, $event_id]);
                        if ($result) {
                            $success_message = "Event updated successfully.";
                        } else {
                            $errors[] = "Failed to update event.";
                        }
                    }
                }
            }
        } elseif ($action === 'delete') {
            $event_id = intval($_POST['event_id']);
            $result = dbQuery("DELETE FROM events WHERE id = ?", [$event_id]);
            if ($result) {
                $success_message = "Event deleted successfully.";
            } else {
                $errors[] = "Failed to delete event.";
            }
        }
    }
}

$events = dbQuery("SELECT * FROM events ORDER BY date ASC");

function handleImageUpload($file) {
    $target_dir = UPLOAD_DIR;
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_file_name = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_file_name;

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return '/uploads/event_images/' . $new_file_name;
    } else {
        return false;
    }
}
?>

<h2 class="text-3xl font-bold mb-6">Event Management</h2>

<?php if (!empty($errors)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
<ul>
    <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
    <?php endforeach; ?>
</ul>
</div>

<?php endif; ?> 

<?php if ($success_message): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
<?php echo $success_message; ?>
</div>
<?php endif; ?>

<h3 class="text-2xl font-semibold mb-4">Create New Event</h3>
<form method="POST" action="" enctype="multipart/form-data" class="mb-8">
<input type="hidden" name="action" value="create">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
    <label for="name" class="block mb-2">Event Name</label>
    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded">
</div>
<div>
    <label for="date" class="block mb-2">Date</label>
    <input type="date" id="date" name="date" required class="w-full px-3 py-2 border rounded">
</div>
<div>
    <label for="time" class="block mb-2">Time</label>
    <input type="time" id="time" name="time" required class="w-full px-3 py-2 border rounded">
</div>
<div>
    <label for="location" class="block mb-2">Location</label>
    <input type="text" id="location" name="location" required class="w-full px-3 py-2 border rounded">
</div>
<div>
    <label for="max_participants" class="block mb-2">Max Participants</label>
    <input type="number" id="max_participants" name="max_participants" required class="w-full px-3 py-2 border rounded">
</div>
<div>
    <label for="image" class="block mb-2">Event Image</label>
    <input type="file" id="image" name="image" accept="image/*" class="w-full px-3 py-2 border rounded">
</div>
</div>
<div class="mt-4">
<label for="description" class="block mb-2">Description</label>
<textarea id="description" name="description" required class="w-full px-3 py-2 border rounded" rows="4"></textarea>
</div>
<button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Event</button>
</form>

<h3 class="text-2xl font-semibold mb-4">Existing Events</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php while ($event = $events->fetch_assoc()): ?>
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if ($event['image_path']): ?>
        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="<?php echo htmlspecialchars($event['name']); ?>" class="w-full h-48 object-cover">
    <?php endif; ?>
    <div class="p-4">
        <h4 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['name']); ?></h4>
        <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($event['date']) . ' ' . htmlspecialchars($event['time']); ?></p>
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['location']); ?></p>
        <button onclick="openEditModal(<?php echo $event['id']; ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</button>
        <form method="POST" action="" class="inline-block ml-2">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
        </form>
    </div>
</div>
<?php endwhile; ?>
</div>

<!-- Edit Event Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
<h3 class="text-2xl font-semibold mb-4">Edit Event</h3>
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" id="edit_event_id" name="event_id">
    <div class="mb-4">
        <label for="edit_name" class="block mb-2">Event Name</label>
        <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_date" class="block mb-2">Date</label>
        <input type="date" id="edit_date" name="date" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_time" class="block mb-2">Time</label>
        <input type="time" id="edit_time" name="time" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_location" class="block mb-2">Location</label>
        <input type="text" id="edit_location" name="location" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_max_participants" class="block mb-2">Max Participants</label>
        <input type="number" id="edit_max_participants" name="max_participants" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_image" class="block mb-2">Event Image</label>
        <input type="file" id="edit_image" name="image" accept="image/*" class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="edit_description" class="block mb-2">Description</label>
        <textarea id="edit_description" name="description" required class="w-full px-3 py-2 border rounded" rows="4"></textarea>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Event</button>
    <button type="button" onclick="closeEditModal()" class="ml-2 bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
</form>
</div>
</div>

<script>
function openEditModal(eventId) {
document.getElementById('edit_event_id').value = eventId;
document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
document.getElementById('editModal').style.display = 'none';
}
</script>

<?php require_once '../includes/footer.php'; ?>