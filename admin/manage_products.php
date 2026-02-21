<?php
session_start();

// ✅ Admin protection
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch all products
$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            width: 85%;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        img {
            width: 60px;
            height: auto;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .no-products {
            text-align: center;
            padding: 20px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>

    <?php if (empty($products)) : ?>
        <div class="no-products">No products found.</div>
    <?php else : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?= $product['id']; ?></td>
                    <td><?= htmlspecialchars($product['name']); ?></td>
                    <td>$<?= number_format($product['price'], 2); ?></td>
                    <td><?= htmlspecialchars($product['description']); ?></td>
                    <td>
                        <?php if (!empty($product['image'])) : ?>
                            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image">
                        <?php else : ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a class="btn edit-btn" href="edit_product.php?id=<?= $product['id']; ?>">Edit</a>
                        <a class="btn delete-btn"
                           href="delete_product.php?id=<?= $product['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this product?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php endif; ?>

    <br>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>