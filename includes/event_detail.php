<?php
require_once 'header.php';
require_once 'db.php';
require_once 'functions.php';

// Check if event ID is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../index.php');
    exit();
}

$event_id = intval($_GET['id']);
$event = dbQuery("SELECT * FROM events WHERE id = ?", [$event_id])->fetch_assoc();

// Check if the event exists
if (!$event) {
    header('Location: ../index.php');
    exit();
}

$is_registered = false;
$registration_error = '';
$registration_success = '';

// Check if the user is logged in
if (isLoggedIn()) {
    // Check if the user has already registered
    $user_id = $_SESSION['user_id'];
    $registration_check = dbQuery("SELECT * FROM registrations WHERE event_id = ? AND user_id = ?", [$event_id, $user_id])->fetch_assoc();
    
    if ($registration_check) {
        $is_registered = true;
    }

    // Handle registration or unregistration
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'] ?? '';
        
        try {
            if ($action === 'register') {
                // Check if there are available slots
                if ($event['current_participants'] < $event['max_participants']) {
                    // Register user
                    $register_query = "INSERT INTO registrations (event_id, user_id) VALUES (?, ?)";
                    dbInsert($register_query, [$event_id, $user_id]);
                    
                    // Update current participants in the events table
                    $update_query = "UPDATE events SET current_participants = current_participants + 1 WHERE id = ?";
                    dbInsert($update_query, [$event_id]);
                    
                    $registration_success = 'You have successfully registered for this event!';
                    $is_registered = true;
                } else {
                    $registration_error = 'No available slots for this event.';
                }
            } elseif ($action === 'unregister') {
                // Unregister user
                $unregister_query = "DELETE FROM registrations WHERE event_id = ? AND user_id = ?";
                dbInsert($unregister_query, [$event_id, $user_id]);
                
                // Update current participants in the events table
                $update_query = "UPDATE events SET current_participants = current_participants - 1 WHERE id = ?";
                dbInsert($update_query, [$event_id]);
                
                $registration_success = 'You have successfully unregistered from this event.';
                $is_registered = false;
            }
        } catch (Exception $e) {
            $registration_error = 'An error occurred: ' . $e->getMessage();
        }
    }
}
?>

<h2 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($event['name']); ?></h2>

<!-- Display registration messages -->
<?php if ($registration_error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($registration_error); ?>
    </div>
<?php endif; ?>

<?php if ($registration_success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($registration_success); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Image display -->
    <?php
    // Check if image path exists
    if (!empty($event['image_path'])) {
        $image_path = '../uploads/events/' . basename($event['image_path']);
        error_log("Cek path gambar: " . $image_path);

        if (file_exists($image_path)) {
            error_log("Gambar ditemukan: " . $image_path);
            ?>
            <div class="relative h-64">
                <img src="<?php echo htmlspecialchars($image_path); ?>" 
                     alt="<?php echo htmlspecialchars($event['name']); ?>" 
                     class="absolute w-full h-full object-cover">
            </div>
            <?php
        } else {
            error_log("Gambar tidak ditemukan di: " . $image_path);
            ?>
            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                Image not found
            </div>
            <?php
        }
    } else {
        ?>
        <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">
            No Image
        </div>
        <?php
    }
    ?>

    <div class="p-6">
        <p class="text-gray-600 mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
        <p class="text-gray-600 mb-2"><strong>Time:</strong> <?php echo htmlspecialchars($event['time']); ?></p>
        <p class="text-gray-600 mb-2"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p class="text-gray-600 mb-4"><strong>Available Slots:</strong> <?php echo htmlspecialchars($event['max_participants'] - $event['current_participants']); ?> / <?php echo htmlspecialchars($event['max_participants']); ?></p>
        <p class="text-gray-800 mb-6"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        
        <?php if (isLoggedIn()): ?>
            <?php if ($is_registered): ?>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="unregister">
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Unregister</button>
                </form>
            <?php else: ?>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="register">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" <?php echo ($event['current_participants'] >= $event['max_participants']) ? 'disabled' : ''; ?>>
                        <?php echo ($event['current_participants'] >= $event['max_participants']) ? 'Event Full' : 'Register'; ?>
                    </button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p>Please <a href="../login.php" class="text-blue-500 hover:underline">log in</a> to register for this event.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
