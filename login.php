<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require 'includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: user/dashboard.php');
                }
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
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
            background-color: #9B7EBD;
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

        .form-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 50px; /* Membuat input menjadi lonjong */
            text-align: center;  /* Center text input */
        }

        .btn-submit,
        .btn-signup {
            background-color: #9B7EBD; /* Warna tombol */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px; /* Membuat tombol lonjong */
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease; /* Tambahkan transisi untuk smooth hover effect */
        }

        .btn-submit:hover {
            background-color: white;
            color: #9B7EBD;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); /* Efek timbul pada hover */
        }

        .btn-signup {
            background-color: white;
            color: #7E60BF;
            border: 2px solid white;
            text-align: center;
            border-radius: 50px; /* Membuat tombol lonjong */
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-signup:hover {
            background-color: #9B7EBD;
            color: white;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
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
</head>
<body>

<div class="login-container">
    <div class="login-form">
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

            <form method="POST">
                <input type="email" name="email" placeholder="Email" required class="form-input">
                <input type="password" name="password" placeholder="Password" required class="form-input">
                <a href="includes/forget_pw.php" class="text-sm text-blue-500 hover:underline">Forgot your password?</a>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
        </div>

        <div class="welcome-box">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us, please login with your personal info</p>
            <a href="register.php" class="btn-signup">Sign Up</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>