<?php
require_once 'config/db.php';
include 'includes/header.php';

$stmt = $pdo->query("
    SELECT services.*, users.fullname
    FROM services
    JOIN users ON services.user_id = users.id
    ORDER BY services.created_at DESC
    LIMIT 6
");
$services = $stmt->fetchAll();
?>

<div class="hero-section text-center mb-5">
    <h1 class="fw-bold mb-3">Welcome to Campus Service Hub</h1>
    <p class="lead mb-4">
        A professional platform for students to offer skills and services such as tutoring,
        design, repair, printing, and more.
    </p>

    <a href="search.php" class="btn btn-professional me-2">Browse Services</a>

    <?php if (!is_logged_in()): ?>
        <a href="register.php" class="btn btn-professional">Get Started</a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <?php foreach ($services as $service): ?>
        <div class="col-md-4">
            <div class="card card-custom h-100">
                <img src="<?php echo service_image_url($service['image']); ?>" class="service-img" alt="Service Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($service['title']); ?></h5>
                    <p class="card-text text-muted"><?php echo e(substr($service['description'], 0, 100)); ?>...</p>
                    <p class="fw-bold text-primary mb-2">RM <?php echo e($service['price']); ?></p>
                    <small class="text-muted d-block mb-3">By <?php echo e($service['fullname']); ?></small>
                    <a href="view_service.php?id=<?php echo (int)$service['id']; ?>" class="btn btn-professional btn-sm">View Details</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>