<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("
    SELECT services.*, users.fullname, users.email
    FROM services
    JOIN users ON services.user_id = users.id
    WHERE services.id = ?
");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    set_flash('danger', 'Service not found.');
    redirect('index.php');
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom overflow-hidden">
            <img src="<?php echo service_image_url($service['image']); ?>" class="service-img" alt="Service Image">
            <div class="card-body p-4">
                <h2><?php echo e($service['title']); ?></h2>
                <p class="text-muted">Posted by <?php echo e($service['fullname']); ?> (<?php echo e($service['email']); ?>)</p>
                <p><?php echo e($service['description']); ?></p>
                <h4 class="text-primary">RM <?php echo e($service['price']); ?></h4>
                <a href="<?php echo BASE_URL; ?>/search.php" class="btn btn-professional mt-3">Back to Search</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>