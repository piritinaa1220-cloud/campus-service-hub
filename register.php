<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = ($_POST['role'] === 'admin') ? 'admin' : 'user';

    if ($fullname === '' || $email === '' || $password === '') {
        set_flash('danger', 'All fields are required.');
        redirect('register.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Invalid email format.');
        redirect('register.php');
    }

    if (strlen($password) < 6) {
        set_flash('danger', 'Password must be at least 6 characters.');
        redirect('register.php');
    }

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        set_flash('danger', 'Email already exists.');
        redirect('register.php');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (fullname, email, password, role)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$fullname, $email, $hashedPassword, $role]);

    set_flash('success', 'Registration successful. Please login.');
    redirect('login.php');
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-custom p-4">
            <h2 class="mb-3 text-center">Register</h2>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-professional w-100">Register</button>

                <p class="text-center mt-3 mb-0">
                    Already have an account?
                    <a href="login.php">Login Here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>