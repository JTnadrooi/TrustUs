<?php
require_once 'functions.php';

$token = $_GET['token'] ?? '';
if (empty($token)) {
    http_response_code(400);
    die('Missing token.');
}

$file = DB::getFileByToken($token);
if (!$file) {
    http_response_code(404);
    die('File not found.');
}

// allow sender and target
$userId = currentUserId();
if ($userId !== (int)$file['sender_id'] && $userId !== (int)$file['target_user_id']) {
    http_response_code(403);
    die('You are not allowed to download this file.');
}

$relativePath = $file['file_path'];
$absolutePath = PROJECT_ROOT . '/' . $relativePath;

if (!file_exists($absolutePath)) {
    writeLog(
    'DOWNLOAD',
    "File downloaded: " . $file['original_name'] . " (token: $token)"
);
    http_response_code(404);
    die('File not found on server.');
}

if (!fileHashIsValid($absolutePath, $file['file_hash'] ?? null)) {
    http_response_code(409);
    die('File integrity check failed. The file may have been changed or corrupted.');
}

$originalName = $file['original_name'];
$mimeType = $file['mime_type'];
$size = $file['size'];

$inline = isset($_GET['inline']);

header('Content-Type: ' . $mimeType);
header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . addslashes($originalName) . '"');
header('Content-Length: ' . $size);
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

if (ob_get_level()) {
    ob_end_clean();
}

readfile($absolutePath);
exit;
