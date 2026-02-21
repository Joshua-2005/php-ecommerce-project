<?php
include('../includes/db.php');
session_start();

$error_message = "";
$success_message = "";

if (isset($_POST['register'])) {

    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        $error_message = "Email already registered!";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);

        $success_message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .register-box h2 {
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
            background: #ff6f61;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #e85a4f;
            transform: scale(1.05);
        }

        .error {
            background: rgba(255,0,0,0.3);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .success {
            background: rgba(0,255,0,0.25);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .extra-links {
            margin-top: 15px;
        }

        .extra-links a {
            color: white;
            text-decoration: underline;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>

    <?php if ($error_message): ?>
        <div class="error"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="success"><?= htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <div class="extra-links">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
</html>