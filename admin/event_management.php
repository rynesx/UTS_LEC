<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Validasi admin
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    redirect('../user/dashboard.php');
}

// Mengambil daftar event
$events = $conn->query("SELECT * FROM events ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <?php require '../includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-4 md:mb-0">Manage Events</h1>
            <a href="add_event.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add New Event</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($event = $events->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($event['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" 
                                     alt="Event image" class="h-20 w-20 md:h-24 md:w-24 object-cover rounded">
                            <?php else: ?>
                                <div class="h-20 w-20 md:h-24 md:w-24 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-500">No image</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($event['name']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M d, Y H:i', strtotime($event['date'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($event['location']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="view_participants.php?event_id=<?php echo $event['id']; ?>" 
                               class="text-indigo-600 hover:text-indigo-900">View Participants</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex flex-col md:flex-row items-start md:items-center">
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3 mb-2 md:mb-0">Edit</a>
                            <button onclick="confirmDelete(<?php echo $event['id']; ?>)" 
                                    class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmDelete(eventId) {
        if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
            window.location.href = `delete_event.php?id=${eventId}`;
        }
    }
    </script>

    <?php require '../includes/footer.php'; ?>
</body>
</html>