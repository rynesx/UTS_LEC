<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    redirect('../login.php');
}

// Mengambil pengguna
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="path/to/tailwind.css"> <!-- Ganti dengan jalur CSS Anda -->
</head>
<body>
    <h2 class="mt-6">Manage Users</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>