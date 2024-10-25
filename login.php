<?php
session_start();
require_once 'includes/db.php'; 
require_once 'includes/functions.php'; 


$errors = []; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
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
        } else {
            $errors[] = "Error executing query.";
        }
    }
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                header('Location: login.php');
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        
        .login-container {
            background: linear-gradient(to bottom, #4A148C , pink);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
        }

        .card {
            width: 900px;
            height: 500px;
            perspective: 1000px;
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .card.flipped .card-inner {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
        }

        .card-front {
            display: flex;
            flex-direction: row;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px; 
        }

        .card-back {
            display: flex;
            flex-direction: row; 
            background-color: #f9f9f9;
            transform: rotateY(180deg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px; 
        }

        .login-box, .register-box {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-box {
            flex: 1;
            background-color: #7E60BF;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            border-radius: 15px; 
        }

        .form-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 50px;
        }

        .btn-submit {
            background-color: #7E60BF; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 50px; 
            cursor: pointer; 
            transition: all 0.3s ease; 
        }

        .btn-submit:hover {
            background-color: white; 
            color: #7E60BF; 
            border: 2px solid #7E60BF; 
        }

        .btn-signup, .btn-login {
            background-color: white; 
            color: #7E60BF;
            border-radius: 50px; 
            padding: 10px 20px;
            border: 2px solid transparent; 
            cursor: pointer;
        }

        .btn-signup:hover, .btn-login:hover {
            background-color: #7E60BF; 
            color: white; 
            border: 2px solid white; 
        }

        .forgot-password {
            text-align: left;
            margin-bottom: 20px;
            color: #7E60BF;
            cursor: pointer;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .card-back .welcome-box {
            order: 2; 
        }

        .card-back .register-box {
            order: 1; 
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card">
        <div class="card-inner">
            
            <div class="card-front">
                <div class="login-box">
                    <h2>Welcome Back!</h2>
                    <p>Please log in to continue.</p>
                    <form action="login.php" method="POST"> 
                        <input type="email" name="email" placeholder="Email" class="form-input" required>
                        <input type="password" name="password" placeholder="Password" class="form-input" required>
                        <p class="forgot-password" onclick="location.href='includes/forget_pw.php'">Forgot your password?</p>
                        <button type="submit" name="login" class="btn-submit">Sign In</button> 
                    </form>
                    <?php if (!empty($errors)): ?> 
                        <div class="error-messages">
                            <?php foreach ($errors as $error): ?>
                                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="welcome-box">
                    <h2>New Here?</h2>
                    <p>Create an account to get started!</p>
                    <button class="btn-signup" onclick="flipCard()">Sign Up</button>
                </div>
            </div>

            
            <div class="card-back">
                <div class="register-box">
                    <h2>Create an Account</h2>
                    <p>Fill in the form below to register.</p>
                    <form action="login.php" method="POST"> 
                        <input type="text" name="name" placeholder="Username" class="form-input" required>
                        <input type="email" name="email" placeholder="Email" class="form-input" required>
                        <input type="password" name="password" placeholder="Password" class="form-input" required>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-input" required>
                        <button type="submit" name="register" class="btn-submit">Register</button> 
                    </form>
                    <?php if (!empty($errors)): ?> 
                        <div class="error-messages">
                            <?php foreach ($errors as $error): ?>
                                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="welcome-box">
                    <h2>Already Have an Account?</h2>
                    <p>Log in to your account!</p>
                    <button class="btn-signup" onclick="flipCard()">Sign In</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function flipCard() {
        const card = document.querySelector('.card');
        card.classList.toggle('flipped');
    }
</script>

</body>
</html>
