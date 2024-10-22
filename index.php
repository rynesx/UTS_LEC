<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$events = $conn->query("SELECT * FROM events WHERE status = 'open' ORDER BY date ASC");
if ($events === false) {
    die("Error fetching events: " . $conn->error);
}

require_once 'includes/header.php';

$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitizeInput($_GET['search']); // Penggunaan fungsi sanitizeInput
    $events = dbQuery("SELECT * FROM events WHERE name LIKE ? AND status = 'open' ORDER BY date ASC", ['%' . $search_query . '%']);
} else {
    $events = dbQuery("SELECT * FROM events WHERE status = 'open' ORDER BY date ASC");
}
?>

<h2 class="text-3xl font-bold mb-6">Upcoming Concerts</h2>

<div style="display: flex; align-items: right; margin-left: 75%;">
    <form action="index.php" method="GET" >
    <input type="text" name="search" placeholder="Search concerts..." value="<?php echo htmlspecialchars($search_query); ?>" required style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
    <button type="submit" style="background-color: #9372ff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Search</button>
    </form>
</div>

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
                    <a href="includes/event_detail.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No upcoming events found.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
