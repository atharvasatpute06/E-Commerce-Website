<?php
session_start();
include 'db.php'; // PostgreSQL connection
include 'config.php'; // ✅ For image path

// Validate product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT p.*, c.name AS category_name 
                        FROM products p
                        JOIN categories c ON p.category_id = c.id
                        WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
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
        .product-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= $imagePath . $product['image'] ?>" class="product-image" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="col-md-6">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                    <p><strong>Price:</strong> ₹<?= number_format($product['price'], 2) ?></p>

                    <form action="add_to_cart.php" method="post" class="mt-3">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
