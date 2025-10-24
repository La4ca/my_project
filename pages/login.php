<?php
// pages/login.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../dao/crudDAO.php';

$errors = [];
$identifier = ''; // username or email

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    // Basic validation
    if ($identifier === '') $errors[] = "Username or email is required.";
    if ($password === '') $errors[] = "Password is required.";

    if (empty($errors)) {
        $dao = new crudDAO($pdo);
        $user = $dao->login($identifier, $password);

        if ($user !== false) {
            // Successful login -> create session
            $_SESSION['user'] = $user; // contains id, firstname, lastname, username, email, created_at

            // Redirect to dashboard immediately
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: 
                linear-gradient(rgba(195, 131, 195, 0.5), rgba(195, 131, 195, 0.5)),
                url('../asset/img.jpg') repeat center center fixed;
                background-size: 500px 500px; 
        }
        .form-container {
            background: rgba(255, 254, 254, 0.85);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 350px;
            width: 80%;
        }
        h1 { text-align: center; color: #d63384; }
        label { display:block; margin-top:10px; font-weight:bold; color: #555;}
        input[type="text"], input[type="password"] {
            width:100%; padding:8px; margin-top:4px; box-sizing:border-box; border-radius:6px; border:1px solid #ccc;
        }
        .errors { background:#ffe6e6; border:1px solid #ffcccc; padding:10px; border-radius:6px; }
        button { width:100%; margin-top:12px; padding:10px; border-radius:6px; border:none; background:#e26ac2ff; font-weight:bold; cursor:pointer; color:white; }
        button:hover { background:#ff9bb3; }
        .small { font-size:0.9rem; margin-top:8px; text-align:center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="identifier">Username or Email</label>
            <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Log In</button>
        </form>

        <div class="small">
            <a href="signup.php" style="color:#d63384; text-decoration:underline;">Don't have an account?</a>
            </div>
    </div>
</body>
</html>
