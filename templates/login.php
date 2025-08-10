<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f2f5; }
        form { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 1.5rem; }
        div { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 0.75rem; border: none; border-radius: 4px; background-color: #007bff; color: white; font-size: 1rem; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <form action="/login" method="POST">
        <h2>Login</h2>

        <?php if (isset($_GET['error'])): ?>
            <div style="color: red; text-align: center; margin-bottom: 1rem;">
                <?php
                    switch ($_GET['error']) {
                        case 'empty_fields':
                            echo 'Please fill in both username and password.';
                            break;
                        case 'invalid_credentials':
                            echo 'Invalid username or password.';
                            break;
                        default:
                            echo 'An unknown error occurred.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['message']) && $_GET['message'] === 'logged_out'): ?>
            <div style="color: green; text-align: center; margin-bottom: 1rem;">
                You have been successfully logged out.
            </div>
        <?php endif; ?>

        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
