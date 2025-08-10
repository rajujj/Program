<?php

class StudentController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: /login?error=unauthorized');
            exit;
        }
    }

    public function dashboard()
    {
        require_once '../templates/dashboards/student.php';
    }
}
