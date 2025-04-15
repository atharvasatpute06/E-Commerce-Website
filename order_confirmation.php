<?php
session_start();
include 'db.php';
include 'config.php'; // ✅ Add image path

// Validate order ID
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Fetch order items with images
$stmt = $conn->prepare("SELECT oi.quantity, p.name, p.price, p.image 
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
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
        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content container mt-5">
            <h2>Thank You for Your Order!</h2>
            <p><strong>Order ID:</strong> <?= $order_id ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
            <p><strong>Shipping Address:</strong> <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['zip']) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Total Paid:</strong> ₹<?= number_format($order['total_amount'], 2) ?></p>

            <h4 class="mt-4">Order Items</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>
                                <img src="<?= $imagePath . $item['image'] ?>" class="product-thumbnail" alt="<?= htmlspecialchars($item['name']) ?>">
                            </td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
