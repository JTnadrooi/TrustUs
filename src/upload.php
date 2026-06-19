<?php
require_once 'functions.php';

checkIfHttps();

// create upload directory if missing
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('Upload error: ' . $file['error']);
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        die('File too large. Maximum size is ' . formatSize(MAX_FILE_SIZE));
    }

    // get target user from form
    $targetUserId = (int)($_POST['target_user'] ?? 0);
    if ($targetUserId <= 0) {
        die('Please select a recipient.');
    }
    // verify that target user exists
    $targetUser = DB::getUserById($targetUserId);
    if (!$targetUser) {
        die('Selected recipient does not exist.');
    }

    $originalName = $file['name'];
    $mimeType = $file['type'] ?: getMimeType($file['tmp_name']);
    $size = $file['size'];

    $token = generateToken();
    $relativePath = 'storage/uploads/' . $token . '.bin';
    $absolutePath = PROJECT_ROOT . '/' . $relativePath;

    if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
        die('Failed to move uploaded file.');
    }

    $fileHash = fileHash($absolutePath);

    // insert with sender (current user) and target
    DB::insertFile(
        $token,
        $originalName,
        $mimeType,
        $size,
        $relativePath,
        $fileHash,
        currentUserId(),   // sender
        $targetUserId      // recipient
    );
    // update for the log
    writeLog(
'UPLOAD',
"File uploaded: $originalName (token: $token) sent to user $targetUserId"
);


    header('Location: view.php?token=' . urlencode($token));
    exit;
} else {
    header('Location: index.php');
    exit;
}
