<?php

class HodController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hod') {
            header('Location: /login?error=unauthorized');
            exit;
        }
    }

    public function dashboard()
    {
        require_once '../templates/dashboards/hod.php';
    }
}
