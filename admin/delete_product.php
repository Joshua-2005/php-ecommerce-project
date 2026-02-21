<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    // Get product image
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {

        // Delete cart records first (IMPORTANT)
        $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
        $stmt->execute([$id]);

        // Delete image file
        if (!empty($product['image'])) {
            $image_path = "../images/" . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: manage_products.php");
exit();
?>