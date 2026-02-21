<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Get cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

$total = 0;

// Calculate total
foreach ($cart_items as $item) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$item['product_id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $total += $product['price'] * $item['quantity'];
}

if (isset($_POST['place_order'])) {

    $address = trim($_POST['address']);
    $payment = $_POST['payment'];

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, address, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $total, $address, $payment]);

    $order_id = $conn->lastInsertId();

    // Insert order items
    foreach ($cart_items as $item) {

        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $product['price']]);
    }

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    header("Location: order_success.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #2196F3, #4CAF50);
        }
        .box {
            width: 50%;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Checkout</h2>
    <p><strong>Total Amount:</strong> $<?= number_format($total,2) ?></p>

    <form method="POST">
        <label>Delivery Address:</label><br>
        <textarea name="address" required style="width:100%;height:80px;"></textarea><br><br>

        <label>Payment Method:</label><br>
        <select name="payment" required style="width:100%;padding:10px;">
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="UPI">UPI</option>
            <option value="Card">Card</option>
        </select><br><br>

        <button type="submit" name="place_order">Place Order</button>
    </form>
</div>

</body>
</html>