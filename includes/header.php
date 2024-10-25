<?php if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
} 
require_once 'db.php'; 
require_once 'functions.php'; 


function getUserRole() {
    global $conn;
    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['role'];
        }
    }
    return null;
}
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title><?php echo SITE_NAME; ?></title> 
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="stylesheet" href="/css/styles.css"> 
</head> 
<style>
    @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap');
</style>
<body class="bg-gray-100 m-0 p-0">
    <header class="bg-purple-900 text-white p-4"> <!-- Ubah padding menjadi 0 -->
        <div class="container mx-auto flex justify-between items-center"> 
            <h1 class="text-2xl font-Dancing Script"><?php echo SITE_NAME; ?></h1> 
            <nav> 
                <ul class="flex space-x-4"> 
                    <li><a href="/UTS_LEC/index.php" class="hover:underline">Home</a></li>
                    <li><a href="/UTS_LEC/includes/about_us.php" class="hover:underline">About Us</a></li>
                    <li><a href="/UTS_LEC/admin/view_registration.php" class="hover:underline">Upcoming</a></li>
                    <?php if (isLoggedIn()): 
                        $userRole = getUserRole();
                        $dashboardUrl = ($userRole === 'admin') ? '/UTS_LEC/admin/dashboard.php' : '/UTS_LEC/user/dashboard.php';
                    ?> 
                        <li><a href="<?php echo $dashboardUrl; ?>" class="hover:underline">Dashboard</a></li>
                        <li><a href="/UTS_LEC/user/profile.php" class="hover:underline">Profile</a></li>
                        <li><a href="/UTS_LEC/user/logout.php" class="hover:underline">Logout</a></li>
                    <?php else: ?> 
                        <li><a href="/UTS_LEC/login.php" class="hover:underline">Login</a></li> 
                        <li><a href="/UTS_LEC/login.php" class="hover:underline">Register</a></li> 
                    <?php endif; ?> 
                </ul> 
            </nav> 
        </div> 
    </header> 
    <main class="container mx-auto mt-4">
