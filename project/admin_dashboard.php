<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Cek apakah user adalah admin
if (!isAdmin()) {
    redirect('index.php');
}

// Ambil data event
$events = dbQuery("SELECT * FROM events");

// Ambil data pendaftar per event
$registrants = dbQuery("SELECT event_id, COUNT(*) as total FROM registrations GROUP BY event_id");

?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <h2>Event Tersedia</h2>
            <ul>
                <?php foreach ($events as $event) : ?>
                    <li><?= $event['name'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h2>Jumlah Pendaftar per Event</h2>
            <ul>
                <?php foreach ($registrants as $registrant) : ?>
                    <li><?= $registrant['event_id'] ?>: <?= $registrant['total'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="event_management.php" class="btn btn-primary">Event Management</a>
            <a href="user_registration.php" class="btn btn-primary">User Registration</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>