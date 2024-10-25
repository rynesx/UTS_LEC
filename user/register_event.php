<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}


$events = $conn->query("SELECT * FROM events WHERE status = 'open'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link rel="stylesheet" href="path/to/tailwind.css"> 
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Register for Upcoming Events</h2>
        <?php if ($events->num_rows > 0): ?>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Date</th>
                        <th class="py-2 px-4">Location</th>
                        <th class="py-2 px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $events->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4"><?php echo htmlspecialchars($event['name']); ?></td>
                        <td class="py-2 px-4"><?php echo htmlspecialchars($event['date']); ?></td>
                        <td class="py-2 px-4"><?php echo htmlspecialchars($event['location']); ?></td>
                        <td class="py-2 px-4">
                            <form method="post" action="register_process.php" class="inline-block">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Register</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-700">No events available for registration at this time.</p>
        <?php endif; ?>
    </div>
</body>
</html>
