<?php
session_start();
include 'db.php'; // PostgreSQL connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Validate input
    if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity < 1) {
        header("Location: shop.php");
        exit();
    }

    // Check if product exists and is active
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND status = TRUE");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: shop.php");
        exit();
    }

    // Initialize cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update item in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Redirect to cart
    header("Location: cart.php");
    exit();
} else {
    // Redirect on direct access
    header("Location: shop.php");
    exit();
}
