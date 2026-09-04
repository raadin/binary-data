<?php
// logout.php - Destroy session and redirect to login
require_once 'config.php';
session_destroy();
header('Location: login.php');
exit;
?>