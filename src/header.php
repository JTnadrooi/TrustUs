<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars(SITE_NAME); ?></title>
</head>

<body>
    <header>
        <h1><?php echo htmlspecialchars(SITE_NAME); ?></h1>
        With <i>all</i> your data.
        <nav>
            <!-- <a href="index.php">Home</a> | -->
            <?php if (isLoggedIn()): ?>
                <br />
                <button type="button" onclick="location.href='logout.php'">
                    Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)
                </button>
            <?php endif; ?>
        </nav>
        <hr>
    </header>

    <main>