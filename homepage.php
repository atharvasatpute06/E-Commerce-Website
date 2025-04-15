<?php
session_start();
include 'db.php';

// Fetch 4 featured/active products
$stmt = $conn->query("SELECT * FROM products WHERE status = TRUE LIMIT 4");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to MyStore</title>
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
        .hero {
            background: #f5f5f5;
            padding: 60px 20px;
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
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="homepage.php">MyStore</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to MyStore</h1>
            <p>Your one-stop shop for awesome products.</p>
            <a href="shop.php" class="btn btn-primary btn-lg me-2">Shop Now</a>
            <a href="register.php" class="btn btn-outline-secondary btn-lg">Register</a>
        </div>

        <!-- Featured Products -->
        <div class="content container mt-5">
            <h3 class="mb-4">Featured Products</h3>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 mb-4">
                        <div class="product-card">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="product-image mb-2" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h5><?= htmlspecialchars($product['name']) ?></h5>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>₹<?= number_format($product['price'], 2) ?></strong></p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <p class="text-muted">No featured products at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            &copy; 2025 MyStore. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
