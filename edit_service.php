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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $imageName = $service['image'];

    if ($title === '' || $description === '' || $price === '') {
        set_flash('danger', 'All fields are required.');
        redirect('edit_service.php?id=' . $id);
    }

    if (!is_numeric($price) || $price <= 0) {
        set_flash('danger', 'Price must be a valid positive number.');
        redirect('edit_service.php?id=' . $id);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload = upload_image($_FILES['image']);
        if (!$upload['success']) {
            set_flash('danger', $upload['message']);
            redirect('edit_service.php?id=' . $id);
        }
        $imageName = $upload['filename'];
    }

    $update = $pdo->prepare("
        UPDATE services
        SET title = ?, description = ?, price = ?, image = ?
        WHERE id = ?
    ");
    $update->execute([$title, $description, $price, $imageName, $id]);

    set_flash('success', 'Service updated successfully.');
    redirect('dashboard.php');
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <h2 class="mb-4">Edit Service</h2>

            <form method="POST" enctype="multipart/form-data" id="serviceForm">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo e($service['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required><?php echo e($service['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (RM)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo e($service['price']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="<?php echo service_image_url($service['image']); ?>" width="140" class="mb-2" alt="Current image">
                    <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-professional">Update Service</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>