<?php
session_start();
include 'db.php'; // PostgreSQL connection

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0.0;
$products = [];

// Handle update quantity
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle remove single item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_item'])) {
    $remove_id = $_POST['remove_item'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

// Fetch product data
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content container mt-4">
            <h2>Your Shopping Cart</h2>
            <?php if (!empty($products)): ?>
                <form method="post">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): 
                                $id = $product['id'];
                                $quantity = $cart[$id];
                                $subtotal = $product['price'] * $quantity;
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td>₹<?= number_format($product['price'], 2) ?></td>
                                    <td>
                                        <input type="number" name="quantities[<?= $id ?>]" value="<?= $quantity ?>" min="1" class="form-control" style="width: 80px;">
                                    </td>
                                    <td>₹<?= number_format($subtotal, 2) ?></td>
                                    <td>
                                        <button type="submit" name="remove_item" value="<?= $id ?>" class="btn btn-sm btn-danger">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p><strong>Total:</strong> ₹<?= number_format($total, 2) ?></p>
                    <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
                    <a href="checkout.php" class="btn btn-success ms-2">Proceed to Checkout</a>
                </form>
            <?php else: ?>
                <p class="text-muted">Your cart is empty.</p>
                <a href="shop.php" class="btn btn-outline-primary">Go to Shop</a>
            <?php endif; ?>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
