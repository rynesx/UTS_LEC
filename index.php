<?php
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Oblivion</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap');
        
        .font-cursive {
            font-family: 'Dancing Script', cursive;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Hero Section -->
    <div class="w-full flex items-center justify-center">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="image/bcg.jpg" alt="Event Play" class="w-full h-full object-cover">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <!-- Content -->
        <div class="relative text-center text-white px-4 w-full">
            <h2 class="font-cursive text-4xl sm:text-5xl md:text-6xl mb-4">
                Welcome to
            </h2>
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold tracking-wider mb-8">
                EventPlay
            </h1>
            <a href="admin/view_registration.php" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-full text-lg font-medium transform transition hover:scale-105 hover:bg-gray-100">
                View Event
            </a>
        </div>
    </div>
</body>
</html>
