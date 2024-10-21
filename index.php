<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$events = $conn->query("SELECT * FROM events WHERE status = 'open' ORDER BY date ASC");
if ($events === false) {
    die("Error fetching events: " . $conn->error);
}

require_once 'includes/header.php';
?>

<h2 class="text-3xl font-bold mb-6">Upcoming Concerts</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if ($events->num_rows > 0): ?>
        <?php while ($event = $events->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($event['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="<?php echo htmlspecialchars($event['name']); ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['name']); ?></h3>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($event['date']) . ' ' . htmlspecialchars(date('H:i', strtotime($event['date']))); ?></p>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['location']); ?></p>
                    <a href="event_details.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No upcoming events found.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>