<?php
require_once 'header.php';
require_once 'db.php';
require_once 'functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$event_id = intval($_GET['id']);
$event = dbQuery("SELECT * FROM events WHERE id = ?", [$event_id])->fetch_assoc();

if (!$event) {
    header('Location: index.php');
    exit();
}

$is_registered = false;
$registration_error = '';
$registration_success = '';

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $registration = dbQuery("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?", [$user_id, $event_id]);
    $is_registered = $registration->num_rows > 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'register') {
            if ($event['current_participants'] < $event['max_participants']) {
                $result = dbInsert("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)", [$user_id, $event_id]);
                if ($result) {
                    dbQuery("UPDATE events SET current_participants = current_participants + 1 WHERE id = ?", [$event_id]);
                    $is_registered = true;
                    $registration_success = "You have successfully registered for this event.";
                } else {
                    $registration_error = "Failed to register for the event. Please try again.";
                }
            } else {
                $registration_error = "Sorry, this event is already full.";
            }
        } elseif ($_POST['action'] === 'unregister') {
            $result = dbQuery("DELETE FROM registrations WHERE user_id = ? AND event_id = ?", [$user_id, $event_id]);
            if ($result) {
                dbQuery("UPDATE events SET current_participants = current_participants - 1 WHERE id = ?", [$event_id]);
                $is_registered = false;
                $registration_success = "You have successfully unregistered from this event.";
            } else {
                $registration_error = "Failed to unregister from the event. Please try again.";
            }
        }
    }
}
?>

<h2 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($event['name'] ?? ''); ?></h2>

<?php if ($registration_error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($registration_error ?? ''); ?>
    </div>
<?php endif; ?>

<?php if ($registration_success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($registration_success ?? ''); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (!empty($event['image_path'])): ?>
        <img src="<?php echo htmlspecialchars($event['image_path'] ?? ''); ?>" alt="<?php echo htmlspecialchars($event['name'] ?? ''); ?>" class="w-full h-64 object-cover">
    <?php endif; ?>
    <div class="p-6">
        <p class="text-gray-600 mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($event['date'] ?? ''); ?></p>
        <p class="text-gray-600 mb-2"><strong>Time:</strong> <?php echo htmlspecialchars($event['time'] ?? ''); ?></p>
        <p class="text-gray-600 mb-2"><strong>Location:</strong> <?php echo htmlspecialchars($event['location'] ?? ''); ?></p>
        <p class="text-gray-600 mb-4"><strong>Available Slots:</strong> <?php echo ($event['max_participants'] - $event['current_participants']) ?? 0; ?> / <?php echo htmlspecialchars($event['max_participants'] ?? 0); ?></p>
        <p class="text-gray-800 mb-6"><?php echo nl2br(htmlspecialchars($event['description'] ?? '')); ?></p>
        
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
            <p>Please <a href="login.php" class="text-blue-500 hover:underline">log in</a> to register for this event.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
