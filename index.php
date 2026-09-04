<?php
// index.php - Main page with user-specific photo galleries
require_once 'config.php';
requireAuth();

$currentUser = getCurrentUser();
$userDir = getUserUploadDir($currentUser);

// Handle file upload
$uploadMessage = '';
$uploadSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    $fileName = basename($file['name']);
    $targetPath = $userDir . time() . '_' . $fileName;
    
    // Basic validation: only images
    $imageType = exif_imagetype($file['tmp_name']);
    if ($imageType === false) {
        $uploadMessage = 'Please upload a valid image file.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadMessage = 'Upload error: ' . $file['error'];
    } elseif (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $uploadMessage = 'Photo uploaded successfully!';
        $uploadSuccess = true;
    } else {
        $uploadMessage = 'Failed to move uploaded file.';
    }
}

// Get list of user's uploaded photos
$photos = is_dir($userDir) ? array_diff(scandir($userDir), ['.', '..']) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Photo Upload - <?= htmlspecialchars($currentUser) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 30px auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .user-info { background: #e9ecef; padding: 5px 15px; border-radius: 20px; font-size: 0.9em; }
        .logout { background: #dc3545; color: white; padding: 5px 15px; text-decoration: none; border-radius: 3px; }
        .upload-area { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        input[type="file"] { margin: 10px 0; }
        button { background: #28a745; color: white; padding: 8px 20px; border: none; cursor: pointer; border-radius: 3px; }
        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 20px; }
        .gallery img { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .message { padding: 10px; margin: 10px 0; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .no-photos { color: #666; font-style: italic; }
        .stats { font-size: 0.9em; color: #666; margin-top: 10px; }
        .switch-user { margin-top: 15px; padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>📸 Photo Upload</h1>
            <span class="user-info">👤 Logged in as: <strong><?= htmlspecialchars($currentUser) ?></strong></span>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    
    <div class="upload-area">
        <h3>Upload a Photo</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="photo" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
        <?php if ($uploadMessage): ?>
            <div class="message <?= $uploadSuccess ? 'success' : 'error' ?>">
                <?= htmlspecialchars($uploadMessage) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <h3>Your Photos</h3>
    <?php if (empty($photos)): ?>
        <p class="no-photos">No photos uploaded yet, <?= htmlspecialchars($currentUser) ?>.</p>
    <?php else: ?>
        <div class="gallery">
            <?php foreach ($photos as $photo): ?>
                <?php $photoPath = $userDir . $photo; ?>
                <a href="<?= htmlspecialchars($photoPath) ?>" target="_blank">
                    <img src="<?= htmlspecialchars($photoPath) ?>" alt="<?= htmlspecialchars($photo) ?>">
                </a>
            <?php endforeach; ?>
        </div>
        <div class="stats">📊 Total: <?= count($photos) ?> photo(s)</div>
    <?php endif; ?>
    
    <div class="switch-user">
        💡 <strong>Tip:</strong> Log out to switch to another user. Each user has their own private photo gallery.
    </div>
</body>
</html>