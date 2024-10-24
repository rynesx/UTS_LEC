<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$user = dbQuery("SELECT * FROM users WHERE id = ?", [$user_id])->fetch_assoc();

$errors = [];
$success_message = '';
$user_profile_picture = isset($user['profile_picture']) ? $user['profile_picture'] : '../uploads/default.png';
$user_name = isset($user['name']) ? $user['name'] : '';
$user_email = isset($user['email']) ? $user['email'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $profile_picture = '';

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Set target directory for profile pictures
        $target_dir = "../uploads/profile_pictures/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $profile_picture = $target_dir . uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);

        if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            $errors[] = "Failed to upload image.";
        } else {
            // Verify that the file was successfully uploaded
            if (!file_exists($profile_picture)) {
                $errors[] = "Image upload failed or file does not exist.";
            }
        }
    }

    // Validate name and email
    if (empty($name) || empty($email)) {
        $errors[] = "Name and email are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If no errors, proceed to update the database
    if (empty($errors)) {
        $update_fields = ["name = ?", "email = ?"];
        $update_values = [$name, $email];

        if (!empty($profile_picture)) {
            $update_fields[] = "profile_picture = ?";
            $update_values[] = $profile_picture;
        }

        $update_values[] = $user_id;

        $query = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $result = dbQuery($query, $update_values);

        if ($result) {
            // Update session and user data
            $_SESSION['user_name'] = $name;
            $success_message = "Profile updated successfully.";
            $user['name'] = $name;
            $user['email'] = $email;
            $user_profile_picture = !empty($profile_picture) ? $profile_picture : $user_profile_picture;
            $errors = [];
        } else {
            // Failed to update profile, redirect to home
            header("Location: ../includes/about_us.php");
            exit(); // Stop further script execution
        }
    } else {
        // Validation errors, redirect to home
        header("Location: ../includes/about_us.php");
        exit(); // Stop further script execution
    }
}
?>

<!-- HTML Form -->
<div class="flex justify-center mb-6">
    <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="rounded-full w-32 h-32 mb-4">
</div>

<?php if (!empty($errors)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" class="max-w-md mx-auto">
    <div class="mb-4">
        <label for="profile_picture" class="block mb-2">Profile Picture (optional)</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="name" class="block mb-2">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="email" class="block mb-2">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required class="w-full px-3 py-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button> 
</form>

<form action="../includes/forget_pw.php" method="GET" class="max-w-md mx-auto mt-4">
    <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Reset Password</button>
</form>

<?php require_once '../includes/footer.php'; ?>
