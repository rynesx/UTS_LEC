<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        $result = dbQuery("SELECT id, name, password, role FROM users WHERE email = ?", [$email]);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
    }

    .login-form {
        display: flex;
        flex-direction: row;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }

    .login-box {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-box h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .login-box p {
        text-align: center;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    .welcome-box {
        flex: 1;
        background-color: #ff1493;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 40px;
    }

    .welcome-box h2 {
        text-align: center;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .welcome-box p {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 50px; /* Membuat input menjadi lonjong */
        text-align: center;  /* Center text input */
    }

    .btn-submit, .btn-signup {
        background-color: #ff1493;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 50px; /* Membuat tombol lonjong */
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: all 0.3s ease; /* Tambahkan transisi untuk smooth hover effect */
    }

    .btn-submit {
        background-color: #ff1493;
        color: white;
        border: 2px solid #ff1493;
        width: 100%;
        padding: 10px 40px;
        text-align: center;
    }

    .btn-submit:hover {
        background-color: white;
        color: #ff1493;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); /* Efek timbul pada hover */
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
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); /* Efek timbul pada hover */
    }

    .error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
</style>

<div class="login-container">
    <div class="login-form">
        <!-- Bagian login kiri -->
        <div class="login-box">
            <h2>Sign In</h2>
            <p>or use your account</p>

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
                <input type="password" id="password" name="password" placeholder="Password" required class="form-input">
                <a href="forget_pw.php" class="text-sm text-blue-500 hover:underline">Forgot your password?</a>
                <br><br>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
           
        </div>

        <!-- Bagian welcome kanan -->
        <div class="welcome-box">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us, please login with your personal info</p>
            <a href="register.php" class="btn-signup">Sign Up</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>