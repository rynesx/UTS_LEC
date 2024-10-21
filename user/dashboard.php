<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    redirect('../login.php'); // Redirect jika tidak ada sesi
}

// Mengambil informasi pengguna
$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Ensure Tailwind CSS is connected -->
    <style>
        .dashboard-container {
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Header -->
<?php require '../includes/header.php'; ?>

<div class="dashboard-container">
    <h1 class="text-3xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Registered Events</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 border-b-2 border-gray-300">Event Name</th>
                    <th class="py-2 border-b-2 border-gray-300">Date</th>
                    <th class="py-2 border-b-2 border-gray-300">Location</th>
                </tr>
            </thead>
            <tbody>
            <form action="register_event.php" method="POST">
                <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-blue-600">Register Concert</button>
            </form>
                <?php
                // Mengambil acara yang terdaftar oleh pengguna
                $registrations = $conn->query("SELECT events.name, events.date, events.location FROM registrations INNER JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = $user_id");
                
                if ($registrations->num_rows > 0) {
                    while ($event = $registrations->fetch_assoc()): 
                ?>
                <tr>
                    <td class="py-2 border-b border-gray-200"><?php echo htmlspecialchars($event['name']); ?></td>
                    <td class="py-2 border-b border-gray-200"><?php echo htmlspecialchars(date('d M Y', strtotime($event['date']))); ?></td>
                    <td class="py-2 border-b border-gray-200"><?php echo htmlspecialchars($event['location']); ?></td>
                </tr>
                <?php endwhile; 
                } else {
                    echo "<tr><td colspan='3' class='py-2 text-center'>No registered events found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<?php require '../includes/footer.php'; ?>

</body>
</html>