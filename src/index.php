<?php
require_once 'functions.php';
doLoginCheck();

// get all users for dropdown (excluding current user? optional)
$users = DB::getAllUsers();
$currentId = currentUserId();
?>

<?php require_once 'header.php'; ?>
<section>
    <p style="text-align: center; font-size: 1.1rem;">
        Upload a file <span style="color: var(--pink-500);"></span>
        <span style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
            max <?php echo formatSize(MAX_FILE_SIZE); ?>
        </span>
    </p>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <p>
            <input type="file" name="file" required>
        </p>
        <p>
            <label for="target_user">Send to:</label>
            <select name="target_user" id="target_user" required>
                <option value="">— select recipient —</option>
                <?php foreach ($users as $user): ?>
                    <?php if ($user['id'] === $currentId) continue; ?>
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