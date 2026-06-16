<?php

declare(strict_types=1);

require_once 'functions.php';

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('Upload error: ' . $file['error']);
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        die('File too large. Maximum size is ' . formatSize(MAX_FILE_SIZE));
    }

    $originalName = $file['name'];
    $mimeType = $file['type'] ?: getMimeType($file['tmp_name']);
    $size = $file['size'];

    $token = generateToken();
    $filePath = UPLOAD_DIR . $token . '.bin';

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        die('Failed to move uploaded file.');
    }

    DB::insertFile($token, $originalName, $mimeType, $size, $filePath);

    header('Location: view.php?token=' . urlencode($token));
    exit;
} else {
    header('Location: index.php');
    exit;
}
