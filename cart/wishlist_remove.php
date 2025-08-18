<?php
session_start();
include('../config/db.php');

$user_id = $_SESSION['user_id'] ?? null;
$product_id = (int) $_GET['product_id'] ?? 0;

if ($user_id && $product_id) {
    $stmt = $conn->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

header('Location: wishlist.php');
exit;
?>
