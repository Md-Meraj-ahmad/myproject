<?php
session_start();
include('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header('Location: ../account/reg_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int) $_GET['product_id'] ?? 0;

// Insert into wishlist
if ($product_id > 0) {
    $checkQuery = "SELECT * FROM wishlists WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Only insert if not already in wishlist
    if ($result->num_rows === 0) {
        $insertQuery = "INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    // Optional: Add to session cart too
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;

    header('Location: ../index.php'); // Or back to product page
    exit;
}
?>
