<?php
session_start();
include 'db.php'; // PostgreSQL connection
include 'config.php'; // ✅ Added for image path

// Fetch active products and join with category
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.status = TRUE";
$stmt = $conn->query($sql);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
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
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            height: 100%;
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content container mt-4">
            <h2 class="mb-4">Our Products</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            <img src="<?= $imagePath . $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image mb-2">
                            <h5><?= htmlspecialchars($product['name']) ?></h5>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                            <p><strong>Price:</strong> ₹<?= number_format($product['price'], 2) ?></p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <div class="col-12">
                        <p class="text-muted">No products available right now.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
