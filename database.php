


<?php
// MySQL database connection
$host = 'localhost';
$db = 'sickle_cell_management';
$user = 'root';     // Default user in XAMPP
$pass = '';         // Default password is empty in XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
