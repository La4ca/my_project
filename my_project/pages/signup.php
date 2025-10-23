<?php
// pages/signup.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../dao/crudDAO.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs
    $lastname        = trim($_POST['lastname'] ?? '');
    $firstname       = trim($_POST['firstname'] ?? '');
    $username        = trim($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm_pass    = $_POST['confirm_password'] ?? '';
    $email           = trim($_POST['email'] ?? '');

    // Basic validation
    if ($lastname === '') $errors[] = "Last name is required.";
    if ($firstname === '') $errors[] = "First name is required.";

    if ($username === '') {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $errors[] = "Username must be 3-20 characters; letters, numbers, underscore only.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_pass) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // If validation passes, create user
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $dao = new crudDAO($pdo);
        $result = $dao->create($lastname, $firstname, $username, $password_hash, $email);

        if ($result === true) {
            $success = true;
        } else {
            if (strpos($result, 'SQLSTATE[23000]') !== false) {
                if (strpos($result, 'username') !== false) {
                    $errors[] = "Username already taken.";
                } elseif (strpos($result, 'email') !== false) {
                    $errors[] = "Email already registered.";
                } else {
                    $errors[] = "Duplicate entry. Please check your input.";
                }
            } else {
                $errors[] = "Database error: " . $result;
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign Up</title>
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
            linear-gradient(rgba(195, 131, 195, 0.5), rgba(40, 50, 40, 0.5)),
            url('../asset/img.jpg') repeat center center fixed;
        background-size: 500px 500px; 
    }

    .form-container {
        background: rgba(249, 241, 241, 0.85);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        max-width: 420px;
        width: 100%;
    }

    h1 {
        text-align: center;
        color: #d63384;
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"], input[type="password"], input[type="email"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .errors {
        background: #ffe6e6;
        border: 1px solid #ffcccc;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 6px;
    }

    .success {
        background: #e6ffe6;
        border: 1px solid #ccffcc;
        padding: 20px;
        margin-bottom: 10px;
        border-radius: 6px;
        text-align: center;
        font-weight: bold;
        color: #2d6a4f;
    }

    button {
        display: block;
        width: 100%;
        margin-top: 12px;
        padding: 10px 16px;
        font-weight: bold;
        background-color: #e26ac2ff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: white;
        transition: 0.3s;
    }

    button:hover {
        background-color: #ff9bb3;
    }

    /* Style for the small text link */
    .small {
        font-size: 0.9rem;
        margin-top: 12px;
        text-align: center;
    }

    .small a {
        color: #d63384;
        text-decoration: none;
        font-weight: bold;
    }

    .small a:hover {
        text-decoration: underline;
        color: #ff9bb3;
    }
    </style>

</head>
<body>
    <div class="form-container">
        <h1>Sign Up</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                Registration successful! 🎉<br>
                You may now <a href="login.php">login</a>.
            </div>
        <?php else: ?>
            <form method="post" action="">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" required />

                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" required />

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required />

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />

                <button type="submit">Sign Up</button>
            </form>

            <!-- Added this section -->
            <div class="small">
            <a href="login.php" style="color:#d63384; text-decoration:underline;">Already have an account?</a>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>
