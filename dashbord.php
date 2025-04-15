<?php
session_start();
include 'db.php';

// Allow only logged-in admins
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Soft delete handler
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];
    $stmt = $conn->prepare("UPDATE products SET status = FALSE WHERE id = ?");
    $stmt->execute([$product_id]);
    header("Location: dashboard.php");
    exit();
}

// Fetch all products with categories
$stmt = $conn->query("SELECT p.*, c.name AS category FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - MyStore</title>
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
        .badge-inactive {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">MyStore</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="content container mt-4">
            <h2>Admin Dashboard</h2>
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td>
                                <?php if ($product['status']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['status']): ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="delete_product" value="<?= $product['id'] ?>">
                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <em>—</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="6" class="text-center text-muted">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer>
            &copy; 2025 MyStore Admin Panel. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
