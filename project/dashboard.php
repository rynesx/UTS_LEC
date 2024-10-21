<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$registered_events = dbQuery("SELECT e.* FROM events e JOIN registrations r ON e.id = r.event_id WHERE r.user_id = ? ORDER BY e.date ASC", [$user_id]);
?>

<h2 class="text-3xl font-bold mb-6">Your Dashboard</h2>

<h3 class="text-2xl font-semibold mb-4">Your Registered Concerts</h3>

<?php if ($registered_events->num_rows > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($event = $registered_events->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($event['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="<?php echo htmlspecialchars($event['name']); ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-4">
                    <h4 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['name']); ?></h4>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($event['date']) . ' ' . htmlspecialchars($event['time']); ?></p>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['location']); ?></p>
                    <a href="event_details.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                    <form method="POST" action="cancel_registration.php" class="inline-block ml-2">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to cancel your registration?')">Cancel Registration</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>You haven't registered for any concerts yet.</p>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>