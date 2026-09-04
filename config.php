<?php
// config.php - Configuration with multiple users
session_start();

// Hardcoded user credentials (username => password)
define('USERS', [
    'alice' => 'alice123',
    'bob'   => 'bob456'
]);

define('BASE_UPLOAD_DIR', 'uploads/');

// Create base upload directory if it doesn't exist
if (!is_dir(BASE_UPLOAD_DIR)) {
    mkdir(BASE_UPLOAD_DIR, 0755, true);
}

// Get current user's upload directory
function getUserUploadDir($username) {
    $dir = BASE_UPLOAD_DIR . $username . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

// Simple authentication check
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Get current logged-in username
function getCurrentUser() {
    return $_SESSION['username'] ?? null;
}

// Redirect to login if not authenticated
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}
?>