<?php
session_start();
include 'includes/db.php';

$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        header {
            background: rgba(0,0,0,0.6);
            padding: 15px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav a {
            margin-left: 15px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        .card img {
            width: 120px;
            height: auto;
            border-radius: 8px;
        }

        .price {
            color: #28a745;
            font-weight: bold;
            margin: 10px 0;
        }

        button {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #1976D2;
        }

        footer {
            text-align: center;
            color: white;
            padding: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>My E-Commerce Store</h1>
    <nav>
        <?php if (!isset($_SESSION['user_id'])) : ?>
            <a href="pages/login.php">Login</a>
            <a href="pages/register.php">Register</a>
        <?php else : ?>
            <?php if ($_SESSION['role'] === 'admin') : ?>
                <a href="admin/dashboard.php">Admin</a>
            <?php endif; ?>
            <a href="pages/cart.php">Cart</a>
            <a href="pages/logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <div class="products">
        <?php foreach ($products as $product) : ?>
            <div class="card">
                <?php if (!empty($product['image'])) : ?>
                    <img src="images/<?= htmlspecialchars($product['image']) ?>">
                <?php endif; ?>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p class="price">$<?= number_format($product['price'],2) ?></p>
                <p><?= htmlspecialchars($product['description']) ?></p>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                <?php else : ?>
                    <a href="pages/login.php">Login to Buy</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    &copy; <?= date("Y"); ?> My Portfolio E-Commerce
</footer>

</body>
</html>