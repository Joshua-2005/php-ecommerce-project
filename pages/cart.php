<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$total_cost = 0;

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

// Remove item
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
}

// Fetch cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }

        .cart-item img {
            width: 90px;
            border-radius: 8px;
        }

        .details {
            flex: 1;
            margin-left: 20px;
        }

        .details h3 {
            margin: 0;
            color: #444;
        }

        .details p {
            margin: 5px 0;
            color: #555;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .update-btn {
            background: #2196F3;
            color: white;
        }

        .remove-btn {
            background: #f44336;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .nav-buttons a {
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            color: white;
        }

        .shop-btn {
            background: #6c757d;
        }

        .checkout-btn {
            background: #28a745;
        }

        .empty {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Cart</h2>

    <?php if (empty($cart_items)) : ?>
        <p class="empty">Your cart is empty.</p>
    <?php else : ?>

    <?php
    $product_ids = array_column($cart_items, 'product_id');
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {

        $quantity = 0;
        foreach ($cart_items as $cart_item) {
            if ($cart_item['product_id'] == $product['id']) {
                $quantity = $cart_item['quantity'];
                break;
            }
        }

        $subtotal = $product['price'] * $quantity;
        $total_cost += $subtotal;
    ?>

        <div class="cart-item">
            <?php if (!empty($product['image'])) : ?>
                <img src="../images/<?= htmlspecialchars($product['image']) ?>">
            <?php endif; ?>

            <div class="details">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>Price: $<?= number_format($product['price'], 2) ?></p>
                <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
            </div>

            <div class="actions">
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1">
                    <button type="submit" name="update_quantity" class="update-btn">Update</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                </form>
            </div>
        </div>

    <?php } ?>

    <div class="total">
        Total: $<?= number_format($total_cost, 2) ?>
    </div>

    <?php endif; ?>

    <div class="nav-buttons">
        <a href="../index.php" class="shop-btn">Back to Shop</a>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>

</div>

</body>
</html>