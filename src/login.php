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
            //update log
            writeLog(
                'LOGIN_SUCCESS',
                "User logged in: $username");

            // redirect to main page after successful login
            header('Location: index.php');
            exit;
        } else {
            // log update
            writeLog(
            'LOGIN_FAIL',
            "Failed login attempt for username: $username");

            $error = 'Invalid username or password.';
        }
    }
}

include 'header.php';
?>
<h2>Login</h2>
<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="post">
    <label>Username: <input type="text" name="username" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
    <button type="button" onclick="location.href='register.php'">
        Register
    </button>
</form>
<?php include 'footer.php'; ?>