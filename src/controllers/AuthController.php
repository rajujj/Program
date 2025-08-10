<?php

class AuthController
{
    /**
     * Display the login form.
     * If the user is already logged in, redirect them to their dashboard.
     */
    public function showLoginForm()
    {
        // If user is already logged in, redirect them away from the login page
        if (isset($_SESSION['user_id'])) {
            $role = $_SESSION['role'];
            // Simple redirect logic
            header("Location: /{$role}/dashboard");
            exit;
        }

        // Get error message from URL query string to display on the form
        $error = $_GET['error'] ?? null;
        require_once '../templates/login.php';
    }

    /**
     * Handle the login form submission.
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header('Location: /login?error=empty_fields');
            exit;
        }

        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            // Verify user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to the appropriate dashboard
                header('Location: /' . $user['role'] . '/dashboard');
                exit;
            } else {
                // Invalid credentials
                header('Location: /login?error=invalid_credentials');
                exit;
            }
        } catch (PDOException $e) {
            // In a real app, log this error. For now, a simple error page.
            die('Database error during login: ' . $e->getMessage());
        }
    }

    /**
     * Handle user logout.
     */
    public function logout()
    {
        // Unset all of the session variables.
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        // Redirect to login page
        header('Location: /login?message=logged_out');
        exit;
    }
}
