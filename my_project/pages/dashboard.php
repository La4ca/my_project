<?php
// pages/dashboard.php
session_start();

// Check if user is logged in
$loggedIn = isset($_SESSION['user']);
$username = $loggedIn ? ($_SESSION['user']['firstname'] ?? $_SESSION['user']['username']) : '';
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #c453aaff, #ffa0b6ff);
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: 
        linear-gradient(rgba(126, 50, 97, 0.5), rgba(126, 50, 97, 0.5)),
        url('../asset/img2.jpg') repeat center center fixed;
        background-size: 500px 500px; /* smaller repeated image */
}

/* Navbar at the top */
nav {
    background: rgba(255,255,255,0.9);
    padding: 15px 30px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

nav a {
    margin-left: 20px;
    text-decoration: none;
    color: #d63384;
    font-weight: bold;
}

nav a:hover {
    color: #ff9bb3;
}

/* Centered content */
.main-content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 2rem;
    text-align: center;
}
</style>
</head>
<body>

    <nav>
        <?php if ($loggedIn): ?>
            Hello, <?php echo htmlspecialchars($username); ?> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </nav>

    <div class="main-content">
        <?php if ($loggedIn): ?>
            Welcome to your Dashboard, <?php echo htmlspecialchars($username); ?>! 🎉
  <?php endif; ?>
</div>

</body>
</html>
