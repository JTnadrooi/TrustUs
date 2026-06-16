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

$filePath = $file['file_path'];
$originalName = $file['original_name'];
$mimeType = $file['mime_type'];
$size = $file['size'];
$uploadTime = $file['upload_time'];

$isPreviewable = isPreviewable($mimeType);
$previewHTML = '';

if ($isPreviewable) {
    // use file_get_contents
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(SITE_NAME); ?> - File</title>
</head>

<body>
    <header>
        <h1><?php echo htmlspecialchars(SITE_NAME); ?></h1>
    </header>

    <main>
        <article>
            <h2>File: <?php echo htmlspecialchars($originalName); ?></h2>
            <dl>
                <dt>Size</dt>
                <dd><?php echo formatSize($size); ?></dd>
                <dt>Uploaded</dt>
                <dd><?php echo htmlspecialchars($uploadTime); ?></dd>
            </dl>
            <p><a href="download.php?token=<?php echo urlencode($token); ?>">Download file</a></p>

            <?php if ($isPreviewable && $previewHTML): ?>
                <section>
                    <h3>Preview</h3>
                    <?php echo $previewHTML; ?>
                </section>
            <?php else: ?>
                <p>Preview not available for this file type.</p>
            <?php endif; ?>

            <p><a href="index.php">Upload another file</a></p>
        </article>
    </main>

    <?php require_once 'footer.php'; ?>
</body>

</html>