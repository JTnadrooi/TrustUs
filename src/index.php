<?php

require_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(SITE_NAME); ?></title>
</head>

<body>
    <header>
        <h1><?php echo htmlspecialchars(SITE_NAME); ?></h1>
    </header>

    <main>
        <section>
            <p>Upload a file (max <?php echo formatSize(MAX_FILE_SIZE); ?>)</p>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="file" required>
                </p>
                <p>
                    <button type="submit">Upload</button>
                </p>
            </form>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>
</body>

</html>