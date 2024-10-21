<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);

    // Cek jika email terdaftar di database
    $user = dbQuery("SELECT * FROM users WHERE email = ?", [$email])->fetch_assoc();

    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(16));

        // Simpan token dan waktu kedaluwarsa di database
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

<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(to right, #ff1493, #6a11cb);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #333;
    }

    .forgot-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
    }

    .forgot-form {
        display: flex;
        flex-direction: row;
        background-color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-radius: 15px;
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        animation: fadeIn 0.8s ease-in-out;
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

    .forgot-box {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .forgot-box h2 {
        text-align: center;
        font-size: 26px;
        color: #333;
        margin-bottom: 10px;
    }

    .forgot-box p {
        text-align: center;
        font-size: 16px;
        color: #777;
        margin-bottom: 20px;
    }

    .welcome-box {
        flex: 1;
        background: linear-gradient(135deg, #ff1493 0%, #6a11cb 100%);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 40px;
        text-align: center;
    }

    .welcome-box h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .welcome-box p {
        margin-bottom: 20px;
        font-size: 16px;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 50px;
        text-align: center;
        background-color: #f9f9f9;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-input:focus {
        background-color: #f1f1f1;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        outline: none;
    }

    .btn-submit, .btn-signup {
        padding: 12px 25px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .btn-submit {
        background-color: #ff1493;
        color: white;
        border: 2px solid #ff1493;
        width: 100%;
        text-align: center;
        box-shadow: 0 5px 15px rgba(255, 20, 147, 0.3);
    }

    .btn-submit:hover {
        background-color: white;
        color: #ff1493;
        box-shadow: 0 8px 20px rgba(255, 20, 147, 0.5);
    }

    .btn-signup {
        background-color: white;
        color: #ff1493;
        border: 2px solid white;
        width: auto;
        padding: 10px 40px;
        text-align: center;
    }

    .btn-signup:hover {
        background-color: #ff1493;
        color: white;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
    }

    .error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        text-align: center;
    }

    .footer-text {
        text-align: center;
        color: blue;
        margin-top: 20px;
    }
</style>

<div class="forgot-container">
    <div class="forgot-form">
        <!-- Bagian kiri untuk reset password -->
        <div class="forgot-box">
            <h2>Forgot Password</h2>
            <p>Enter your registered email</p>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="email" id="email" name="email" placeholder="Email" required class="form-input">
                <button type="submit" class="btn-submit">Reset Password</button>
            </form>
        </div>

        <!-- Bagian kanan -->
        <div class="welcome-box">
            <h2>Remember Password?</h2>
            <p>If you remember your password, please login with your personal info</p>
            <a href="login.php" class="btn-signup">Sign In</a>
        </div>
    </div>
</div>

