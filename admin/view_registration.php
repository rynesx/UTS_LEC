<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitizeInput($_GET['search']);
    $events = dbQuery("SELECT * FROM events WHERE name LIKE ? AND status = 'open' ORDER BY date ASC", ['%' . $search_query . '%']);
} else {
    $events = dbQuery("SELECT * FROM events WHERE status = 'open' ORDER BY date ASC");
}

require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Concerts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-6">Upcoming Concerts</h2>

        <div class="mb-6 flex justify-end">
            <form action="index.php" method="GET" class="flex">
                <input type="text" name="search" placeholder="Search concerts..." value="<?php echo htmlspecialchars($search_query); ?>" required class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-r-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50">Search</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($events && $events->num_rows > 0): ?>
                <?php while ($event = $events->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <?php
                        if (!empty($event['image_path'])) {
                            $image_path = '../uploads/' . $event['image_path'];
                            if (file_exists($image_path)) {
                                echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($event['name']) . '" class="w-full h-48 object-cover">';
                            } else {
                                echo '<div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">Image not found</div>';
                            }
                        } else {
                            echo '<div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>';
                        }
                        ?>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['name']); ?></h3>
                            <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($event['date']) . ' ' . htmlspecialchars($event['time']); ?></p>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['location']); ?></p>
                            <a href="../includes/event_detail.php?id=<?php echo $event['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="col-span-full text-center text-gray-500">No upcoming events found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>