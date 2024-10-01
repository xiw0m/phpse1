<?php
session_start();
$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$storedPassword = isset($_SESSION['password']) ? $_SESSION['password'] : null;
$message = '';

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if there is already a logged-in user
    if ($loggedInUser && $loggedInUser != $username) {
        $message = "$loggedInUser is already logged in. Wait for them to log out first.";
    } else {
        // Store login details in session
        $_SESSION['username'] = $username;
        $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $message = "User logged in: $username";
        $loggedInUser = $username;
        $storedPassword = $_SESSION['password'];
    }
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    $loggedInUser = null;
    $storedPassword = null;
    $message = "User logged out.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($loggedInUser ?? ''); ?>" <?php echo $loggedInUser ? 'disabled' : ''; ?>><br><br>
        
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" value="<?php echo $loggedInUser ? '******' : ''; ?>" <?php echo $loggedInUser ? 'disabled' : ''; ?>><br><br>
        
        <button type="submit" name="login" <?php echo $loggedInUser ? 'disabled' : ''; ?>>Login</button>
        <button type="submit" name="logout" <?php echo $loggedInUser ? '' : 'disabled'; ?>>Logout</button>
    </form>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    
    <?php if ($loggedInUser): ?>
        <p>User logged in: <?php echo htmlspecialchars($loggedInUser); ?></p>
        <p>Password: <?php echo htmlspecialchars($storedPassword); ?></p>
    <?php endif; ?>
</body>
</html>
