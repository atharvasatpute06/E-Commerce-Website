<?php
session_start();
include 'db.php';
include 'config.php'; // ✅ Added for image path

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all active products
$stmt = $conn->query("SELECT * FROM products WHERE status = TRUE");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - Welcome</title>
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
        .footer-section {
            max-width: 960px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <!-- ✅ Clapstore Logo Only -->
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.png" alt="Clapstore Logo" style="height: 40px;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content container mt-4">
            <h2 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            <img src="<?= $imagePath . $product['image'] ?>" class="product-image mb-2" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h5><?= htmlspecialchars($product['name']) ?></h5>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong>Price:</strong> ₹<?= number_format($product['price'], 2) ?></p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <p class="text-muted">No products available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Updated Footer -->
        <footer class="bg-dark text-white py-5 mt-5">
            <div class="footer-section">
                <div class="row">
                    <!-- About Us -->
                    <div class="col-md-8">
                        <h5>About Clapstore</h5>
                        <p style="line-height: 1.8;">
                            Clapstore is an innovative toy brand focused on building a screen-free childhood. <br>
                            We design hands-on learning tools like busy boards to develop fine motor skills and imagination. <br>
                            This project is inspired by Clapstore's mission to give skills, not screens.
                        </p>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-md-4">
                        <h5>Contact</h5>
                        <p>Email: support@clapstoretoys.com</p>
                        <p>Phone: +91 98765 43210</p>
                    </div>
                </div>
                <hr class="bg-secondary">
                <p class="text-center text-muted mt-4 mb-0">&copy; 2025 Clapstore. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
