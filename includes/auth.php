<?php
require_once __DIR__ . '\functions.php';

function authorize_service_owner($serviceUserId) {
    if (!isset($_SESSION['user'])) {
        set_flash('danger', 'Unauthorized access.');
        redirect('login.php');
    }

    if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['id'] != $serviceUserId) {
        set_flash('danger', 'You are not allowed to access this service.');
        redirect('dashboard.php');
    }
}
?>