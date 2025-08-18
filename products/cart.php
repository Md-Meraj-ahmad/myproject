<?php
session_start();
include('../config/db.php');

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<div class='container mx-auto py-10'><h1 class='text-2xl font-semibold'>Your cart is empty.</h1></div>";
    exit();
}

// Fetch product info for all product IDs in the cart
$product_ids = implode(',', array_keys($cart));
$sql = "SELECT * FROM products WHERE id IN ($product_ids)";
$result = mysqli_query($conn, $sql);

$products_in_cart = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products_in_cart[$row['id']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Your Shopping Cart</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto py-10 max-w-5xl bg-white rounded shadow p-6">
  <h1 class="text-3xl font-semibold mb-6">Shopping Cart</h1>

  <table class="w-full text-left border-collapse">
    <thead>
      <tr>
        <th class="border-b p-3">Product</th>
        <th class="border-b p-3">Price</th>
        <th class="border-b p-3">Quantity</th>
        <th class="border-b p-3">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      foreach ($cart as $id => $qty):
        $prod = $products_in_cart[$id];
        $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);
        $subtotal = $discounted_price * $qty;
        $total += $subtotal;
      ?>
      <tr>
        <td class="border-b p-3"><?php echo htmlspecialchars($prod['name']); ?></td>
        <td class="border-b p-3">$<?php echo number_format($discounted_price, 2); ?></td>
        <td class="border-b p-3"><?php echo $qty; ?></td>
        <td class="border-b p-3">$<?php echo number_format($subtotal, 2); ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="font-bold">
        <td colspan="3" class="p-3 text-right">Total:</td>
        <td class="p-3">$<?php echo number_format($total, 2); ?></td>
      </tr>
    </tbody>
  </table>

  <div class="mt-6">
    <a href="checkout.php" class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">Proceed to Checkout</a>
  </div>
</div>

</body>
</html>
