<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

if (is_admin()) {
    $stmt = $pdo->query("
        SELECT services.*, users.fullname
        FROM services
        JOIN users ON services.user_id = users.id
        ORDER BY services.created_at DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT services.*, users.fullname
        FROM services
        JOIN users ON services.user_id = users.id
        WHERE services.user_id = ?
        ORDER BY services.created_at DESC
    ");
    $stmt->execute([$_SESSION['user']['id']]);
}

$services = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<h2 class="mb-4">Dashboard</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><img src="<?php echo service_image_url($service['image']); ?>" width="80" alt="service image"></td>
                    <td><?php echo e($service['title']); ?></td>
                    <td>RM <?php echo e($service['price']); ?></td>
                    <td><?php echo e($service['fullname']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/edit_service.php?id=<?php echo (int)$service['id']; ?>" class="btn btn-warning-custom btn-sm">Edit</a>
                        <a href="<?php echo BASE_URL; ?>/delete_service.php?id=<?php echo (int)$service['id']; ?>" class="btn btn-danger-custom btn-sm" onclick="return confirm('Delete this service?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>