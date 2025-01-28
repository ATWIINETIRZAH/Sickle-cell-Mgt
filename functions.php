

<?php
require 'database.php';

function loginUser($username, $password) {
    global $pdo;

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Check if the user exists and the password matches
    if ($user && $password == $user['password']) {
        return $user; // Return user data
    }
    return false;
}

function checkRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}
?>


