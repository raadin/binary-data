<?php
// login.php - Login page with multiple user support
require_once 'config.php';

// If already logged in, go to index
if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Check if user exists and password matches
    if (isset(USERS[$username]) && USERS[$username] === $password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Photo Upload</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%; }
        .error { color: red; }
        .hint { margin-top: 20px; font-size: 0.9em; color: #666; background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .hint table { width: 100%; border-collapse: collapse; }
        .hint td { padding: 5px 10px; }
        .hint tr { border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required placeholder="Enter username">
        <label>Password:</label>
        <input type="password" name="password" required placeholder="Enter password">
        <button type="submit">Login</button>
    </form>
    
    <div class="hint">
        <p><strong>Available Users:</strong></p>
        <table>
            <tr><td><strong>Username</strong></td><td><strong>Password</strong></td></tr>
            <?php foreach (USERS as $user => $pass): ?>
            <tr><td><?= htmlspecialchars($user) ?></td><td><?= htmlspecialchars($pass) ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>