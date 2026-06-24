<?php
require_once 'functions.php';

$token = $_GET['token'] ?? '';
if (empty($token)) {
    die('No file token specified.');
}

$file = DB::getFileByToken($token);
if (!$file) {
    http_response_code(404);
    die('File not found.');
}

// check access: current user must be either the sender or the target
$userId = currentUserId();
if ($userId !== (int)$file['sender_id'] && $userId !== (int)$file['target_user_id']) {
    http_response_code(403);
    die('You are not allowed to view this file.');
}

$relativePath = $file['file_path'];
$absolutePath = PROJECT_ROOT . '/' . $relativePath;

// check if file actually exists
if (!file_exists($absolutePath)) {
    http_response_code(404);
    die('File not found on server.');
}

if (!fileHashIsValid($absolutePath, $file['file_hash'] ?? null)) {
    http_response_code(409);
    die('File integrity check failed. The file may have been changed or corrupted.');
}

$sender = DB::getUserById((int)$file['sender_id']);
$senderName = $sender ? $sender->username : 'Unknown';

$originalName = $file['original_name'];
$mimeType = $file['mime_type'];
$size = $file['size'];
$uploadTime = $file['upload_time'];

$isPreviewable = isPreviewable($mimeType);
$previewHTML = '';

if ($isPreviewable) {
    if (strpos($mimeType, 'text/plain') === 0) {
        $content = file_get_contents($absolutePath);
        $previewHTML = '<div class="text-preview">' . htmlspecialchars($content) . '</div>';
    } else {
        $src = 'download.php?token=' . urlencode($token) . '&inline=1';
        if (strpos($mimeType, 'image/') === 0) {
            $previewHTML = '<div class="image-preview"><img src="' . $src . '" alt="Image preview"></div>';
        } elseif (strpos($mimeType, 'video/') === 0) {
            $previewHTML = '<div class="video-preview"><video controls><source src="' . $src . '" type="' . $mimeType . '"></video></div>';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            $previewHTML = '<div class="audio-preview"><audio controls><source src="' . $src . '" type="' . $mimeType . '"></audio></div>';
        }
    }
}

// get file icon based on mime type
// alr I know what it looks like but the emojis fit here
function getFileIcon(string $mimeType)
{
    if (strpos($mimeType, 'image/') === 0) return '🖼️';
    if (strpos($mimeType, 'video/') === 0) return '🎬';
    if (strpos($mimeType, 'audio/') === 0) return '🎵';
    if (strpos($mimeType, 'text/') === 0) return '📄';
    if (strpos($mimeType, 'application/pdf') === 0) return '📕';
    if (strpos($mimeType, 'application/zip') === 0 || strpos($mimeType, 'application/x-rar') === 0) return '📦';
    if (strpos($mimeType, 'application/msword') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0) return '📘';
    if (strpos($mimeType, 'application/vnd.ms-excel') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0) return '📊';
    if (strpos($mimeType, 'application/vnd.ms-powerpoint') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.presentationml') === 0) return '📙';
    return '📎';
}
?>
<?php require_once 'header.php'; ?>

<article class="file-view">
    <div class="file-header">
        <div class="file-icon">
            <?php echo getFileIcon($mimeType); ?>
        </div>
        <div class="file-title">
            <h2><?php echo htmlspecialchars($originalName); ?></h2>
            <span class="file-badge"><?php echo htmlspecialchars($mimeType); ?></span>
        </div>
    </div>

    <div class="file-details">
        <div class="detail-group">
            <div class="detail-item">
                <span class="detail-label">Size</span>
                <span class="detail-value"><?php echo formatSize($size); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Uploaded</span>
                <span class="detail-value"><?php echo htmlspecialchars($uploadTime); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Sent by</span>
                <span class="detail-value"><?php echo htmlspecialchars($senderName); ?></span>
            </div>
        </div>
    </div>

    <div class="file-actions">
        <a href="download.php?token=<?php echo urlencode($token); ?>" class="btn btn-primary">
            Download file
        </a>
        <a href="index.php" class="btn btn-secondary">
            Upload another file
        </a>
    </div>

    <?php if ($isPreviewable && $previewHTML): ?>
        <section class="preview-section">
            <h3>Preview</h3>
            <div class="preview-container">
                <?php echo $previewHTML; ?>
            </div>
        </section>
    <?php else: ?>
        <div class="preview-unavailable">
            <p>Preview not available for this file type.</p>
        </div>
    <?php endif; ?>
</article>

<?php require_once 'footer.php'; ?>