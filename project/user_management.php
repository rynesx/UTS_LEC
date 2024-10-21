<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

redirectIfNotAdmin();

$users = dbQuery("SELECT * FROM users ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $user_id = intval($_POST['user_id']);
    $result = dbQuery("DELETE FROM users WHERE id = ?", [$user_id]);
    if ($result) {
        $success_message = "User deleted successfully.";
        $users = dbQuery("SELECT * FROM users ORDER BY name ASC");
    } else {
        $errors[] = "Failed to delete user.";
    }
}
?>

<h2 class="text-3xl font-bold mb-6">User Management</h2>

<?php if (!empty($errors)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<table class="w-full border-collapse border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">Name</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Role</th>
            <th class="border p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td class="border p-2"><?php echo htmlspecialchars($user['name']); ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($user['role']); ?></td>
                <td class="border p-2">
                    <form method="POST" action="" class="inline-block">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>