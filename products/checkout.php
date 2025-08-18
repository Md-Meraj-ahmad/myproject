<?php
session_start();
include('../config/db.php');

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: cart.php');
    exit();
}

require_once '../path/to/mail_helper.php'; // Update to actual path

// Prepare items for email
$email_items = [];
foreach ($cart as $id => $qty) {
    $prod = $products_in_cart[$id];
    $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);
    $subtotal = $discounted_price * $qty;
    $email_items[] = [
        'name' => $prod['name'],
        'quantity' => $qty,
        'subtotal' => $subtotal,
    ];
}

$sent = sendOrderConfirmationEmail(
    $customer_email,    // e.g. $_POST['email']
    $customer_name,     // e.g. $_POST['name']
    $order_id,
    $email_items,
    $total,             // total amount
    $shipping_address   // e.g. $_POST['address']
);

if ($sent) {
    // Email sent successfully (you can add a success log here)
} else {
    // Log or handle email sending failure
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Checkout</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto py-10 max-w-4xl bg-white rounded shadow p-6">
  <h1 class="text-3xl font-semibold mb-6">Checkout</h1>

  <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
  <ul class="mb-6">
    <?php foreach ($cart as $id => $qty):
        $prod = $products_in_cart[$id];
        $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);
        $subtotal = $discounted_price * $qty;
    ?>
      <li class="flex justify-between border-b py-2">
        <span><?php echo htmlspecialchars($prod['name']) . " x $qty"; ?></span>
        <span>$<?php echo number_format($subtotal, 2); ?></span>
      </li>
    <?php endforeach; ?>
    <li class="flex justify-between font-bold pt-3">
      <span>Total</span>
      <span>$<?php echo number_format($total, 2); ?></span>
    </li>
  </ul>

  <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>

  <form action="process_order.php" method="POST" class="space-y-4">
    <div>
      <label for="name" class="block font-medium mb-1">Full Name</label>
      <input type="text" name="name" id="name" required class="w-full p-2 border border-gray-300 rounded" />
    </div>
    <div>
      <label for="email" class="block font-medium mb-1">Email Address</label>
      <input type="email" name="email" id="email" required class="w-full p-2 border border-gray-300 rounded" />
    </div>
    <div>
      <label for="address" class="block font-medium mb-1">Shipping Address</label>
      <textarea name="address" id="address" required class="w-full p-2 border border-gray-300 rounded"></textarea>
    </div>
    <div>
      <label for="phone" class="block font-medium mb-1">Phone Number</label>
      <input type="tel" name="phone" id="phone" required class="w-full p-2 border border-gray-300 rounded" />
    </div>

    <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded hover:bg-indigo-700 transition">
      Place Order
    </button>
  </form>
</div>

</body>
</html>
