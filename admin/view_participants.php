<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Validasi admin
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

// Mengambil user_id dan role
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    redirect('../admin/dashboard.php');
}

// Ambil event ID dari query parameter dan validasi
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Ambil data peserta event
$stmt = $conn->prepare("SELECT u.id, u.name, u.email FROM registrations ep
                        JOIN users u ON ep.user_id = u.id
                        WHERE ep.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$participants = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - Event Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <?php require '../includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Event Participants</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($participant = $participants->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($participant['name']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($participant['email']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="confirmRemove(<?php echo $participant['id']; ?>, <?php echo $event_id; ?>)"
                                    class="text-red-600 hover:text-red-900">Remove</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($participants->num_rows === 0): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center">No participants found for this event.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmRemove(userId, eventId) {
        if (confirm('Are you sure you want to remove this participant?')) {
            window.location.href = `remove_participant.php?user_id=${userId}&event_id=${eventId}`;
        }
    }
    </script>

    <?php require '../includes/footer.php'; ?>
</body>
</html>