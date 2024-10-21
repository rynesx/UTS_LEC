<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

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
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $profile_picture = $target_dir . basename($_FILES["profile_picture"]["name"]);
        
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            // Successfully uploaded
        } else {
            $errors[] = "Failed to upload image.";
        }
    }

    if (empty($name) || empty($email)) {
        $errors[] = "Name and email are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!empty($current_password) || !empty($new_password)) {
        if (empty($user_password) || !password_verify($current_password, $user_password)) {
            $errors[] = "Current password is incorrect.";
        }

        if (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters long.";
        }

        if ($new_password !== $confirm_new_password) {
            $errors[] = "New passwords do not match.";
        }
    }

    if (empty($errors)) {
        $update_fields = ["name = ?", "email = ?"];
        $update_values = [$name, $email];

        if (!empty($new_password)) {
            $update_fields[] = "password = ?";
            $update_values[] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        if (isset($profile_picture)) {
            $update_fields[] = "profile_picture = ?";
            $update_values[] = $profile_picture;
        }

        $update_values[] = $user_id;

        $query = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $result = dbQuery($query, $update_values);
        
        if ($result) {
            $_SESSION['user_name'] = $name;
            $success_message = "Profile updated successfully.";
            $user['name'] = $name;
            $user['email'] = $email;
            $user_profile_picture = isset($profile_picture) ? $profile_picture : $user_profile_picture; // Update profil picture
        } else {
            $errors[] = "Failed to update profile.";
        }
    }
}
?>

<div class="text-center mb-6">
    <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="rounded-full w-32 h-32 mb-4">
    <h2 class="text-3xl font-bold">Your Profile</h2>
</div>

<?php if (!empty($errors)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Please fix the following errors:</strong>
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
    <div class="mb-4">
        <label for="current_password" class="block mb-2">Current Password (leave blank to keep current password)</label>
        <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="new_password" class="block mb-2">New Password (leave blank to keep current password)</label>
        <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border rounded">
    </div>
    <div class="mb-4">
        <label for="confirm_new_password" class="block mb-2">Confirm New Password</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" class="w-full px-3 py-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button>
</form>

<?php require_once '../includes/footer.php'; ?>
