<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: manage_products.php");
    exit();
}

if (isset($_POST['update_product'])) {

    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = $product['image'];

    if (!empty($_FILES['image']['name'])) {

        if (!empty($product['image'])) {
            $old_path = "../images/" . $product['image'];
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }

        $image = basename($_FILES['image']['name']);
        $target_path = "../images/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=? WHERE id=?");
    $stmt->execute([$name, $price, $description, $image, $id]);

    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4CAF50, #2196F3);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        input[type="file"] {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        img {
            border-radius: 8px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .update-btn {
            background-color: #28a745;
            border: none;
        }

        .update-btn:hover {
            background-color: #218838;
        }

        .back-btn {
            background-color: #6c757d;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Product Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Current Image:</label>
        <?php if (!empty($product['image'])) : ?>
            <img src="../images/<?= htmlspecialchars($product['image']) ?>" width="120">
        <?php endif; ?>

        <label>Change Image (optional):</label>
        <input type="file" name="image">

        <div class="button-group">
            <button type="submit" name="update_product" class="btn update-btn">Update Product</button>
            <a href="manage_products.php" class="btn back-btn">Back</a>
        </div>

    </form>
</div>

</body>
</html>