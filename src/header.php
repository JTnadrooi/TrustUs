<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js" defer></script>
</head>

<body>
    <header>
        <h1><?php echo htmlspecialchars(SITE_NAME); ?></h1>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">
            With <i style="color: var(--primary-500);">all</i> your data.
        </p>
        <nav>
            <div class="theme-selector">
                <label for="themeSelect">
                    <span class="theme-dot <?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'green' ? 'green' : 'pink'; ?>"></span>
                </label>
                <select id="themeSelect" onchange="changeTheme(this.value)">
                    <option value="pink" <?php echo (!isset($_COOKIE['theme']) || $_COOKIE['theme'] === 'pink') ? 'selected' : ''; ?>>Pink</option>
                    <option value="green" <?php echo (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'green') ? 'selected' : ''; ?>>Green</option>
                </select>
            </div>
            <?php if (isLoggedIn()): ?>
                <button type="button" onclick="location.href='logout.php'">
                    Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)
                </button>
            <?php endif; ?>
        </nav>
        <hr>
    </header>

    <main>