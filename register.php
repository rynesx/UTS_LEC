<?php
require_once 'includes/db.php';       // Connect to the database
require_once 'includes/functions.php'; // Include functions
require 'includes/header.php';    // Include header

$errors = []; // Array to hold errors

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and escape user inputs
    $name = htmlspecialchars(sanitize(trim($_POST['name'])), ENT_QUOTES, 'UTF-8'); // Escape output
    $email = htmlspecialchars(sanitize(trim($_POST['email'])), ENT_QUOTES, 'UTF-8'); // Escape output
    $password = trim($_POST['password']); // Store password directly without escaping
    $confirm_password = trim($_POST['confirm_password']); // Store password directly

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required."; // Ensure all fields are filled
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match."; // Error for non-matching passwords
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email already registered."; // If email is already registered
        } else {
            // Hash the password and save to the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = isset($_POST['is_admin']) ? 'admin' : 'user'; // Set role based on checkbox
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                header('Location: login.php'); // Redirect to login page after successful registration
                exit();
            } else {
                $errors[] = "Registration failed. Please try again."; // Error during registration
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
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Ganti dengan jalur CSS Anda -->
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
            background-color: #7E60BF; /* Warna latar belakang kanan */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
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
            background-color: #7E60BF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px; /* Membuat tombol lonjong */
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease; /* Tambahkan transisi untuk smooth hover effect */
        }

        .btn-submit:hover {
        background-color: white;
        color: #9B7EBD;
        border: 2px solid #9B7EBD; /* Menambahkan border saat hover */;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3); /* Efek timbul pada hover */
    }

        .btn-signup {
            background-color: white;
            color: #7E60BF;
            border: 2px solid white;
            margin: 30px;
            width: auto;
            padding: 10px 40px;
            text-align: center;
        }

        .btn-signup:hover {
            background-color: #7E60BF;
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
</head>

<body>

<!-- Header -->
<?php require_once 'includes/header.php'; ?>

<div class="login-container">
    <div class="login-form">
        <div class="login-box">
            <h2>Register</h2>

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
                <input type="text" id="name" name="name" placeholder="Username" required class="form-input">
                <input type="email" id="email" name="email" placeholder="Email" required class="form-input">
                <input type="password" id="password" name="password" placeholder="Password" required class="form-input">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required class="form-input">
                <br>
                <button type="submit" class="btn-submit">Join now</button>
            </form>
        </div>

        <div class="welcome-box">
            <h2>Create an account</h2>
            <p>To keep connected with us, please register with your personal info</p>
            <a href="login.php" class="btn-signup">Sign In</a>
        </div>
    </div>
</div>

<!-- Footer -->
<?php require_once 'includes/footer.php'; ?>

</body>
</html>