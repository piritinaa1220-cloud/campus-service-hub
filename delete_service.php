<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    set_flash('danger', 'Service not found.');
    redirect('dashboard.php');
}

if (!is_admin() && $service['user_id'] != $_SESSION['user']['id']) {
    set_flash('danger', 'Unauthorized action.');
    redirect('dashboard.php');
}

$delete = $pdo->prepare("DELETE FROM services WHERE id = ?");
$delete->execute([$id]);

set_flash('success', 'Service deleted successfully.');
redirect('dashboard.php');