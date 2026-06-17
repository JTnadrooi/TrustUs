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
                $success = 'Registration successful. <a href="login.php">Login here</a>';
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }
}

include 'header.php';
?>
<h2>Register</h2>
<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<?php if ($success): ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php else: ?>
    <form method="post">
        <label>Username: <input type="text" name="username" required></label><br><br>
        <label>Password: <input type="password" name="password" required></label><br><br>
        <label>Confirm Password: <input type="password" name="confirm_password" required></label><br><br>
        <button type="submit">Register</button>
        <button type="button" onclick="location.href='login.php'">
            Back to login
        </button>
    </form>
<?php endif; ?>
<?php include 'footer.php'; ?>