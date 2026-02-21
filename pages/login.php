<?php
include('../includes/db.php');
session_start();

$error_message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .login-box h2 {
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 6px #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #28a745;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .error {
            background: rgba(255,0,0,0.25);
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .extra-links {
            margin-top: 15px;
        }

        .extra-links a {
            color: #fff;
            text-decoration: underline;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>User Login</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php if ($error_message): ?>
        <div class="error"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="extra-links">
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</div>

</body>
</html>