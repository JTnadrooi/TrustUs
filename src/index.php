<?php
require_once 'functions.php';
doLoginCheck();

// get all users for dropdown (excluding current user? optional)
$users = DB::getAllUsers();
$currentId = currentUserId();
?>

<?php require_once 'header.php'; ?>
<section>
    <p>Upload a file (max <?php echo formatSize(MAX_FILE_SIZE); ?>)</p>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <p>
            <input type="file" name="file" required>
        </p>
        <p>
            <label for="target_user">Send to:</label>
            <select name="target_user" id="target_user" required>
                <option value="">-- select recipient --</option>
                <?php foreach ($users as $user): ?>
                    <?php if ($user['id'] === $currentId) continue; // optional: skip self 
                    ?>
                    <option value="<?php echo $user['id']; ?>">
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button type="submit">Upload</button>
        </p>
    </form>
</section>
<?php require_once 'footer.php'; ?>