<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$search = "%$q%";

$sql = "
    SELECT services.*, users.fullname
    FROM services
    JOIN users ON services.user_id = users.id
    WHERE services.title LIKE ?
       OR services.description LIKE ?
       OR users.fullname LIKE ?
    ORDER BY services.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$search, $search, $search]);
$services = $stmt->fetchAll();

if (!$services) {
    echo '<div class="alert alert-warning">No services found.</div>';
    exit;
}

echo '<div class="row g-4">';

foreach ($services as $service) {
    echo '<div class="col-md-4">';
    echo '<div class="card card-custom h-100">';
    echo '<img src="' . e(service_image_url($service['image'])) . '" class="service-img" alt="Service Image">';
    echo '<div class="card-body">';
    echo '<h5>' . e($service['title']) . '</h5>';
    echo '<p>' . e(substr($service['description'], 0, 90)) . '...</p>';
    echo '<p class="fw-bold text-primary">RM ' . e($service['price']) . '</p>';
    echo '<small class="text-muted d-block mb-2">By ' . e($service['fullname']) . '</small>';
    echo '<a href="' . BASE_URL . '/view_service.php?id=' . (int)$service['id'] . '" class="btn btn-professional btn-sm">View Details</a>';
    echo '</div></div></div>';
}

echo '</div>';
?>