<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if ($title === '' || $description === '' || $price === '') {
        set_flash('danger', 'All fields are required.');
        redirect('add_service.php');
    }

    if (!is_numeric($price) || $price <= 0) {
        set_flash('danger', 'Price must be a valid positive number.');
        redirect('add_service.php');
    }

    $upload = upload_image($_FILES['image']);
    if (!$upload['success']) {
        set_flash('danger', $upload['message']);
        redirect('add_service.php');
    }

    $stmt = $pdo->prepare("
        INSERT INTO services (user_id, title, description, price, image)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user']['id'],
        $title,
        $description,
        $price,
        $upload['filename']
    ]);

    set_flash('success', 'Service added successfully.');
    redirect('dashboard.php');
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <h2 class="mb-4">Add Service</h2>

            <form method="POST" enctype="multipart/form-data" id="serviceForm">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (RM)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png" required>
                </div>

                <button type="submit" class="btn btn-professional">Save Service</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>