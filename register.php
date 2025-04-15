<?php
session_start();
include 'db.php'; // PostgreSQL connection using PDO

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = htmlspecialchars($_POST["name"]);
    $email    = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "⚠️ Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['is_admin'] = false;

            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
        <div class="content container mt-5">
            <h2>Register</h2>
            <?php if ($message): ?>
                <div class="alert alert-warning"><?= $message ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <button class="btn btn-primary" type="submit">Register</button>
            </form>
        </div>
        <footer>
            &copy; 2025 Your E-commerce Store. All rights reserved.
        </footer>
    </div>
</body>
</html>
