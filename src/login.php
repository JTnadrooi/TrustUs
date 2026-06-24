<?php
require_once 'functions.php';

$error = '';

// process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // check that both fields are filled
    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } else {
        // fetch user from database by username
        $user = DB::getUser($username);

        // verify password and set session variables if valid
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            // redirect to main page after successful login
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

include 'header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Sign in to access your files</p>
        </div>

        <?php if ($error): ?>
            <div class="auth-error">
                <span class="error-icon">⚠️</span>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Sign In
                </button>
                <button type="button" class="btn btn-secondary" onclick="location.href='register.php'">
                    Create Account
                </button>
            </div>
        </form>

        <div class="auth-footer">
            <p>New here? <a href="register.php">Create an account</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>