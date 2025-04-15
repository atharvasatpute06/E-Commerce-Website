<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

$total = 0;
$products = [];

// Fetch products in the cart
$placeholders = implode(',', array_fill(0, count($cart), '?'));
$stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute(array_keys($cart));
$products = $stmt->fetchAll();

// Calculate total
foreach ($products as $product) {
    $total += $product['price'] * $cart[$product['id']];
}

$message = "";

// Handle checkout form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $city    = htmlspecialchars($_POST['city']);
    $state   = htmlspecialchars($_POST['state']);
    $zip     = htmlspecialchars($_POST['zip']);
    $payment = htmlspecialchars($_POST['payment_method']);

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, city, state, zip, payment_method, total_amount) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?) RETURNING id");
    $stmt->execute([$name, $email, $address, $city, $state, $zip, $payment, $total]);
    $order_id = $stmt->fetchColumn();

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($cart as $product_id => $quantity) {
        $stmt->execute([$order_id, $product_id, $quantity]);
    }

    // Clear cart
    $_SESSION['cart'] = [];

    // Redirect to confirmation
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
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
            <h2>Checkout</h2>
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>ZIP</label>
                        <input type="text" name="zip" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Total Amount</label>
                        <input type="text" class="form-control" value="₹<?= number_format($total, 2) ?>" readonly>
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Place Order</button>
            </form>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
