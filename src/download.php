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

$filePath = $file['file_path'];
if (!file_exists($filePath)) {
    http_response_code(404);
    die('File not found on server.');
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
readfile($filePath);
exit;
