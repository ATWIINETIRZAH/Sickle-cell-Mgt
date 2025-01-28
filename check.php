


<!-- header("Location: dashboard.php");
exit(); -->


<?php
require 'database.php';

try {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();

    if ($users) {
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    } else {
        echo "No users found in the database.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>