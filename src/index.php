<?php

require_once 'functions.php';

?>

<?php require_once 'header.php'; ?>
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
<?php require_once 'footer.php'; ?>