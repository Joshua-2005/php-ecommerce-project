<?php
session_start();

// ✅ Protect page - only admin allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$success_message = "";
$error_message = "";

if (isset($_POST['add_product'])) {

    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if (!empty($_FILES['image']['name'])) {

        $image = basename($_FILES['image']['name']);
        $target_path = "../images/" . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $price, $description, $image]);

            $success_message = "Product added successfully!";
        } else {
            $error_message = "Image upload failed.";
        }

    } else {
        $error_message = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        input:focus, textarea:focus {
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

        .success {
            background: rgba(0,255,0,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            background: rgba(255,0,0,0.3);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Add Product</h2>

    <?php if ($success_message): ?>
        <div class="success"><?= htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="error"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Image:</label>
        <input type="file" name="image" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>

</body>
</html>