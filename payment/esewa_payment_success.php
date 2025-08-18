<?php
session_start();
include('config/db.php'); // Adjust path to your DB config

// Get eSewa parameters from GET
$amt = $_GET['amt'] ?? null;      // Paid amount
$pid = $_GET['pid'] ?? null;      // Your internal order ID
$refId = $_GET['refId'] ?? null;  // eSewa transaction reference ID (optional)

// Your eSewa merchant code
$merchant_code = "YOUR_MERCHANT_ID"; // Replace with your actual merchant ID

if (!$amt || !$pid) {
    die("Invalid payment confirmation data.");
}

// eSewa verification URL
$verification_url = "https://esewa.com.np/epay/transrec";

$data = [
    'amt' => $amt,
    'pid' => $pid,
    'scd' => $merchant_code,
    'rid' => $refId
];

// Prepare POST data
$post_data = http_build_query($data);

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verification_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL error: " . curl_error($ch));
}

curl_close($ch);

$response = trim($response);

if ($response === "Success") {
    // Payment verified, update order status to 'paid'
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $pid);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <title>Payment Success</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        </head>
        <body class="bg-green-50 flex items-center justify-center min-h-screen">
            <div class="max-w-md bg-white p-8 rounded shadow text-center">
                <h1 class="text-3xl font-bold mb-4 text-green-700">Payment Successful!</h1>
                <p class="mb-6">Your payment for Order ID <strong>#<?php echo htmlspecialchars($pid); ?></strong> has been confirmed.</p>
                <a href="index.php" class="inline-block bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition">Go to Homepage</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Payment verified but failed to update order status. Please contact support.";
    }
    $stmt->close();
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Payment Failed</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="bg-red-50 flex items-center justify-center min-h-screen">
        <div class="max-w-md bg-white p-8 rounded shadow text-center">
            <h1 class="text-3xl font-bold mb-4 text-red-700">Payment Verification Failed!</h1>
            <p class="mb-6">Your payment could not be verified by eSewa.</p>
            <p>Please <a href="contact.php" class="text-blue-600 underline">contact support</a> if you believe this is an error.</p>
            <a href="cart.php" class="inline-block mt-6 bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">Return to Cart</a>
        </div>
    </body>
    </html>
    <?php
}

$conn->close();
?>
