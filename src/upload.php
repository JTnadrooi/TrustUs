<?php
// check if HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    die('Secure HTTPS connection required.');
}
require_once 'functions.php';


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

    DB::insertFile($token, $originalName, $mimeType, $size, $relativePath, $fileHash);

    header('Location: view.php?token=' . urlencode($token));
    exit;
} else {
    header('Location: index.php');
    exit;
}
