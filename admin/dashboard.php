<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('../login.php');
}

$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    redirect('../user/dashboard.php');
}

$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalEvents = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$totalRegistrations = $conn->query("SELECT COUNT(*) as count FROM registrations")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Font Awesome untuk icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
   body{
            background: linear-gradient(to bottom, #4A148C , pink);
        }
</style>
<body class="bg-grey-100">

<?php require '../includes/header.php'; ?>

<div class="flex min-h-screen">
  
    <div class="bg-gray-800 text-white w-64 py-6 flex-shrink-0">
        <div class="px-6">
            <h2 class="text-lg font-semibold">Admin Panel</h2>
            <p class="text-sm text-gray-400">Welcome back!</p>
        </div>
        <nav class="mt-6">
            <div class="px-6 py-3 hover:bg-gray-700 cursor-pointer">
                <a href="dashboard.php" class="flex items-center">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
            </div>
            <div class="px-6 py-3 hover:bg-gray-700 cursor-pointer">
                <a href="event_management.php" class="flex items-center">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Manage Events
                </a>
            </div>
            <div class="px-6 py-3 hover:bg-gray-700 cursor-pointer">
                <a href="user_management.php" class="flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Manage Users
                </a>
            </div>
            <div class="px-6 py-3 hover:bg-gray-700 cursor-pointer">
                <a href="export_csv.php" class="flex items-center">
                    <i class="fas fa-file-csv mr-3"></i>
                    Export Registrations
                </a>
            </div>
        </nav>
    </div>

    <div class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Dashboard Overview</h1>
            <p class="text-pink">Welcome to your admin dashboard.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-full">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Users</h3>
                        <p class="text-2xl font-semibold"><?php echo $totalUsers; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-full">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Events</h3>
                        <p class="text-2xl font-semibold"><?php echo $totalEvents; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-full">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Registrations</h3>
                        <p class="text-2xl font-semibold"><?php echo $totalRegistrations; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Event Management</h2>
                <div class="space-y-4">
                    <a href="event_management.php" class="block bg-blue-500 text-white rounded px-4 py-2 text-center hover:bg-blue-600">
                        View All Events
                    </a>
                    <a href="add_event.php" class="block bg-green-500 text-white rounded px-4 py-2 text-center hover:bg-green-600">
                        Add New Event
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">User Management</h2>
                <div class="space-y-4">
                    <a href="user_management.php" class="block bg-purple-500 text-white rounded px-4 py-2 text-center hover:bg-purple-600">
                        Manage Users
                    </a>
                    <a href="view_registration.php" class="block bg-indigo-500 text-white rounded px-4 py-2 text-center hover:bg-indigo-600">
                        View Registrations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>

</body>
</html>