<?php

class AdminController
{
    public function __construct()
    {
        // Protect this controller's methods
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login?error=unauthorized');
            exit;
        }
    }

    public function dashboard()
    {
        // The constructor already handles authentication and role checking.
        // We can now safely render the admin dashboard view.
        require_once '../templates/dashboards/admin.php';
    }
}
