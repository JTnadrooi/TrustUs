<?php
// include database configuration and shared functions
require_once 'functions.php';

$error = '';
$success = '';

// process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // validate required fields
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // check if username already exists in database
        $check = DB::getDB()->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->fetch()) {
            $error = 'Username already taken.';
        } else {
            // hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            // insert new user with default viewer role
            $stmt = DB::getDB()->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, 'viewer', CURDATE())");
            if ($stmt->execute([$username, $hashed])) {
                $success = 'Registration successful!<br>You can now <a href="login.php">login here</a>.';
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }
}

include 'header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join us to start sharing files</p>
        </div>

        <?php if ($error): ?>
            <div class="auth-error">
                <span class="error-icon">⚠️</span>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="auth-success">
                <span class="success-icon">✅</span>
                <span class="message-text">
                    <?php echo $success; ?>
                </span>
            </div>
        <?php else: ?>
            <form method="post" class="auth-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" name="username" placeholder="Choose a username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✓</span>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Create Account
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="location.href='login.php'">
                        Back to Login
                    </button>
                </div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>