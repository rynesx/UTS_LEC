<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $result = dbQuery("SELECT id FROM users WHERE email = ?", [$email]);

        if ($result->num_rows > 0) {
            $errors[] = "Email already exists.";
        } else {
            $user_id = dbInsert("INSERT INTO users (name, email, password) VALUES (?, ?, ?)", [$name, $email, $hashed_password]);

            if ($user_id) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = 'user';
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<style>
    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
    }

    .register-form {
        display: flex;
        flex-direction: row;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }

    .register-box {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .register-box h2 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .register-box p {
        text-align: center;
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    .welcome-box {
        flex: 1;
        background-color: #ff4b2b;
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

    .btn-submit, .btn-login {
        background-color: #ff4b2b;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 50px; /* Membuat tombol lonjong */
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: all 0.3s ease; /* Tambahkan transisi untuk smooth hover effect */
    }

    /* Hover efek timbul untuk tombol Register */
    .btn-submit:hover {
        background-color: #ff6b4b;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); /* Efek timbul pada hover */
    }

    .btn-login {
        background-color: white;
        color: #ff4b2b;
        border: 2px solid #ff4b2b;
        width: auto;
        padding: 10px 40px;
        text-align: center;
    }

    /* Hover efek timbul untuk tombol Sign In */
    .btn-login:hover {
        background-color: #ff4b2b;
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

<div class="register-container">
    <div class="register-form">
        <!-- Bagian register kiri -->
        <div class="register-box">
            <h2>Create Account</h2>
            <p>or use your email for registration</p>

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
                <input type="text" id="name" name="name" placeholder="Name" required class="form-input">
                <input type="email" id="email" name="email" placeholder="Email" required class="form-input">
                <input type="password" id="password" name="password" placeholder="Password" required class="form-input">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required class="form-input">
                <button type="submit" class="btn-submit">Register</button>
            </form>
        </div>

        <!-- Bagian welcome kanan -->
        <div class="welcome-box">
            <h2>Welcome Kontol</h2>
            <p>To keep connected with us, please login with your personal info</p>
            <a href="login.php" class="btn-login">Sign In</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
