<?php
session_start();
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone   = trim($_POST['phone']);
    $cart    = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        header('Location: cart.php');
        exit();
    }

    if (!$name || !$email || !$address || !$phone) {
        die('Please fill all required fields.');
    }

    // Fetch products and calculate total
    $product_ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = mysqli_query($conn, $sql);

    $products_in_cart = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products_in_cart[$row['id']] = $row;
    }

    $total = 0;
    foreach ($cart as $id => $qty) {
        $prod = $products_in_cart[$id];
        $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);
        $subtotal = $discounted_price * $qty;
        $total += $subtotal;
    }

    // Insert order with status 'pending'
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, phone, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("sssds", $name, $email, $address, $phone, $total);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($cart as $id => $qty) {
            $prod = $products_in_cart[$id];
            $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);

            $stmt_items->bind_param("iiid", $order_id, $id, $qty, $discounted_price);
            $stmt_items->execute();
        }
        $stmt_items->close();

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to eSewa for payment
        $merchant_code = "YOUR_MERCHANT_ID"; // Replace with your actual merchant code
        $total_formatted = number_format($total, 2, '.', '');
        $return_url = "https://yourdomain.com/esewa_order_success.php?order_id=$order_id";
        $cancel_url = "https://yourdomain.com/esewa_order_failure.php?order_id=$order_id";

        $esewa_url = "https://esewa.com.np/mobileapp/?amt=$total_formatted&scd=$merchant_code&pid=$order_id&su=$return_url&fu=$cancel_url";

        header("Location: $esewa_url");
        exit();

    } else {
        die('Error placing order: ' . $conn->error);
    }
} else {
    header('Location: cart.php');
    exit();
}
