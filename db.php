<?php
$host = 'localhost';
$db   = 'mystore';            // Name of your DB (we'll create this next)
$user = 'postgres';           // Default Postgres user
$pass = 'Abhay2005';          // Your actual password
$port = '5433';               // Your custom port for PostgreSQL 17

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Connected to PostgreSQL!";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
