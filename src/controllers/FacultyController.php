<?php

class FacultyController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
            header('Location: /login?error=unauthorized');
            exit;
        }
    }

    public function dashboard()
    {
        require_once '../templates/dashboards/faculty.php';
    }
}
