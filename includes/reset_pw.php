<?php
require_once '../includes/db.php'; // Pastikan ada koneksi ke database
require_once '../includes/functions.php'; // Memasukkan file dengan fungsi sanitizeInput

$errors = [];
$success_message = '';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

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

        // Set success message
        $success_message = "Your password has been reset successfully. Redirecting to login page...";

        // Redirect ke halaman login setelah beberapa detik
        header("refresh:5;url=../login.php");
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: #333;
    }

    .reset-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
        padding: 20px;
    }

    .reset-box {
        background-color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 15px;
        padding: 40px;
        max-width: 400px;
        width: 100%;
        text-align: center;
        animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .reset-box h2 {
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 30px;
        text-align: center;
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }

    .form-input:focus {
        background-color: #f1f1f1;
        outline: none;
    }

    .btn-submit {
        background-color: #6a11cb;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(106, 17, 203, 0.2);
    }

    .btn-submit:hover {
        background-color: #2575fc;
        box-shadow: 0 8px 20px rgba(37, 117, 252, 0.4);
    }

    .error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .footer-text {
        text-align: center;
        color: white;
        margin-top: 20px;
    }
</style>

<div class="reset-container">
    <div class="reset-box">
        <h2>Reset Password</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="password" id="password" name="password" placeholder="New Password" required class="form-input">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required class="form-input">
            <button type="submit" class="btn-submit">Reset Password</button>
        </form>
    </div>
</div>

