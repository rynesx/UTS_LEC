<?php
require_once '../includes/db.php'; // Pastikan ada koneksi ke database
require_once '../includes/functions.php'; // Memasukkan file dengan fungsi sanitizeInput

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $token = sanitizeInput($_POST['token']);

    // Validasi token
    $current_time = date('Y-m-d H:i:s');
    $reset_token = dbQuery("SELECT * FROM email_reset_tokens WHERE token = ? AND expiration_time > ?", [$token, $current_time])->fetch_assoc();

    if (!$reset_token) {
        $errors[] = "Invalid token or token has expired.";
    }

    if (empty($password) || $password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        dbQuery("UPDATE users SET password = ? WHERE email = ?", [$hashed_password, $reset_token['email']]);

        // Hapus token yang telah digunakan
        dbQuery("DELETE FROM email_reset_tokens WHERE token = ?", [$token]);

        $success_message = "Your password has been reset successfully. You can now log in.";
    }
}
?>

<h2>Reset Password</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <div class="mb-4">
        <label for="password" class="block mb-2">New Password</label>
        <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="confirm_password" class="block mb-2">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Reset Password</button>
</form>

<div class="mt-4">
<a href="javascript:history.back()" class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Back</a>
</div>