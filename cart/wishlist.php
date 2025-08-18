<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/reg_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "
    SELECT p.* FROM wishlists w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">💖 Your Wishlist</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <img src="../assets/images/product_images/<?php echo htmlspecialchars($product['image']); ?>" alt="" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-600 mt-2">₹<?php echo number_format($product['price'], 2); ?></p>
                    <div class="mt-4">
                        <a href="../order/process_order.php?product_id=<?php echo $product['id']; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Buy Now</a>
                        <a href="wishlist_remove.php?product_id=<?php echo $product['id']; ?>" class="bg-red-100 text-red-500 px-4 py-2 rounded hover:underline ml-4">Remove</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
