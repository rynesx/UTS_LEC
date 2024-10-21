<?php
require_once '../includes/db.php'; // Pastikan Anda memiliki koneksi ke database
require_once '../includes/functions.php'; // Memasukkan file dengan fungsi

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    
    // Cek jika email terdaftar di database
    $user = dbQuery("SELECT * FROM users WHERE email = ?", [$email])->fetch_assoc();
    
    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(16)); // Menghasilkan token acak
        
        // Simpan token dan waktu kedaluwarsa di database atau di tabel terpisah
        $expiration_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        dbQuery("INSERT INTO email_reset_tokens (email, token, expiration_time) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expiration_time = ?", [$email, $token, $expiration_time, $token, $expiration_time]);

        // Redirect ke halaman reset dengan token
        header("Location: reset_pw.php?token=" . $token);
        exit();
    } else {
        $errors[] = "Email not found. Please check again.";
    }
}
?>

<h2>Forget Password</h2>

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
    <div class="mb-4">
        <label for="email" class="block mb-2">Enter your registered Email</label>
        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Reset Password</button>
</form>
