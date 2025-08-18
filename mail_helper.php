<?php
include('../config/db.php');
require_once 'email_helper.php';  // <-- Adjust this path to your email_helper.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        header('Location: ../checkout/cart.php');
        exit();
    }

    // Basic validation
    if (!$name || !$email || !$address || !$phone) {
        die('Please fill all required fields.');
    }

    // Fetch products in cart from DB
    $product_ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = mysqli_query($conn, $sql);

    $products_in_cart = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products_in_cart[$row['id']] = $row;
    }

    // Calculate total
    $total = 0;
    foreach ($cart as $id => $qty) {
        $prod = $products_in_cart[$id];
        $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);
        $subtotal = $discounted_price * $qty;
        $total += $subtotal;
    }

    // Insert order into orders table
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, phone, total_amount, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssds", $name, $email, $address, $phone, $total);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert each product in order_items table
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($cart as $id => $qty) {
            $prod = $products_in_cart[$id];
            $discounted_price = $prod['price'] - ($prod['price'] * $prod['discount'] / 100);

            $stmt_items->bind_param("iiid", $order_id, $id, $qty, $discounted_price);
            $stmt_items->execute();
        }
        $stmt_items->close();

        // Prepare items array for email helper
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

        // Send order confirmation email
        $sent = sendOrderConfirmationEmail($email, $name, $order_id, $email_items, $total, $address);

        // Clear the cart
        unset($_SESSION['cart']);

        echo "<h2>Thank you! Your order has been placed successfully.</h2>";

        if ($sent) {
            echo "<p>An order confirmation email has been sent to your email address.</p>";
        } else {
            echo "<p>Unable to send confirmation email at this time.</p>";
        }

        echo "<a href='index.php'>Go back to homepage</a>";
    } else {
        die('Error placing order: ' . $conn->error);
    }
} else {
    header('Location: ../checkout/cart.php');
    exit();
}
?>
