<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .container {
            width: 60%;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            color: white;
            transition: 0.3s;
        }

        .add { background: #2196F3; }
        .manage { background: #ff9800; }
        .logout { background: #f44336; }

        .btn:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        footer {
            text-align: center;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Dashboard</h2>

    <div class="buttons">
        <a href="add_product.php" class="btn add">Add Product</a>
        <a href="manage_products.php" class="btn manage">Manage Products</a>
        <a href="logout.php" class="btn logout">Logout</a>
    </div>
</div>

<footer>
    &copy; <?= date("Y"); ?> Admin Panel
</footer>

</body>
</html>